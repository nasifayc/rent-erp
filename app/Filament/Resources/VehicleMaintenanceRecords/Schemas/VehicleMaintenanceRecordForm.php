<?php

namespace App\Filament\Resources\VehicleMaintenanceRecords\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VehicleMaintenanceRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id')
                    ->required(),
                TextInput::make('vehicle_service_request_id')
                    ->numeric(),
                TextInput::make('mileage_at_service')
                    ->required()
                    ->numeric(),
                DatePicker::make('service_date')
                    ->required(),
                TextInput::make('service_type')
                    ->required(),
                TextInput::make('provider'),
                TextInput::make('cost')
                    ->required()
                    ->numeric()
                    ->default(0)
                    ->prefix('$'),
                Textarea::make('notes')
                    ->columnSpanFull(),
                DatePicker::make('next_due_date'),
                TextInput::make('next_due_mileage')
                    ->numeric(),
            ]);
    }
}
