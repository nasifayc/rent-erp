<?php

namespace App\Filament\Resources\OfficeRentAgreements\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OfficeRentAgreementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                TextInput::make('agreement_id')
                    ->required(),
                TextInput::make('landlord_name')
                    ->required(),
                TextInput::make('property_address')
                    ->required(),
                TextInput::make('monthly_rent')
                    ->required()
                    ->numeric(),
                TextInput::make('payment_schedule')
                    ->required()
                    ->default('monthly'),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('draft'),
                TextInput::make('contract_file'),
                DatePicker::make('approved_at'),
                Textarea::make('review_notes')
                    ->columnSpanFull(),
                TextInput::make('approved_by')
                    ->numeric(),
            ]);
    }
}
