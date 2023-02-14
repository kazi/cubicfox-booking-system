<?php

namespace App\Providers;

use App\Filters\Api\V1\OffersFilter;
use App\Filters\Api\V1\ReservationsFilter;
use App\Models\Offer;
use App\Models\Reservation;
use App\Services\OfferService;
use App\Services\ReservationService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(OffersFilter::class, function ($app) {
            return new OffersFilter();
        });
        $this->app->singleton(OfferService::class, function ($app) {
            return new OfferService();
        });
        $this->app->singleton(ReservationService::class, function ($app) {
            return new ReservationService(
                new Reservation(),
                new Offer()
            );
        });
        $this->app->singleton(ReservationsFilter::class, function ($app) {
            return new ReservationsFilter();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
