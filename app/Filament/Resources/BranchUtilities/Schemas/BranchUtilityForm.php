<?php

namespace App\Filament\Resources\BranchUtilities\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BranchUtilityForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                TextInput::make('utility_type')
                    ->required(),
                TextInput::make('provider')
                    ->required(),
                TextInput::make('account_number')
                    ->required(),
                TextInput::make('payment_cycle')
                    ->required()
                    ->default('monthly'),
                DatePicker::make('next_due_date'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
            ]);
    }
}
