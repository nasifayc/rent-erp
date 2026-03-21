<?php

namespace App\Filament\Resources\OfficeRentAgreements\Tables;

use App\Models\AgreementRenewal;
use App\Models\OfficeRentAgreement;
use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class OfficeRentAgreementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('branch.name')
                    ->searchable(),
                TextColumn::make('agreement_id')
                    ->searchable(),
                TextColumn::make('landlord_name')
                    ->searchable(),
                TextColumn::make('property_address')
                    ->searchable(),
                TextColumn::make('monthly_rent')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('payment_schedule')
                    ->searchable(),
                TextColumn::make('start_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('end_date')
                    ->date()
                    ->sortable(),
                TextColumn::make('days_remaining')
                    ->label('Days Remaining')
                    ->state(fn ($record): int => $record->daysUntilExpiry())
                    ->badge()
                    ->color(fn (int $state): string => $state <= 30 ? 'danger' : ($state <= 60 ? 'warning' : 'success')),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('contract_file')
                    ->searchable(),
                TextColumn::make('approved_at')
                    ->date()
                    ->sortable(),
                TextColumn::make('approver.name')
                    ->label('Approved By')
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
                SelectFilter::make('status')
                    ->options([
                        OfficeRentAgreement::STATUS_DRAFT => 'Draft',
                        OfficeRentAgreement::STATUS_PENDING_LEGAL => 'Pending Legal',
                        OfficeRentAgreement::STATUS_ACTIVE => 'Active',
                        OfficeRentAgreement::STATUS_REJECTED => 'Rejected',
                        OfficeRentAgreement::STATUS_RENEWAL_PENDING => 'Renewal Pending',
                        OfficeRentAgreement::STATUS_TERMINATED => 'Terminated',
                    ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('sendForLegal')
                    ->label('Send For Legal')
                    ->visible(fn ($record): bool => $record->status === OfficeRentAgreement::STATUS_DRAFT && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_ADMIN_OFFICER,
                    ]))
                    ->action(function ($record): void {
                        $record->update(['status' => OfficeRentAgreement::STATUS_PENDING_LEGAL]);

                        Notification::make()
                            ->title('Agreement moved to legal review')
                            ->success()
                            ->send();
                    }),
                Action::make('approveLegal')
                    ->label('Approve')
                    ->visible(fn ($record): bool => $record->status === OfficeRentAgreement::STATUS_PENDING_LEGAL && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_LEGAL_REVIEWER,
                    ]))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => OfficeRentAgreement::STATUS_ACTIVE,
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);

                        $record->branch?->update([
                            'status' => 'active',
                        ]);

                        Notification::make()
                            ->title('Agreement approved and activated')
                            ->success()
                            ->send();
                    }),
                Action::make('rejectLegal')
                    ->label('Reject')
                    ->color('danger')
                    ->visible(fn ($record): bool => $record->status === OfficeRentAgreement::STATUS_PENDING_LEGAL && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_LEGAL_REVIEWER,
                    ]))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => OfficeRentAgreement::STATUS_REJECTED,
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Agreement rejected by legal')
                            ->danger()
                            ->send();
                    }),
                Action::make('renew')
                    ->label('Renew')
                    ->visible(fn ($record): bool => $record->requiresRenewalDecision() && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FINANCE_OFFICER,
                    ]))
                    ->action(function ($record): void {
                        $existingPending = AgreementRenewal::query()
                            ->where('office_rent_agreement_id', $record->id)
                            ->where('status', AgreementRenewal::STATUS_PENDING)
                            ->exists();

                        if ($existingPending) {
                            Notification::make()->title('A pending renewal decision already exists')->warning()->send();

                            return;
                        }

                        AgreementRenewal::query()->create([
                            'office_rent_agreement_id' => $record->id,
                            'old_rent' => $record->monthly_rent,
                            'new_rent' => $record->monthly_rent,
                            'old_end_date' => $record->end_date,
                            'new_start_date' => $record->end_date->copy()->addDay(),
                            'new_end_date' => $record->end_date->copy()->addYear(),
                            'decision' => AgreementRenewal::DECISION_RENEW,
                            'status' => AgreementRenewal::STATUS_PENDING,
                            'notes' => 'System-generated renewal request.',
                        ]);

                        $record->update(['status' => OfficeRentAgreement::STATUS_RENEWAL_PENDING]);

                        Notification::make()->title('Renewal request created')->success()->send();
                    }),
                Action::make('amend')
                    ->label('Amend')
                    ->visible(fn ($record): bool => $record->requiresRenewalDecision() && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FINANCE_OFFICER,
                    ]))
                    ->action(function ($record): void {
                        $existingPending = AgreementRenewal::query()
                            ->where('office_rent_agreement_id', $record->id)
                            ->where('status', AgreementRenewal::STATUS_PENDING)
                            ->exists();

                        if ($existingPending) {
                            Notification::make()->title('A pending renewal decision already exists')->warning()->send();

                            return;
                        }

                        AgreementRenewal::query()->create([
                            'office_rent_agreement_id' => $record->id,
                            'old_rent' => $record->monthly_rent,
                            'new_rent' => $record->monthly_rent,
                            'old_end_date' => $record->end_date,
                            'new_start_date' => $record->end_date->copy()->addDay(),
                            'new_end_date' => $record->end_date->copy()->addYear(),
                            'decision' => AgreementRenewal::DECISION_AMEND,
                            'status' => AgreementRenewal::STATUS_PENDING,
                            'notes' => 'System-generated amendment request.',
                        ]);

                        $record->update(['status' => OfficeRentAgreement::STATUS_RENEWAL_PENDING]);

                        Notification::make()->title('Amendment request created')->success()->send();
                    }),
                Action::make('terminate')
                    ->label('Terminate')
                    ->color('danger')
                    ->visible(fn ($record): bool => $record->requiresRenewalDecision() && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FINANCE_OFFICER,
                    ]))
                    ->action(function ($record): void {
                        $existingPending = AgreementRenewal::query()
                            ->where('office_rent_agreement_id', $record->id)
                            ->where('status', AgreementRenewal::STATUS_PENDING)
                            ->exists();

                        if ($existingPending) {
                            Notification::make()->title('A pending renewal decision already exists')->warning()->send();

                            return;
                        }

                        AgreementRenewal::query()->create([
                            'office_rent_agreement_id' => $record->id,
                            'old_rent' => $record->monthly_rent,
                            'new_rent' => $record->monthly_rent,
                            'old_end_date' => $record->end_date,
                            'new_start_date' => now()->toDateString(),
                            'new_end_date' => now()->toDateString(),
                            'decision' => AgreementRenewal::DECISION_TERMINATE,
                            'status' => AgreementRenewal::STATUS_PENDING,
                            'notes' => 'System-generated termination request.',
                        ]);

                        $record->update(['status' => OfficeRentAgreement::STATUS_RENEWAL_PENDING]);

                        Notification::make()->title('Termination request created')->success()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
