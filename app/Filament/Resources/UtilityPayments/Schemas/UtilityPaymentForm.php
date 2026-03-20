<?php

namespace App\Filament\Resources\UtilityPayments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class UtilityPaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('branch_utility_id')
                    ->required()
                    ->numeric(),
                DatePicker::make('billing_month')
                    ->required(),
                DatePicker::make('due_date')
                    ->required(),
                TextInput::make('amount_due')
                    ->required()
                    ->numeric(),
                TextInput::make('amount_paid')
                    ->required()
                    ->numeric()
                    ->default(0),
                DatePicker::make('paid_at'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('recorded_by')
                    ->numeric(),
                TextInput::make('receipt_file'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
