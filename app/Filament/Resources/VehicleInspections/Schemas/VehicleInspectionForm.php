<?php

namespace App\Filament\Resources\VehicleInspections\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VehicleInspectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id')
                    ->required(),
                DatePicker::make('inspection_date')
                    ->required(),
                DatePicker::make('expiry_date')
                    ->required(),
                TextInput::make('status')
                    ->required()
                    ->default('valid'),
                TextInput::make('certificate_file'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
