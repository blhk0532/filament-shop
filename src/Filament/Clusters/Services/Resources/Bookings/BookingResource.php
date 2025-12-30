<?php

namespace Adultdate\FilamentShop\Filament\Clusters\Services\Resources\Bookings;

use Adultdate\FilamentShop\Filament\Clusters\Services\Resources\Bookings\Pages\CreateBooking;
use Adultdate\FilamentShop\Filament\Clusters\Services\Resources\Bookings\Pages\EditBooking;
use Adultdate\FilamentShop\Filament\Clusters\Services\Resources\Bookings\Pages\ListBookings;
use Adultdate\FilamentShop\Filament\Clusters\Services\Resources\Bookings\Schemas\BookingForm;
use Adultdate\FilamentShop\Filament\Clusters\Services\Resources\Bookings\Tables\BookingsTable;
use Adultdate\FilamentShop\Models\Shop\Booking;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use UnitEnum;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $slug = 'services/bookings';

    protected static ?string $recordTitleAttribute = 'number';

    protected static string | UnitEnum | null $navigationGroup = 'Services';

    protected static string | BackedEnum | null $navigationIcon = 'heroicon-o-calendar-days';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return BookingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BookingsTable::configure($table);
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        return static::calculateTotalPrice($data);
    }

    public static function mutateFormDataBeforeSave(array $data): array
    {
        return static::calculateTotalPrice($data);
    }

    protected static function calculateTotalPrice(array $data): array
    {
        $totalPrice = 0;

        if (isset($data['items']) && is_array($data['items'])) {
            foreach ($data['items'] as $item) {
                $qty = isset($item['qty']) ? (int) $item['qty'] : 0;
                $unit = isset($item['unit_price']) ? (float) $item['unit_price'] : 0;
                $totalPrice += $qty * $unit;
            }
        }

        $data['total_price'] = $totalPrice;

        if (empty($data['currency'])) {
            $data['currency'] = 'SEK';
        }

        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBookings::route('/'),
            'create' => CreateBooking::route('/create'),
            'edit' => EditBooking::route('/{record}/edit'),
        ];
    }

    /** @return Builder<Booking> */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScope(SoftDeletingScope::class);
    }
}
