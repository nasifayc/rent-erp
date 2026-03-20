<?php

namespace App\Filament\Resources\VehicleInspections;

use App\Filament\Resources\VehicleInspections\Pages\CreateVehicleInspection;
use App\Filament\Resources\VehicleInspections\Pages\EditVehicleInspection;
use App\Filament\Resources\VehicleInspections\Pages\ListVehicleInspections;
use App\Filament\Resources\VehicleInspections\Schemas\VehicleInspectionForm;
use App\Filament\Resources\VehicleInspections\Tables\VehicleInspectionsTable;
use App\Models\User;
use App\Models\VehicleInspection;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VehicleInspectionResource extends Resource
{
    protected static ?string $model = VehicleInspection::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Vehicle Fleet Management';

    protected static ?string $navigationLabel = 'Inspections';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VehicleInspectionForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleInspectionsTable::configure($table);
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
            'index' => ListVehicleInspections::route('/'),
            'create' => CreateVehicleInspection::route('/create'),
            'edit' => EditVehicleInspection::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_ADMIN_OFFICER,
            User::ROLE_FLEET_MANAGER,
        ]) ?? false;
    }

    public static function canCreate(): bool
    {
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return static::canViewAny();
    }
}
