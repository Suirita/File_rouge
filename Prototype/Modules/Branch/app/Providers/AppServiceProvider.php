<?php

namespace Modules\Branch\app\Providers;

use Modules\Branch\app\Models\Branch;
use Modules\Branch\app\Policies\BranchPolicy;
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    protected $policies = [
        Branch::class => BranchPolicy::class,
    ];

    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
