<?php

namespace App\Filament\Resources\AgreementRenewals\Tables;

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
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class AgreementRenewalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('officeRentAgreement.id')
                    ->label('Agreement')
                    ->formatStateUsing(fn ($record): string => $record->officeRentAgreement?->agreement_id ?? 'N/A')
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
                    ->badge()
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->searchable(),
                TextColumn::make('approver.name')
                    ->label('Approved By')
                    ->searchable(),
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
                ViewAction::make(),
                EditAction::make(),
                Action::make('approveDecision')
                    ->label('Approve Decision')
                    ->visible(fn ($record): bool => $record->status === AgreementRenewal::STATUS_PENDING && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FINANCE_OFFICER,
                    ]))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => AgreementRenewal::STATUS_APPROVED,
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);

                        $agreement = $record->officeRentAgreement;

                        if (! $agreement) {
                            return;
                        }

                        if ($record->decision === AgreementRenewal::DECISION_TERMINATE) {
                            $agreement->update([
                                'status' => OfficeRentAgreement::STATUS_TERMINATED,
                                'end_date' => now()->toDateString(),
                            ]);
                        } else {
                            $agreement->update([
                                'status' => OfficeRentAgreement::STATUS_ACTIVE,
                                'monthly_rent' => $record->new_rent,
                                'start_date' => $record->new_start_date,
                                'end_date' => $record->new_end_date,
                            ]);
                        }

                        Notification::make()->title('Renewal decision approved and applied')->success()->send();
                    }),
                Action::make('rejectDecision')
                    ->label('Reject Decision')
                    ->color('danger')
                    ->visible(fn ($record): bool => $record->status === AgreementRenewal::STATUS_PENDING && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_FINANCE_OFFICER,
                    ]))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => AgreementRenewal::STATUS_REJECTED,
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);

                        $record->officeRentAgreement?->update([
                            'status' => OfficeRentAgreement::STATUS_ACTIVE,
                        ]);

                        Notification::make()->title('Renewal decision rejected')->danger()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
