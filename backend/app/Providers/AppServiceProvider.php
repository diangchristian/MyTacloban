<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(
            \App\Contracts\BarangayRepositoryInterface::class, 
            \App\Repositories\BarangayRepository::class);

        $this->app->bind(
            \App\Contracts\AnnouncementRepositoryInterface::class, 
            \App\Repositories\AnnouncementRepository::class);

        $this->app->bind(
            \App\Contracts\EventRepositoryInterface::class, 
            \App\Repositories\EventRepository::class);

        $this->app->bind(
            \App\Contracts\ReportRepositoryInterface::class, 
            \App\Repositories\ReportRepository::class);
        
        $this->app->bind(
            \App\Contracts\AnnouncementCategoryRepositoryInterface::class, 
            \App\Repositories\AnnouncementCategoryRepository::class);

        $this->app->bind(
            \App\Contracts\EventCategoryRepositoryInterface::class, 
            \App\Repositories\EventCategoryRepository::class);
        
        $this->app->bind(
            \App\Contracts\ReportCategoryRepositoryInterface::class, 
            \App\Repositories\ReportCategoryRepository::class);

        $this->app->bind(
            \App\Contracts\UserRepositoryInterface::class, 
            \App\Repositories\UserRepository::class);

        $this->app->bind(
            \App\Contracts\ReportTimelineRepositoryInterface::class, 
            \App\Repositories\ReportTimelineRepository::class);

        $this->app->bind(
            \App\Contracts\BarangayOfficialRepositoryInterface::class, 
            \App\Repositories\BarangayOfficialRepository::class);
    }


    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
