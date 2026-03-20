<?php

namespace App\Filament\Resources\OfficeRentPayments\Pages;

use App\Filament\Resources\OfficeRentPayments\OfficeRentPaymentResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOfficeRentPayments extends ListRecords
{
    protected static string $resource = OfficeRentPaymentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
