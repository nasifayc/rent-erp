<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('location')
                    ->required(),
                TextInput::make('proposed_office')
                    ->required(),
                TextInput::make('landlord_name')
                    ->required(),
                TextInput::make('landlord_phone')
                    ->tel(),
                TextInput::make('landlord_email')
                    ->email(),
                TextInput::make('estimated_rent')
                    ->required()
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('created_by')
                    ->numeric(),
            ]);
    }
}
