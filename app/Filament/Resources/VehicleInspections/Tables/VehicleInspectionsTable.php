<?php

namespace App\Filament\Resources\VehicleInspections\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleInspectionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.id')
                    ->label('Vehicle Plate')
                    ->formatStateUsing(fn ($record): string => $record->vehicle?->plate_number ?? 'N/A')
                    ->searchable(),
                TextColumn::make('inspection_date')
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
                TextColumn::make('certificate_file')
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
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
