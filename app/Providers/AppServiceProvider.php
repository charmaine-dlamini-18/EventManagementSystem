<?php

namespace App\Providers;

use App\Models\Event;
use App\Models\Registration;
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
        Event::observe(EventObserver::class);
        Registration::observe(RegistrationObserver::class);

        // --- Policies ---
        // Maps models to their policy classes.
        // Laravel uses these automatically when $this->authorize() is called.
        Gate::policy(Event::class, EventPolicyCMS::class);
        Gate::policy(Registration::class, RegistrationPolicyCMS::class);

        // --- Gates ---
        // Simple boolean checks for role-based UI controls.
        // Use in Blade: @can('manage-events') or in controllers: Gate::allows('manage-events')

        /**
         * Gate: 'manage-events'
         * True for admins and organizers.
         * Used to show/hide event create/edit/delete buttons in the UI.
         */
        Gate::define('manage-events', function ($user) {
            return in_array($user->role, ['admin', 'organizer']);
        });

        /**
         * Gate: 'manage-registrations'
         * True for admins and organizers.
         * Used to show/hide the registration approval/decline panel.
         */
        Gate::define('manage-registrations', function ($user) {
            return in_array($user->role, ['admin', 'organizer']);
        });

        /**
         * Gate: 'admin-only'
         * True only for admins.
         * Use to protect admin-only dashboard sections.
         */
        Gate::define('admin-only', function ($user) {
            return $user->role === 'admin';
        });
    }
}