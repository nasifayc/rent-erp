<?php

namespace App\Filament\Resources\AgreementRenewals\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class AgreementRenewalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('officeRentAgreement.id')
                    ->searchable(),
                TextColumn::make('old_rent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('new_rent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('old_end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('new_start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('new_end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('decision')
                    ->searchable(),
                TextColumn::make('status')
                    ->searchable(),
                TextColumn::make('approved_by')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('approved_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
