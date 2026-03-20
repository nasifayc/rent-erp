<?php

namespace App\Filament\Resources\VehicleMaintenanceRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehicleMaintenanceRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.id')
                    ->label('Vehicle Plate')
                    ->formatStateUsing(fn ($record): string => $record->vehicle?->plate_number ?? 'N/A')
                    ->searchable(),
                TextColumn::make('serviceRequest.id')
                    ->label('Service Request')
                    ->searchable(),
                TextColumn::make('mileage_at_service')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('service_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('service_type')
                    ->searchable(),
                TextColumn::make('provider')
                    ->searchable(),
                TextColumn::make('cost')
                    ->money()
                    ->sortable(),
                TextColumn::make('next_due_date')
                    ->date()
                    ->sortable()
                    ->badge(),
                TextColumn::make('next_due_mileage')
                    ->numeric()
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
