<?php

namespace App\Filament\Resources\OfficeRentPayments\Schemas;

use App\Models\OfficeRentPayment;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class OfficeRentPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('office_rent_agreement_id')
                    ->relationship('agreement', 'agreement_id')
                    ->required(),
                DatePicker::make('billing_period_start')->required(),
                DatePicker::make('billing_period_end')->required(),
                DatePicker::make('due_date')->required(),
                TextInput::make('amount_due')->required()->numeric(),
                TextInput::make('amount_paid')->required()->numeric()->default(0),
                Select::make('status')
                    ->required()
                    ->default(OfficeRentPayment::STATUS_PENDING)
                    ->options([
                        OfficeRentPayment::STATUS_PENDING => 'Pending',
                        OfficeRentPayment::STATUS_PAID => 'Paid',
                    ]),
                DatePicker::make('paid_at'),
                Textarea::make('notes')->columnSpanFull(),
            ]);
    }
}
