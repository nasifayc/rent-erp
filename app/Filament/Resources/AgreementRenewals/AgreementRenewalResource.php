<?php

namespace App\Filament\Resources\AgreementRenewals;

use App\Filament\Resources\AgreementRenewals\Pages\CreateAgreementRenewal;
use App\Filament\Resources\AgreementRenewals\Pages\EditAgreementRenewal;
use App\Filament\Resources\AgreementRenewals\Pages\ListAgreementRenewals;
use App\Filament\Resources\AgreementRenewals\Schemas\AgreementRenewalForm;
use App\Filament\Resources\AgreementRenewals\Tables\AgreementRenewalsTable;
use App\Models\AgreementRenewal;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class AgreementRenewalResource extends Resource
{
    protected static ?string $model = AgreementRenewal::class;

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
}
