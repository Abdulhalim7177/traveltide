<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;

class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function afterCreate(): void
    {
        // Assign the default 'user' role if no other roles are selected
        if ($this->record->roles()->count() === 0) {
            $this->record->assignRole('user');
        }
    }
}
