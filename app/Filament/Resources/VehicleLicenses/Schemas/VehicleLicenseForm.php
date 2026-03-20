<?php

namespace App\Filament\Resources\VehicleLicenses\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VehicleLicenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id')
                    ->required(),
                DatePicker::make('issued_date'),
                DatePicker::make('expiry_date')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('valid'),
                TextInput::make('receipt_file'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
