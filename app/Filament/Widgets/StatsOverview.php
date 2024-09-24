<?php

namespace App\Filament\Widgets;

use App\Models\Vehicle;
use App\Models\Trip;
use App\Models\User;
use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class StatsOverview extends BaseWidget
{
    protected function getCards(): array
    {
        return [
            Card::make('Total Vehicles', Vehicle::count())
                ->description('Current total vehicles in the system')
                ->icon('heroicon-o-truck')
                ->color('primary'),
            
            Card::make('Total Trips', Trip::count())
                ->description('Current total trips scheduled')
                ->icon('heroicon-o-calendar')
                ->color('success'),
            
            Card::make('Total Users', User::count())
                ->description('Users registered')
                ->icon('heroicon-o-user-group')
                ->color('info'),
            
            Card::make('Total Bookings', Booking::count())
                ->description('Total bookings made by users')
                ->icon('heroicon-o-ticket')
                ->color('warning'),
        ];
    }
}
