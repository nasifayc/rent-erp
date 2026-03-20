<?php

namespace App\Filament\Resources\OfficeRentAgreements\Pages;

use App\Filament\Resources\OfficeRentAgreements\OfficeRentAgreementResource;
use App\Models\OfficeRentAgreement;
use Filament\Resources\Pages\CreateRecord;

class CreateOfficeRentAgreement extends CreateRecord
{
    protected static string $resource = OfficeRentAgreementResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['status'] = OfficeRentAgreement::STATUS_DRAFT;

        return $data;
    }
}
