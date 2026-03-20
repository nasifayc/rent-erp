<?php

namespace App\Filament\Resources\BranchUtilities;

use App\Filament\Resources\BranchUtilities\Pages\CreateBranchUtility;
use App\Filament\Resources\BranchUtilities\Pages\EditBranchUtility;
use App\Filament\Resources\BranchUtilities\Pages\ListBranchUtilities;
use App\Filament\Resources\BranchUtilities\Schemas\BranchUtilityForm;
use App\Filament\Resources\BranchUtilities\Tables\BranchUtilitiesTable;
use App\Models\BranchUtility;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BranchUtilityResource extends Resource
{
    protected static ?string $model = BranchUtility::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return BranchUtilityForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BranchUtilitiesTable::configure($table);
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
            'index' => ListBranchUtilities::route('/'),
            'create' => CreateBranchUtility::route('/create'),
            'edit' => EditBranchUtility::route('/{record}/edit'),
        ];
    }
}
