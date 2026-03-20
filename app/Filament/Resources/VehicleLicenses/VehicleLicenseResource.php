<?php

namespace App\Filament\Resources\VehicleLicenses;

use App\Filament\Resources\VehicleLicenses\Pages\CreateVehicleLicense;
use App\Filament\Resources\VehicleLicenses\Pages\EditVehicleLicense;
use App\Filament\Resources\VehicleLicenses\Pages\ListVehicleLicenses;
use App\Filament\Resources\VehicleLicenses\Schemas\VehicleLicenseForm;
use App\Filament\Resources\VehicleLicenses\Tables\VehicleLicensesTable;
use App\Models\User;
use App\Models\VehicleLicense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VehicleLicenseResource extends Resource
{
    protected static ?string $model = VehicleLicense::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Vehicle Fleet Management';

    protected static ?string $navigationLabel = 'Bolo Licenses';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VehicleLicenseForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleLicensesTable::configure($table);
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
            'index' => ListVehicleLicenses::route('/'),
            'create' => CreateVehicleLicense::route('/create'),
            'edit' => EditVehicleLicense::route('/{record}/edit'),
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
