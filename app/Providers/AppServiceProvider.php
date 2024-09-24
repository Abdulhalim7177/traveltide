<?php

namespace App\Providers;

use App\Filament\Pages\Auth\CustomLogin;
use App\Filament\Resources\AdminResource\Widgets\StatsOverviewWidget as WidgetsStatsOverviewWidget;
use App\Filament\Resources\UserResource;
use App\Filament\Widgets\PriceOverview;
use App\Filament\Widgets\StatsOverview;
use App\Filament\Widgets\StatsOverviewWidget;
use Filament\Facades\Filament;
use Filament\Widgets\Widget;
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
     */
 /*   public function boot()
    {   
        Filament::registerResources([
            UserResource::class, // Register UserResource for the user panel
        ], 'user'); // Specify the panel name here ('user')

        Filament::registerWidgets([
            PriceOverview::class,
        ]);

        Filament::registerWidgets([
            StatsOverview::class,
        ]);
   
    }
*/

}
