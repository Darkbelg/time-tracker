<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use App\Models\User;
use App\Models\TimeEntry;
use Illuminate\Support\Str;
use App\Policies\ActivityPolicy;
use Laravel\Pulse\Facades\Pulse;
use App\Policies\TimeEntryPolicy;
use Illuminate\Support\Facades\Gate;
use Spatie\Activitylog\Models\Activity;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        Activity::class => ActivityPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        Gate::define('viewPulse', function (User $user) {
            return Str::startsWith($user->email, 'stijn.sagaert');
        });

        Pulse::user(fn ($user) => [
            'name' => $user->first_name . ' ' . $user->last_name,
            'extra' => $user->email,
        ]);
    }
}
