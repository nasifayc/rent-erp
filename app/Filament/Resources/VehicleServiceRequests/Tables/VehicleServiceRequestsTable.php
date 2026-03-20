<?php

namespace App\Filament\Resources\VehicleServiceRequests\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class VehicleServiceRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('vehicle.id')
                    ->label('Vehicle Plate')
                    ->formatStateUsing(fn ($record): string => $record->vehicle?->plate_number ?? 'N/A')
                    ->searchable(),
                TextColumn::make('requester.name')
                    ->label('Requested By')
                    ->searchable(),
                TextColumn::make('service_type')
                    ->searchable(),
                TextColumn::make('urgency_level')
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('service_provider')
                    ->searchable(),
                TextColumn::make('approver.name')
                    ->label('Approved By')
                    ->searchable(),
                TextColumn::make('approved_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('service_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('service_report_file')
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
                Action::make('approveRequest')
                    ->label('Approve')
                    ->visible(fn ($record): bool => $record->status === 'pending' && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FLEET_MANAGER,
                    ]))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => 'approved',
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);

                        Notification::make()->title('Service request approved')->success()->send();
                    }),
                Action::make('assignProvider')
                    ->label('Assign Provider')
                    ->visible(fn ($record): bool => in_array($record->status, ['approved', 'in_progress'], true) && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FLEET_MANAGER,
                    ]))
                    ->form([
                        TextInput::make('service_provider')->required(),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->update([
                            'service_provider' => $data['service_provider'],
                            'status' => 'in_progress',
                        ]);

                        Notification::make()->title('Provider assigned')->success()->send();
                    }),
                Action::make('markCompleted')
                    ->label('Complete')
                    ->visible(fn ($record): bool => in_array($record->status, ['approved', 'in_progress'], true) && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FLEET_MANAGER,
                    ]))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => 'completed',
                            'service_date' => now()->toDateString(),
                        ]);

                        Notification::make()->title('Service request completed')->success()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
