<?php

namespace App\Providers;

use App\Services\AuthService;
use App\Services\ConfigurationService;
use App\Services\MasterDataService;
use App\Services\MusyrifManagementService;
use App\Services\StudentPointService;
use App\Services\StudentService;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use App\Socialite\LaravelPassportProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        $authService = new AuthService();
        $configService = new ConfigurationService();
        $musyrifManagementService = new MusyrifManagementService();
        $studentService = new StudentService();
        $masterDataService = new MasterDataService();
        $this->app->bind(MasterDataService::class, function ($app) use ($masterDataService) {
            return $masterDataService;
        });
        $this->app->bind(StudentPointService::class, function ($app) {
            return new StudentPointService();
        });
        $this->app->bind(AuthService::class, function ($app) use ($authService) {
            return $authService;
        });
        $this->app->bind(StudentService::class, function ($app) use ($studentService) {
            return $studentService;
        });
        $this->app->bind(MusyrifManagementService::class, function ($app) use ($musyrifManagementService) {
            return $musyrifManagementService;
        });
        $this->app->bind(ConfigurationService::class, function ($app) use ($configService) {
            return $configService;
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Blade::component('components.card', 'card');
        Blade::component('components.modal', 'modal');

        $socialite = $this->app->make(\Laravel\Socialite\Contracts\Factory::class);
        $socialite->extend(
            'portalsekolah',
            function ($app) use ($socialite) {
                $config = config('services.portalsekolah');
                $config['guzzle'] = ['verify' => false];

                return $socialite->buildProvider(LaravelPassportProvider::class, $config);
            }
        );
    }
}
