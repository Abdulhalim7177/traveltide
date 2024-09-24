<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Facades\Filament;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    public static function canView(?\Illuminate\Database\Eloquent\Model $record = null): bool
    {
        return Filament::auth()->user()->isAdmin(); // Only admins can view this resource
    }

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->required()
                    ->email()
                    ->maxLength(255),
                Forms\Components\TextInput::make('phone_number')
                    ->required()
                    ->maxLength(15),
                Forms\Components\TextInput::make('password')
                    ->required()
                    ->password()
                    ->minLength(8)
                    ->maxLength(255)
                    ->dehydrateStateUsing(fn($state) => Hash::make($state)), // Hashing password before saving
                Forms\Components\Select::make('roles')
                    ->label('Role')
                    ->multiple()  // Use multiple if you support assigning multiple roles
                    ->options(Role::all()->pluck('name', 'id'))
                    ->default(Role::where('name', 'user')->first()->id)  // Default to 'user'
                    ->required()
                    ->afterStateHydrated(function ($component, $record) {
                        if ($record) {
                            $roleIds = $record->roles->pluck('id')->toArray();
                            $component->state($roleIds);
                        }
                    }),
            ]);
    }
    

    public static function afterSave(User $user, array $data): void
    {
        if (isset($data['roles'])) {
          //  \Log::info('Roles to sync:', ['roles' => $data['roles']]);
            $user->syncRoles($data['roles']);  // Sync roles after saving
        }
    }
    

public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('id'),
            Tables\Columns\TextColumn::make('unique_identifier'),
            Tables\Columns\TextColumn::make('name'),
            Tables\Columns\TextColumn::make('email'),
            Tables\Columns\TextColumn::make('phone_number'),
            Tables\Columns\TextColumn::make('roles.name')
                ->label('Role')
                // ->formatStateUsing(function ($state) {
                //     // Ensure $state is an array before imploding
                //     if (is_array($state)) {
                //         return implode(', ', $state);
                //     }

                //     // Return 'No Role Assigned' if no roles
                //     return 'No Role Assigned';
                // }),
        ])
        ->filters([])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ])
        ->bulkActions([
            Tables\Actions\BulkActionGroup::make([
                Tables\Actions\DeleteBulkAction::make(),
            ]),
        ]);
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
