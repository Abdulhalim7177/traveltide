<?php

namespace App\Filament\Widgets;

use App\Models\Trip;
use App\Models\Booking;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Card;

class PriceOverview extends BaseWidget
{
    protected function getCards(): array
    {
        // Calculate the total revenue and average price of all trips
        $totalRevenue = Trip::sum('price');
        $averagePrice = Trip::avg('price');

        // Calculate total revenue from bookings (sum of each booking * trip price)
        $totalRevenueFromBookings = Booking::join('trips', 'bookings.trip_id', '=', 'trips.id')
            ->sum('trips.price'); // Sum the price of trips corresponding to bookings
        
        return [
            Card::make('Total Revenue', '₦' . number_format($totalRevenue, 2))
                ->description('Total revenue generated from all trips')
                ->icon('heroicon-o-currency-dollar')  // You can keep this or change the icon
                ->color('primary')
                ->extraAttributes(['class' => 'bg-indigo-100 border border-indigo-300 shadow-sm rounded-lg p-4']),

            Card::make('Average Price per Trip', '₦' . number_format($averagePrice, 2))
                ->description('The average price of all trips')
                ->icon('heroicon-o-chart-bar')
                ->color('success')
                ->extraAttributes(['class' => 'bg-teal-100 border border-teal-300 shadow-sm rounded-lg p-4']),

            Card::make('Total Revenue from Bookings', '₦' . number_format($totalRevenueFromBookings, 2))
                ->description('Revenue generated from all bookings')
                ->icon('heroicon-o-credit-card')
                ->color('warning')
                ->extraAttributes(['class' => 'bg-yellow-100 border border-yellow-300 shadow-sm rounded-lg p-4']),
        ];
    }
}
