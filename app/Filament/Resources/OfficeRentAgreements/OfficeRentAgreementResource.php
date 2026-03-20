<?php

namespace App\Filament\Resources\OfficeRentAgreements;

use App\Filament\Resources\OfficeRentAgreements\Pages\CreateOfficeRentAgreement;
use App\Filament\Resources\OfficeRentAgreements\Pages\EditOfficeRentAgreement;
use App\Filament\Resources\OfficeRentAgreements\Pages\ListOfficeRentAgreements;
use App\Filament\Resources\OfficeRentAgreements\Schemas\OfficeRentAgreementForm;
use App\Filament\Resources\OfficeRentAgreements\Tables\OfficeRentAgreementsTable;
use App\Models\OfficeRentAgreement;
use App\Models\User;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class OfficeRentAgreementResource extends Resource
{
    protected static ?string $model = OfficeRentAgreement::class;

    protected static string|\UnitEnum|null $navigationGroup = 'Office Rent Management';

    protected static ?string $navigationLabel = 'Rent Agreements';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return OfficeRentAgreementForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return OfficeRentAgreementsTable::configure($table);
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
            'index' => ListOfficeRentAgreements::route('/'),
            'create' => CreateOfficeRentAgreement::route('/create'),
            'edit' => EditOfficeRentAgreement::route('/{record}/edit'),
        ];
    }

    public static function canViewAny(): bool
    {
        return Auth::user()?->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_ADMIN_OFFICER,
            User::ROLE_LEGAL_REVIEWER,
            User::ROLE_FINANCE_OFFICER,
        ]) ?? false;
    }

    public static function canCreate(): bool
    {
        return Auth::user()?->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_ADMIN_OFFICER,
        ]) ?? false;
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()?->hasAnyRole([
            User::ROLE_ADMIN,
            User::ROLE_ADMIN_OFFICER,
            User::ROLE_LEGAL_REVIEWER,
        ]) ?? false;
    }
}
