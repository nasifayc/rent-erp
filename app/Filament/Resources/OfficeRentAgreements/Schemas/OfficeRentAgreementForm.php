<?php

namespace App\Filament\Resources\OfficeRentAgreements\Schemas;

use App\Models\OfficeRentAgreement;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Schema;

class OfficeRentAgreementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('branch_id')
                    ->relationship('branch', 'name')
                    ->required(),
                TextInput::make('agreement_id')
                    ->required(),
                TextInput::make('landlord_name')
                    ->required(),
                TextInput::make('property_address')
                    ->required(),
                TextInput::make('monthly_rent')
                    ->required()
                    ->numeric(),
                Select::make('payment_schedule')
                    ->required()
                    ->default('monthly')
                    ->options([
                        'monthly' => 'Monthly',
                        'quarterly' => 'Quarterly',
                        'semi_annual' => 'Semi Annual',
                        'annual' => 'Annual',
                    ]),
                DatePicker::make('start_date')
                    ->required(),
                DatePicker::make('end_date')
                    ->required(),
                Select::make('status')
                    ->required()
                    ->default(OfficeRentAgreement::STATUS_DRAFT)
                    ->options([
                        OfficeRentAgreement::STATUS_DRAFT => 'Draft',
                        OfficeRentAgreement::STATUS_PENDING_LEGAL => 'Pending Legal',
                        OfficeRentAgreement::STATUS_ACTIVE => 'Active',
                        OfficeRentAgreement::STATUS_REJECTED => 'Rejected',
                        OfficeRentAgreement::STATUS_RENEWAL_PENDING => 'Renewal Pending',
                        OfficeRentAgreement::STATUS_TERMINATED => 'Terminated',
                    ]),
                FileUpload::make('contract_file')
                    ->directory('office-rent-contracts')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->downloadable()
                    ->openable(),
                DatePicker::make('approved_at')
                    ->visible(fn (): bool => Auth::user()?->hasAnyRole(['admin', 'legal_reviewer'])),
                Textarea::make('review_notes')
                    ->columnSpanFull(),
                TextInput::make('approved_by')
                    ->numeric()
                    ->hidden(),
            ]);
    }
}
