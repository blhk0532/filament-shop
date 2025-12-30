<?php

namespace Adultdate\FilamentShop\Filament\Clusters\Services\Resources\Bookings\Pages;

use Adultdate\FilamentShop\Filament\Clusters\Services\Resources\Bookings\BookingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
