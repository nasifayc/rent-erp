<?php

namespace App\Filament\Resources\VehicleLicenses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleLicensesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.id')
                    ->label('Vehicle Plate')
                    ->formatStateUsing(fn ($record): string => $record->vehicle?->plate_number ?? 'N/A')
                    ->searchable(),
                TextColumn::make('issued_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('expiry_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('days_remaining')
                    ->label('Days Remaining')
                    ->state(fn ($record): int => now()->startOfDay()->diffInDays($record->expiry_date, false))
                    ->badge()
                    ->color(fn (int $state): string => $state <= 7 ? 'danger' : ($state <= 30 ? 'warning' : 'success')),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('receipt_file')
                    ->searchable(),
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
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
