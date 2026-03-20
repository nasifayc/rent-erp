<?php

namespace App\Filament\Resources\VehicleServiceRequests\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VehicleServiceRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'id')
                    ->required(),
                TextInput::make('requested_by')
                    ->numeric(),
                TextInput::make('service_type')
                    ->required(),
                Textarea::make('problem_description')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('urgency_level')
                    ->required()
                    ->default('medium'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('service_provider'),
                TextInput::make('approved_by')
                    ->numeric(),
                DatePicker::make('approved_at'),
                DatePicker::make('service_date'),
                TextInput::make('service_report_file'),
            ]);
    }
}
