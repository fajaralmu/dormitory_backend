<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'App\Models\Cbt' => 'App\Policies\CbtPolicy',
        'App\Models\Study' => 'App\Policies\StudyPolicy',
        'App\Models\Lesson' => 'App\Policies\LessonPolicy',
        'App\Models\Course' => 'App\Policies\CoursePolicy',
        'App\Models\Episode' => 'App\Policies\EpisodePolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //
    }
}
