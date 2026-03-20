<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

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
                Select::make('status')
                    ->required()
                    ->default('pending')
                    ->options([
                        'pending' => 'Pending Request',
                        'agreement_preparation' => 'Agreement Preparation',
                        'active' => 'Active Branch',
                        'closed' => 'Closed',
                    ]),
                Hidden::make('created_by')
                    ->default(fn () => Auth::id()),
            ]);
    }
}
