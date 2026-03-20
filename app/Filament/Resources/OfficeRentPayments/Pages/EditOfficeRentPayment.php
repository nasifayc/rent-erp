<?php

namespace App\Filament\Resources\OfficeRentPayments\Pages;

use App\Filament\Resources\OfficeRentPayments\OfficeRentPaymentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOfficeRentPayment extends EditRecord
{
    protected static string $resource = OfficeRentPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
