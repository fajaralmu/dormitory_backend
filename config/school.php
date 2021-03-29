<?php

return [
	'm2m_sync_secret' => env('M2M_SYNC_SECRET'),
	'tahun_ajaran' => '2019-2020',
	'semester' => 1,
	'pilihan_jurusan' => [
		'0' => 'Non Jurusan',
		'1' => 'IPA',
		'2' => 'IPS',
		'3' => 'Bahasan',
		'4' => 'Keagamaan',
	],
	'penilaian' => [
		'jenis' => [
			'pengetahuan' => ['tulis', 'lisan', 'tugas'],
			'keterampilan' => ['kinerja', 'proyek', 'portofolio'],
		],
		'bobot' => [
			'pengetahuan' => [
				'ph' => 30,
				'pts' => 30,
				'pas' => 40
			],
			'keterampilan' => [
				'kinerja' => 40,
				'proyek' => 30,
				'portofolio' => 30
			]
		],
		'range_predikat' => ['A', 'B', 'C', 'D'],
		'periode' => [
			'ph' => 'Harian',
			'pts' => 'Tengah Semester',
			'pas' => 'Akhir Semester',
		]
	],
	'cbt' => [
		'jenis_pertanyaan' => [
            'pg' => 'Pilihan Ganda',
            'es' => 'Essay'
		],
		'opsi_jawaban' => [
            'dasar' => ['A', 'B', 'C', 'D'],
            'menengah_pertama' => ['A', 'B', 'C', 'D'],
            'menengah_atas' => ['A', 'B', 'C', 'D', 'E'],
        ],
	],
];