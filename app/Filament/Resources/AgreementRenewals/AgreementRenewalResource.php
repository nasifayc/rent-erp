<?php

namespace App\Filament\Resources\AgreementRenewals;

use App\Filament\Resources\AgreementRenewals\Pages\CreateAgreementRenewal;
use App\Filament\Resources\AgreementRenewals\Pages\EditAgreementRenewal;
use App\Filament\Resources\AgreementRenewals\Pages\ListAgreementRenewals;
use App\Filament\Resources\AgreementRenewals\Schemas\AgreementRenewalForm;
use App\Filament\Resources\AgreementRenewals\Tables\AgreementRenewalsTable;
use App\Models\AgreementRenewal;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class AgreementRenewalResource extends Resource
{
    protected static ?string $model = AgreementRenewal::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Office Rent Management';

    protected static ?string $navigationLabel = 'Renewals & Amendments';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return AgreementRenewalForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return AgreementRenewalsTable::configure($table);
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
            'index' => ListAgreementRenewals::route('/'),
            'create' => CreateAgreementRenewal::route('/create'),
            'edit' => EditAgreementRenewal::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_FINANCE_OFFICER,
            User::ROLE_LEGAL_REVIEWER,
        ]) ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_FINANCE_OFFICER,
        ]) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return static::canCreate();
    }
}
