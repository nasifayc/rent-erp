<?php

namespace App\Filament\Resources\Vehicles\Schemas;

use App\Models\Vehicle;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class VehicleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name'),
                TextInput::make('plate_number')
                    ->required(),
                TextInput::make('registration_number')
                    ->required(),
                TextInput::make('make')
                    ->required(),
                TextInput::make('model')
                    ->required(),
                TextInput::make('color'),
                TextInput::make('manufacture_year')
                    ->numeric(),
                TextInput::make('current_mileage')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('last_service_date'),
                TextInput::make('service_interval_km')
                    ->required()
                    ->numeric()
                    ->default(5000),
                TextInput::make('inspection_interval_days')
                    ->required()
                    ->numeric()
                    ->default(90),
                TextInput::make('major_service_interval_days')
                    ->required()
                    ->numeric()
                    ->default(180),
                Select::make('status')
                    ->required()
                    ->default(Vehicle::STATUS_ACTIVE)
                    ->options([
                        Vehicle::STATUS_ACTIVE => 'Active',
                        Vehicle::STATUS_MAINTENANCE => 'Under Maintenance',
                        Vehicle::STATUS_INACTIVE => 'Inactive',
                    ]),
            ]);
    }
}
