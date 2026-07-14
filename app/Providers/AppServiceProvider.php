<?php

namespace App\Providers;

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

    public function boot(): void
    {
        \Illuminate\Database\Eloquent\Relations\Relation::morphMap([
            'contract' => \App\Models\Contract::class,
            'specific' => \App\Models\Specific::class,
            'residence' => \App\Models\SpecificResidenceAgreement::class,
            'internship' => \App\Models\IndividualInternshipAgreement::class,
        ]);
    }
}
