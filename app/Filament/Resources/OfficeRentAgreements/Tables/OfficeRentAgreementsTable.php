<?php

namespace App\Filament\Resources\OfficeRentAgreements\Tables;

use App\Models\User;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
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
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('sendForLegal')
                    ->label('Send For Legal')
                    ->visible(fn ($record): bool => $record->status === 'draft' && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_ADMIN_OFFICER,
                    ]))
                    ->action(function ($record): void {
                        $record->update(['status' => 'pending_legal']);

                        Notification::make()
                            ->title('Agreement moved to legal review')
                            ->success()
                            ->send();
                    }),
                Action::make('approveLegal')
                    ->label('Approve')
                    ->visible(fn ($record): bool => $record->status === 'pending_legal' && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_LEGAL_REVIEWER,
                    ]))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => 'active',
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Agreement approved and activated')
                            ->success()
                            ->send();
                    }),
                Action::make('rejectLegal')
                    ->label('Reject')
                    ->color('danger')
                    ->visible(fn ($record): bool => $record->status === 'pending_legal' && Auth::user()?->hasAnyRole([
                        User::ROLE_ADMIN,
                        User::ROLE_LEGAL_REVIEWER,
                    ]))
                    ->action(function ($record): void {
                        $record->update([
                            'status' => 'rejected',
                            'approved_by' => Auth::id(),
                            'approved_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Agreement rejected by legal')
                            ->danger()
                            ->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
