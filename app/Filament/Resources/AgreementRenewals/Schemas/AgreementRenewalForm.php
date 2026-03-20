<?php

namespace App\Filament\Resources\AgreementRenewals\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AgreementRenewalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('office_rent_agreement_id')
                    ->relationship('officeRentAgreement', 'id')
                    ->required(),
                TextInput::make('old_rent')
                    ->required()
                    ->numeric(),
                TextInput::make('new_rent')
                    ->required()
                    ->numeric(),
                DatePicker::make('old_end_date')
                    ->required(),
                DatePicker::make('new_start_date')
                    ->required(),
                DatePicker::make('new_end_date')
                    ->required(),
                TextInput::make('decision')
                    ->required()
                    ->default('renew'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('approved_by')
                    ->numeric(),
                DatePicker::make('approved_at'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
