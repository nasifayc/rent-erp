<?php

namespace App\Filament\Resources\VehicleMaintenanceRecords;

use App\Filament\Resources\VehicleMaintenanceRecords\Pages\CreateVehicleMaintenanceRecord;
use App\Filament\Resources\VehicleMaintenanceRecords\Pages\EditVehicleMaintenanceRecord;
use App\Filament\Resources\VehicleMaintenanceRecords\Pages\ListVehicleMaintenanceRecords;
use App\Filament\Resources\VehicleMaintenanceRecords\Schemas\VehicleMaintenanceRecordForm;
use App\Filament\Resources\VehicleMaintenanceRecords\Tables\VehicleMaintenanceRecordsTable;
use App\Models\User;
use App\Models\VehicleMaintenanceRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VehicleMaintenanceRecordResource extends Resource
{
    protected static ?string $model = VehicleMaintenanceRecord::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Vehicle Fleet Management';

    protected static ?string $navigationLabel = 'Maintenance History';

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

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_FLEET_MANAGER,
            User::ROLE_DRIVER,
            User::ROLE_DEPARTMENT_MANAGER,
        ]) ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_FLEET_MANAGER,
        ]) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return static::canCreate();
    }
}
