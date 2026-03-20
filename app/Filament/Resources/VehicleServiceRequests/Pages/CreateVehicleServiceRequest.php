<?php

namespace App\Filament\Resources\VehicleServiceRequests\Pages;

use App\Filament\Resources\VehicleServiceRequests\VehicleServiceRequestResource;
use App\Models\VehicleServiceRequest;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateVehicleServiceRequest extends CreateRecord
{
    protected static string $resource = VehicleServiceRequestResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['requested_by'] = Auth::id();
        $data['status'] = VehicleServiceRequest::STATUS_PENDING;

        return $data;
    }
}
