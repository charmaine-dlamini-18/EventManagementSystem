<?php

namespace App\Providers;

use App\Models\EventCMS;
use App\Models\RegistrationCMS;
use App\Observers\EventObserver;
use App\Observers\RegistrationObserver;
use App\Policies\EventPolicyCMS;
use App\Policies\RegistrationPolicyCMS;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     * Registers Policies and Gates for the EMS application.
     */
    public function boot(): void
    {
        // --- Observers ---
        // Automatically react to Eloquent model lifecycle events.
        EventCMS::observe(EventObserver::class);
        RegistrationCMS::observe(RegistrationObserver::class);

        // --- Policies ---
        // Maps models to their policy classes.
        // Laravel uses these automatically when $this->authorize() is called.
        Gate::policy(EventCMS::class, EventPolicyCMS::class);
        Gate::policy(RegistrationCMS::class, RegistrationPolicyCMS::class);

        // --- Gates ---
        // Simple boolean checks for role-based UI controls.
        // Use in Blade: @can('manage-events-cms') or in controllers: Gate::allows('manage-events-cms')

        /**
         * Gate: 'manage-events-cms'
         * True only for organizers.
         * Used to show/hide event create/edit/delete buttons in the UI.
         */
        Gate::define('manage-events-cms', function ($user) {
            return $user->role === 'organizer';
        });

        /**
         * Gate: 'manage-registrations-cms'
         * True only for organizers.
         * Used to show/hide the registration approval/decline panel.
         */
        Gate::define('manage-registrations-cms', function ($user) {
            return $user->role === 'organizer';
        });

        /**
         * Gate: 'admin-only-cms'
         * True only for admins.
         * Use to protect admin-only dashboard sections.
         */
        Gate::define('admin-only-cms', function ($user) {
            return $user->role === 'admin';
        });
    }
}