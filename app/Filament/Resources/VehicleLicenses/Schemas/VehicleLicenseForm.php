<?php

namespace App\Filament\Resources\VehicleLicenses\Schemas;

use App\Models\VehicleLicense;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class VehicleLicenseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'plate_number')
                    ->required(),
                DatePicker::make('issued_date'),
                DatePicker::make('expiry_date')
                    ->required(),
                Select::make('status')
                    ->required()
                    ->default(VehicleLicense::STATUS_VALID)
                    ->options([
                        VehicleLicense::STATUS_VALID => 'Valid',
                        VehicleLicense::STATUS_EXPIRED => 'Expired',
                        VehicleLicense::STATUS_RENEWED => 'Renewed',
                    ]),
                FileUpload::make('receipt_file')
                    ->directory('vehicle-bolo-receipts')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->downloadable()
                    ->openable(),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
