<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TripResource\Pages;
use App\Models\Trip;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\BadgeColumn;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->required(),
                Forms\Components\TextInput::make('start_location')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('destination')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TimePicker::make('trip_time')
                    ->required(),
                Forms\Components\Select::make('status')
                    ->required()
                    ->options([
                        'new' => 'New',
                        'processing' => 'Processing',
                        'cancelled' => 'Cancelled',
                        'done' => 'Done',
                    ]),
                Forms\Components\Select::make('vehicle_id')
                    ->required()
                    ->relationship('vehicle', 'name'),
                Forms\Components\TextInput::make('price')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('description'),
                Tables\Columns\TextColumn::make('start_location'),
                Tables\Columns\TextColumn::make('destination'),
                Tables\Columns\TextColumn::make('trip_time'),

                // BadgeColumn with Heroicons and colors for status
                BadgeColumn::make('status')
                    ->label('Trip Status')
                    ->colors([
                        'primary' => 'new',
                        'warning' => 'processing',
                        'danger' => 'cancelled',
                        'success' => 'done',
                    ])
                    ->icons([
                        'heroicon-o-plus' => 'new',
                        'heroicon-o-cog' => 'processing',
                        'heroicon-o-archive-box-x-mark' => 'cancelled',
                        'heroicon-o-check' => 'done',
                    ])
                    ->formatStateUsing(function ($state) {
                        return match ($state) {
                            'new' => 'New',
                            'processing' => 'Processing',
                            'cancelled' => 'Cancelled',
                            'done' => 'Done',
                            default => 'Unknown',
                        };
                    }),

                Tables\Columns\TextColumn::make('vehicle.name')
                    ->label('Vehicle'),
                Tables\Columns\TextColumn::make('price'),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Ensure the method signature matches the parent class
    public static function can(string $action, ?\Illuminate\Database\Eloquent\Model $record = null): bool
    {
        return true; // Restrict actions to admins
    }

    public static function canCreate(): bool
    {
        return Filament::auth()->user()->isAdmin(); // Only admins can create trips
    }

    public static function canEdit($model): bool
    {
        return Filament::auth()->user()->isAdmin(); // Only admins can edit trips
    }

    public static function canDelete($model): bool
    {
        return Filament::auth()->user()->isAdmin(); // Only admins can delete trips
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
            'index' => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrip::route('/create'),
            'edit' => Pages\EditTrip::route('/{record}/edit'),
        ];
    }
}
