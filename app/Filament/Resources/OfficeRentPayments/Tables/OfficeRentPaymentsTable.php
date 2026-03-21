<?php

namespace App\Filament\Resources\OfficeRentPayments\Tables;

use App\Models\OfficeRentPayment;
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

class OfficeRentPaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('agreement.agreement_id')->label('Agreement')->searchable(),
                TextColumn::make('agreement.branch.name')->label('Branch')->searchable(),
                TextColumn::make('billing_period_start')->date()->sortable(),
                TextColumn::make('billing_period_end')->date()->sortable(),
                TextColumn::make('due_date')->date()->sortable(),
                TextColumn::make('amount_due')->numeric()->sortable(),
                TextColumn::make('amount_paid')->numeric()->sortable(),
                TextColumn::make('status')->badge()->searchable(),
                TextColumn::make('recorder.name')->label('Recorded By')->searchable(),
            ])
            ->filters([
                SelectFilter::make('status')->options([
                    OfficeRentPayment::STATUS_PENDING => 'Pending',
                    OfficeRentPayment::STATUS_PAID => 'Paid',
                ]),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                Action::make('markPaid')
                    ->label('Mark Paid')
                    ->visible(fn ($record): bool => $record->status !== OfficeRentPayment::STATUS_PAID)
                    ->action(function ($record): void {
                        $record->update([
                            'status' => OfficeRentPayment::STATUS_PAID,
                            'amount_paid' => $record->amount_due,
                            'paid_at' => now()->toDateString(),
                            'recorded_by' => Auth::id(),
                        ]);

                        Notification::make()->title('Rent payment marked as paid')->success()->send();
                    }),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
