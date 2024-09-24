<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Models\Booking;
use App\Models\Trip;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Database\Eloquent\Builder;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->required()
                    ->relationship('user', 'name')
                    ->searchable()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $user = User::find($state);

                        if ($user) {
                            $formUniqueIdentifier = $get('unique_identifier');
                            if ($formUniqueIdentifier && $formUniqueIdentifier !== $user->unique_identifier) {
                                FilamentNotification::make()
                                    ->title('Unique Identifier Mismatch')
                                    ->warning()
                                    ->body('The unique identifier does not match the selected user.')
                                    ->send();

                                $set('unique_identifier', null);
                            }
                        }
                    }),
                Forms\Components\Select::make('trip_id')
                    ->required()
                    ->relationship('trip', 'name')
                    ->searchable()
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $trip = Trip::find($state);

                        if ($trip) {
                            $maxSeatNumber = Booking::where('trip_id', $trip->id)->max('seat_number') ?? 0;
                            $set('seat_number', $maxSeatNumber + 1);

                            $existingBookings = Booking::where('trip_id', $trip->id)->count();
                            if ($existingBookings >= $trip->vehicle->capacity) {
                                FilamentNotification::make()
                                    ->title('Booking Limit Exceeded')
                                    ->danger()
                                    ->body('The booking limit for this trip has been reached.')
                                    ->send();

                                $set('trip_id', null);
                            }
                        }
                    }),
                Forms\Components\TextInput::make('seat_number')
                    ->required()
                    ->numeric()
                    ->disabled(),
                Forms\Components\TextInput::make('unique_identifier')
                    ->label('Unique Identifier for Validation')
                    ->required()
                    ->maxLength(7)
                    ->dehydrated(false)
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        $userId = $get('user_id');

                        $user = User::find($userId);
                        if ($user && $user->unique_identifier !== $state) {
                            FilamentNotification::make()
                                ->title('Unique Identifier Error')
                                ->danger()
                                ->body('The unique identifier does not match the selected user.')
                                ->send();

                            $set('unique_identifier', null);
                        }
                    }),
                Forms\Components\View::make('proceed-to-payment')
                    ->view('filament.forms.proceed-to-payment')
                    ->label('Proceed to Payment'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id'),
                Tables\Columns\TextColumn::make('user.name'),
                Tables\Columns\TextColumn::make('trip.name'),
                Tables\Columns\TextColumn::make('seat_number'),
                Tables\Columns\TextColumn::make('trip.vehicle.capacity')
                    ->label('Vehicle Capacity'),
                Tables\Columns\TextColumn::make('trip.price')
                    ->label('Price'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('trip_id')
                    ->label('Trip')
                    ->relationship('trip', 'name')
                    ->options(Trip::pluck('name', 'id')->toArray())
                    ->placeholder('All Trips')
                    ->default(static::getLastBookedTripId())
                    ->query(function (Builder $query, $state) {
                        if ($state) {
                            return $query->where('trip_id', $state);
                        }
                        return $query; // Return all bookings if no trip is selected
                    }),
                Tables\Filters\SelectFilter::make('show_all')
                    ->label('Show All Bookings')
                    ->hidden(fn () => !Filament::auth()->user()->isAdmin()) // Show only for admins
                    ->options([
                        'yes' => 'Activate All Filters',
                    ])
                    ->query(function (Builder $query, $state) {
                        if ($state === 'yes') {
                            // This will return all bookings, effectively selecting all filters
                            return $query->whereIn('trip_id', Trip::pluck('id')); // Include all trips
                        }
                        return $query; // Default behavior
                    }),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getLastBookedTripId()
    {
        return Booking::latest('id')->first()->trip_id ?? null;
    }

    public static function canEdit($model): bool
    {
        return Filament::auth()->user()->isAdmin(); // Only admins can edit trips
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
        ];
    }
}
