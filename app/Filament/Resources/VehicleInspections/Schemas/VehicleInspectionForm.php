<?php

namespace App\Filament\Resources\VehicleInspections\Schemas;

use App\Models\VehicleInspection;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VehicleInspectionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'plate_number')
                    ->required(),
                DatePicker::make('inspection_date')
                    ->required(),
                DatePicker::make('expiry_date')
                    ->required(),
                Select::make('status')
                    ->required()
                    ->default(VehicleInspection::STATUS_VALID)
                    ->options([
                        VehicleInspection::STATUS_VALID => 'Valid',
                        VehicleInspection::STATUS_EXPIRED => 'Expired',
                        VehicleInspection::STATUS_RENEWED => 'Renewed',
                    ]),
                FileUpload::make('certificate_file')
                    ->directory('vehicle-inspection-certificates')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->downloadable()
                    ->openable(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
