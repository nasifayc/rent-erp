<?php

namespace App\Filament\Resources\Vehicles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class VehiclesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')
                    ->searchable(),
                TextColumn::make('plate_number')
                    ->searchable(),
                TextColumn::make('registration_number')
                    ->searchable(),
                TextColumn::make('make')
                    ->searchable(),
                TextColumn::make('model')
                    ->searchable(),
                TextColumn::make('color')
                    ->searchable(),
                TextColumn::make('manufacture_year')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('current_mileage')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('km_until_service_due')
                    ->label('KM Until Service Due')
                    ->state(fn ($record): int => $record->kmUntilServiceDue())
                    ->badge()
                    ->color(fn (int $state): string => $state <= 0 ? 'danger' : ($state <= 500 ? 'warning' : 'success')),
                TextColumn::make('last_service_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('days_until_inspection_due')
                    ->label('Days Until Time-Based Check')
                    ->state(fn ($record): string => ($record->daysUntilInspectionDue() ?? '-') . '')
                    ->badge()
                    ->color(function ($state): string {
                        if ($state === '-') {
                            return 'gray';
                        }

                        $days = (int) $state;

                        return $days <= 0 ? 'danger' : ($days <= 30 ? 'warning' : 'success');
                    }),
                TextColumn::make('service_interval_km')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('inspection_interval_days')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('major_service_interval_days')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
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
