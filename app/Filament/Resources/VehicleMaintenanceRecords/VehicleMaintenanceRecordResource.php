<?php

namespace App\Filament\Resources\VehicleMaintenanceRecords;

use App\Filament\Resources\VehicleMaintenanceRecords\Pages\CreateVehicleMaintenanceRecord;
use App\Filament\Resources\VehicleMaintenanceRecords\Pages\EditVehicleMaintenanceRecord;
use App\Filament\Resources\VehicleMaintenanceRecords\Pages\ListVehicleMaintenanceRecords;
use App\Filament\Resources\VehicleMaintenanceRecords\Schemas\VehicleMaintenanceRecordForm;
use App\Filament\Resources\VehicleMaintenanceRecords\Tables\VehicleMaintenanceRecordsTable;
use App\Models\VehicleMaintenanceRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class VehicleMaintenanceRecordResource extends Resource
{
    protected static ?string $model = VehicleMaintenanceRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VehicleMaintenanceRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleMaintenanceRecordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListVehicleMaintenanceRecords::route('/'),
            'create' => CreateVehicleMaintenanceRecord::route('/create'),
            'edit' => EditVehicleMaintenanceRecord::route('/{record}/edit'),
        ];
    }
}
