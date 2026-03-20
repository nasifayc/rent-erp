<?php

namespace App\Filament\Resources\UtilityPayments;

use App\Filament\Resources\UtilityPayments\Pages\CreateUtilityPayment;
use App\Filament\Resources\UtilityPayments\Pages\EditUtilityPayment;
use App\Filament\Resources\UtilityPayments\Pages\ListUtilityPayments;
use App\Filament\Resources\UtilityPayments\Schemas\UtilityPaymentForm;
use App\Filament\Resources\UtilityPayments\Tables\UtilityPaymentsTable;
use App\Models\User;
use App\Models\UtilityPayment;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class UtilityPaymentResource extends Resource
{
    protected static ?string $model = UtilityPayment::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Utility Management';

    protected static ?string $navigationLabel = 'Utility Payments';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return UtilityPaymentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return UtilityPaymentsTable::configure($table);
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
            'index' => ListUtilityPayments::route('/'),
            'create' => CreateUtilityPayment::route('/create'),
            'edit' => EditUtilityPayment::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_FINANCE_OFFICER,
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
