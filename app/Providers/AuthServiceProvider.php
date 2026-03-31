<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Taxpayer;
use App\Policies\CollectorPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\PaymentPolicy;
use App\Policies\RolePolicy;
use App\Policies\TaxpayerPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Spatie\Permission\Models\Role;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        User::class => UserPolicy::class,
        Role::class => RolePolicy::class,
        Invoice::class => InvoicePolicy::class,
        Payment::class => PaymentPolicy::class,
        Taxpayer::class => TaxpayerPolicy::class,
    ];
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();
        Gate::define('update-user', [UserPolicy::class, 'update']);
        Gate::define('create-user', [UserPolicy::class, 'create']);
        Gate::define('delete-user', [UserPolicy::class, 'delete']);
        Gate::define('update-role', [UserPolicy::class, 'update']);
        Gate::define('create-role', [UserPolicy::class, 'create']);
        Gate::define('delete-role', [UserPolicy::class, 'delete']);

        Gate::define('update-collector', [CollectorPolicy::class, 'update']);
        Gate::define('create-collector', [CollectorPolicy::class, 'create']);
        Gate::define('delete-collector', [CollectorPolicy::class, 'delete']);
    }
}
