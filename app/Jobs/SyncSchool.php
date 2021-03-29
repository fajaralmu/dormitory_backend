<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use GuzzleHttp\Client;
use Batch;
use Arr;

class SyncSchool implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $schoolId;
    protected $isUpdate;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($schoolId, $isUpdate)
    {
        $this->schoolId = $schoolId;
        $this->isUpdate = $isUpdate;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $data = $this->retrieveSchoolData();
        $subscription = array_get($data, 'subscription');
        $kelas = array_get($data, 'kelas');
        $index = 'id';

        $kelas_ids = Arr::pluck($kelas, 'id');
        $yayasan_id = array_get($data, 'yayasan_id');

        if ($this->isUpdate) {
            // Users
            $old_users_ids = \DB::table('users')->where('yayasan_id', $yayasan_id)->pluck('id')->toArray();
            $users_ids = Arr::pluck($this->extractUsers($data), 'id');

            $user_ids_for_insert = array_diff($users_ids, $old_users_ids);
            $user_for_insert = array_filter($this->extractUsers($data), function ($user) use ($user_ids_for_insert) {
                return in_array($user['id'], $user_ids_for_insert);
            });
            \DB::table('users')->insert($user_for_insert);
            Batch::update(new \App\Models\User, $this->extractUsers($data), $index);

            // Siswa
            $old_siswa_ids = \DB::table('siswa')->whereIn('kelas_id', $kelas_ids)->pluck('id')->toArray();
            $siswa_ids = Arr::pluck($this->extractStudents($data), 'id');

            $siswa_ids_for_insert = array_diff($siswa_ids, $old_siswa_ids);
            $siswa_for_insert = array_filter($this->extractStudents($data), function ($student) use ($siswa_ids_for_insert) {
                return in_array($student['id'], $siswa_ids_for_insert);
            });
            \DB::table('siswa')->insert($siswa_for_insert);
            Batch::update(new \App\Models\Siswa, $this->extractStudents($data), $index);

            // Pegawai
            $old_pegawai_ids = \DB::table('pegawai')->where('yayasan_id', $yayasan_id)->pluck('id')->toArray();
            $pegawai_ids = Arr::pluck($this->extractEmployees($data), 'id');

            $pegawai_ids_for_insert = array_diff($pegawai_ids, $old_pegawai_ids);
            $pegawai_for_insert = array_filter($this->extractEmployees($data), function ($pegawai) use ($pegawai_ids_for_insert) {
                return in_array($pegawai['id'], $pegawai_ids_for_insert);
            });
            \DB::table('pegawai')->insert($pegawai_for_insert);

            $employeesPivotData = array_filter($this->extractEmployeesPivot($data), function ($ep) use ($pegawai_ids_for_insert) {
                return in_array($ep['pegawai_id'], $pegawai_ids_for_insert);
            });
            \DB::table('pegawai_sekolah')->insert($employeesPivotData);
            Batch::update(new \App\Models\Pegawai, $this->extractEmployees($data), $index);
            // Batch::update(new \App\Models\PegawaiSekolah, $this->extractEmployeesPivot($data), $index);

            Batch::update(new \App\Models\Subscription, [$subscription], $index);
            Batch::update(new \App\Models\Kelas, $kelas, $index);

            \Arr::forget($data, ['subscription', 'students', 'employees', 'kelas', 'mapels', 'syncronized_apps']);

            // encode json in data sekolah
            $data['options'] = json_encode($data['options']);
            $data['struktur_organisasi'] = json_encode($data['struktur_organisasi']);

            Batch::update(new \App\Models\Sekolah, [$data], $index);
        } else {
            \DB::table('users')->insert($this->extractUsers($data));
            \DB::table('siswa')->insert($this->extractStudents($data));
            \DB::table('pegawai')->insert($this->extractEmployees($data));
            \DB::table('pegawai_sekolah')->insert($this->extractEmployeesPivot($data));
            \DB::table('subscriptions')->insert($subscription);
            \DB::table('kelas')->insert($kelas);

            \Arr::forget($data, ['subscription', 'students', 'employees', 'kelas', 'mapels', 'syncronized_apps']);

            // encode json in data sekolah
            $data['options'] = json_encode($data['options']);
            $data['struktur_organisasi'] = json_encode($data['struktur_organisasi']);
            \DB::table('sekolah')->insert($data);
        }
    }

    public function extractStudents($data)
    {
        $students = array_map(function ($student) {
            \Arr::forget($student, ['user', 'laravel_through_key']);
            $student['graduated'] = (int) $student['graduated'];
            $student['is_beasiswa'] = (int) $student['is_beasiswa'];
            return $student;
        }, array_get($data, 'students', []));

        return collect($students)->unique()->toArray();
    }

    public function extractEmployees($data)
    {
        $yayasan_id = $data['yayasan_id'];
        $old_employees = \DB::table('pegawai')->where('yayasan_id', $yayasan_id)->pluck('nip')->toArray();

        // prevent pegawai duplication
        $employees_data = array_get($data, 'employees', []);

        if (count($old_employees) && ! $this->isUpdate) {
            $employees_data = array_filter(array_get($data, 'employees', []), function ($item) use ($yayasan_id, $old_employees) {
                return ! in_array($item['nip'], $old_employees);
            });
        }

        $employees = array_map(function ($employee) {
            \Arr::forget($employee, ['user', 'pivot']);
            return $employee;
        }, $employees_data);

        return collect($employees)->unique()->toArray();
    }

    public function extractEmployeesPivot($data)
    {
        return collect($data['employees'])->pluck('pivot')->toArray();
    }

    public function extractMapels($data)
    {
        $mapels = array_map(function ($mapel) {
            \Arr::forget($mapel, 'distribusi_mengajar');
            $mapel['is_muatan_lokal'] = (int) $mapel['is_muatan_lokal'];
            return $mapel;
        }, array_get($data, 'mapels', []));

        return collect($mapels)->unique()->toArray();
    }

    public function extractDistribusiMengajar($data)
    {
        $items = [];
        $distribusis = collect(array_get($data, 'mapels', []))->pluck('distribusi_mengajar')->toArray();
        foreach ($distribusis as $key => $value) {
            foreach ($value as $item) {
                $item['active'] = (int) $item['active'];
                array_push($items, $item);
            }
        }
        // return $items;
        return collect($items)->unique()->toArray();
    }

    public function extractUsers($data)
    {
        $yayasan_id = $data['yayasan_id'];
        $old_employees = \DB::table('pegawai')->where('yayasan_id', $yayasan_id)->pluck('nip')->toArray();
        $users = [];
        foreach (['students', 'employees'] as $type) {
            $source = $data[$type];
            if ($type === 'employees') {
                // prevent pegawai duplication
                $source = array_get($data, 'employees', []);

                if (count($old_employees)) {
                    $source = array_filter(array_get($data, 'employees', []), function ($item) use ($yayasan_id, $old_employees) {
                        return ! in_array($item['nip'], $old_employees);
                    });
                }
            }

            foreach ($source as $item) {
                $user = $item['user'];
                $user['roles'] = json_encode($user['roles']);
                $user['password'] = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
                array_push($users, $user);
            }
        }
        return collect($users)->unique()->toArray();
    }

    public function retrieveSchoolData()
    {
        $client = new Client(['verify' => false]);

        $url = 'https://portalsekolah.test/api/syncdata';

        if (config('app.env')  === 'production') {
            $url = 'https://myschool.web.id/api/syncdata';
        }

        $response = $client->post($url, [
            'headers' => [
                'Accept' => 'application/json',
                'Authorization' => 'Bearer ' . $this->getAccessToken($client)
            ],
            'form_params' => [
                'school_id' => $this->schoolId
            ]
        ]);

        $data = json_decode((string) $response->getBody(), true);

        return $data;
    }

    public function getAccessToken($client)
    {
        $url = config('services.machine_portalsekolah.token_url');

        $response = $client->post($url, [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => config('services.machine_portalsekolah.client_id'),
                'client_secret' => config('services.machine_portalsekolah.client_secret'),
                'scope' => '*',
            ],
        ]);

        return json_decode((string) $response->getBody(), true)['access_token'];
    }
}
