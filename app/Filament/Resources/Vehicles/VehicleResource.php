<?php

namespace App\Filament\Resources\Vehicles;

use App\Filament\Resources\Vehicles\Pages\CreateVehicle;
use App\Filament\Resources\Vehicles\Pages\EditVehicle;
use App\Filament\Resources\Vehicles\Pages\ListVehicles;
use App\Filament\Resources\Vehicles\Schemas\VehicleForm;
use App\Filament\Resources\Vehicles\Tables\VehiclesTable;
use App\Models\User;
use App\Models\Vehicle;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Vehicle Fleet Management';

    protected static ?string $navigationLabel = 'Vehicles';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VehicleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehiclesTable::configure($table);
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
            'index' => ListVehicles::route('/'),
            'create' => CreateVehicle::route('/create'),
            'edit' => EditVehicle::route('/{record}/edit'),
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
