<?php

namespace App\Filament\Resources\AgreementRenewals\Schemas;

use App\Models\AgreementRenewal;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class AgreementRenewalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('office_rent_agreement_id')
                    ->relationship('officeRentAgreement', 'agreement_id')
                    ->required(),
                TextInput::make('old_rent')
                    ->required()
                    ->numeric(),
                TextInput::make('new_rent')
                    ->required()
                    ->numeric(),
                DatePicker::make('old_end_date')
                    ->required(),
                DatePicker::make('new_start_date')
                    ->required(),
                DatePicker::make('new_end_date')
                    ->required(),
                Select::make('decision')
                    ->required()
                    ->default(AgreementRenewal::DECISION_RENEW)
                    ->options([
                        AgreementRenewal::DECISION_RENEW => 'Renew',
                        AgreementRenewal::DECISION_AMEND => 'Amend',
                        AgreementRenewal::DECISION_TERMINATE => 'Terminate',
                    ]),
                Select::make('status')
                    ->required()
                    ->default(AgreementRenewal::STATUS_PENDING)
                    ->options([
                        AgreementRenewal::STATUS_PENDING => 'Pending',
                        AgreementRenewal::STATUS_APPROVED => 'Approved',
                        AgreementRenewal::STATUS_REJECTED => 'Rejected',
                    ]),
                TextInput::make('approved_by')
                    ->numeric(),
                DatePicker::make('approved_at'),
                Textarea::make('notes')
                    ->columnSpanFull(),
            ]);
    }
}
