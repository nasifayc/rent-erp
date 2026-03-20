<?php

namespace App\Filament\Resources\VehicleServiceRequests;

use App\Filament\Resources\VehicleServiceRequests\Pages\CreateVehicleServiceRequest;
use App\Filament\Resources\VehicleServiceRequests\Pages\EditVehicleServiceRequest;
use App\Filament\Resources\VehicleServiceRequests\Pages\ListVehicleServiceRequests;
use App\Filament\Resources\VehicleServiceRequests\Schemas\VehicleServiceRequestForm;
use App\Filament\Resources\VehicleServiceRequests\Tables\VehicleServiceRequestsTable;
use App\Models\User;
use App\Models\VehicleServiceRequest;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class VehicleServiceRequestResource extends Resource
{
    protected static ?string $model = VehicleServiceRequest::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Vehicle Fleet Management';

    protected static ?string $navigationLabel = 'Service Requests';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return VehicleServiceRequestForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return VehicleServiceRequestsTable::configure($table);
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
            'index' => ListVehicleServiceRequests::route('/'),
            'create' => CreateVehicleServiceRequest::route('/create'),
            'edit' => EditVehicleServiceRequest::route('/{record}/edit'),
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
        return static::canViewAny();
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()?->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_FLEET_MANAGER,
        ]) ?? false;
    }
}
