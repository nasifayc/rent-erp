<?php

namespace App\Filament\Resources\VehicleServiceRequests\Tables;

use App\Models\User;
use App\Models\VehicleMaintenanceRecord;
use App\Models\VehicleServiceRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
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
                ViewAction::make(),
                EditAction::make(),
                Action::make('approveRequest')
                    ->label('Approve')
                    ->visible(fn ($record): bool => $record->status === VehicleServiceRequest::STATUS_PENDING && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FLEET_MANAGER,
                    ]))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => VehicleServiceRequest::STATUS_APPROVED,
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);

                        Notification::make()->title('Service request approved')->success()->send();
                    }),
                Action::make('assignProvider')
                    ->label('Assign Provider')
                    ->visible(fn ($record): bool => in_array($record->status, [VehicleServiceRequest::STATUS_APPROVED, VehicleServiceRequest::STATUS_IN_PROGRESS], true) && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FLEET_MANAGER,
                    ]))
                    ->form([
                        TextInput::make('service_provider')->required(),
                    ])
                    ->action(function ($record, array $data): void {
                        $record->update([
                            'service_provider' => $data['service_provider'],
                            'status' => VehicleServiceRequest::STATUS_IN_PROGRESS,
                        ]);

                        Notification::make()->title('Provider assigned')->success()->send();
                    }),
                Action::make('markCompleted')
                    ->label('Complete')
                    ->visible(fn ($record): bool => in_array($record->status, [VehicleServiceRequest::STATUS_APPROVED, VehicleServiceRequest::STATUS_IN_PROGRESS], true) && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FLEET_MANAGER,
                    ]))
                    ->action(function ($record): void {
                        $serviceDate = now()->toDateString();
                        $vehicle = $record->vehicle;

                        $record->update([
                            'status' => VehicleServiceRequest::STATUS_COMPLETED,
                            'service_date' => $serviceDate,
                        ]);

                        if ($vehicle) {
                            VehicleMaintenanceRecord::query()->create([
                                'vehicle_id' => $vehicle->id,
                                'vehicle_service_request_id' => $record->id,
                                'mileage_at_service' => $vehicle->current_mileage,
                                'service_date' => $serviceDate,
                                'service_type' => $record->service_type,
                                'provider' => $record->service_provider,
                                'cost' => 0,
                                'notes' => $record->problem_description,
                                'next_due_date' => now()->addDays($vehicle->inspection_interval_days)->toDateString(),
                                'next_due_mileage' => $vehicle->current_mileage + $vehicle->service_interval_km,
                            ]);

                            $vehicle->update([
                                'last_service_date' => $serviceDate,
                            ]);
                        }

                        Notification::make()->title('Service request completed and maintenance history updated')->success()->send();
                    }),
                Action::make('rejectRequest')
                    ->label('Reject')
                    ->color('danger')
                    ->visible(fn ($record): bool => $record->status === VehicleServiceRequest::STATUS_PENDING && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FLEET_MANAGER,
                    ]))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => VehicleServiceRequest::STATUS_REJECTED,
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);

                        Notification::make()->title('Service request rejected')->danger()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
