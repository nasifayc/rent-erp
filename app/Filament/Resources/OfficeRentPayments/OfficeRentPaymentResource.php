<?php

namespace App\Filament\Resources\OfficeRentPayments;

use App\Filament\Resources\OfficeRentPayments\Pages\CreateOfficeRentPayment;
use App\Filament\Resources\OfficeRentPayments\Pages\EditOfficeRentPayment;
use App\Filament\Resources\OfficeRentPayments\Pages\ListOfficeRentPayments;
use App\Filament\Resources\OfficeRentPayments\Schemas\OfficeRentPaymentForm;
use App\Filament\Resources\OfficeRentPayments\Tables\OfficeRentPaymentsTable;
use App\Models\OfficeRentPayment;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OfficeRentPaymentResource extends Resource
{
    protected static ?string $model = OfficeRentPayment::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Office Rent Management';

    protected static ?string $navigationLabel = 'Rent Payments';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;

    public static function form(Schema $schema): Schema
    {
        return OfficeRentPaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OfficeRentPaymentsTable::configure($table);
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
            'index' => ListOfficeRentPayments::route('/'),
            'create' => CreateOfficeRentPayment::route('/create'),
            'edit' => EditOfficeRentPayment::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_FINANCE_OFFICER,
            User::ROLE_ADMIN_OFFICER,
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
