<?php

namespace App\Providers;

use App\Models\Consultation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

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
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view): void {
            if (! Auth::check()) {
                $view->with('pendingPreventiveNotifications', 0);
                return;
            }

            $targetDate = now()->addDays(2)->toDateString();

            $pendingCount = Consultation::query()
                ->whereDate('next_vaccination_at', $targetDate)
                ->count()
                + Consultation::query()
                    ->whereDate('next_deworming_at', $targetDate)
                    ->count();

            $view->with('pendingPreventiveNotifications', $pendingCount);
        });
    }
}
