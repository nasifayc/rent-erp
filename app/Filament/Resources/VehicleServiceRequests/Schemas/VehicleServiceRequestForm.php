<?php

namespace App\Filament\Resources\VehicleServiceRequests\Schemas;

use App\Models\VehicleServiceRequest;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;

class VehicleServiceRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('vehicle_id')
                    ->relationship('vehicle', 'plate_number')
                    ->required(),
                Hidden::make('requested_by')
                    ->default(fn () => Auth::id()),
                TextInput::make('service_type')
                    ->required(),
                Textarea::make('problem_description')
                    ->required()
                    ->columnSpanFull(),
                Select::make('urgency_level')
                    ->required()
                    ->default('medium')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'critical' => 'Critical',
                    ]),
                Select::make('status')
                    ->required()
                    ->default(VehicleServiceRequest::STATUS_PENDING)
                    ->options([
                        VehicleServiceRequest::STATUS_PENDING => 'Pending',
                        VehicleServiceRequest::STATUS_APPROVED => 'Approved',
                        VehicleServiceRequest::STATUS_IN_PROGRESS => 'In Progress',
                        VehicleServiceRequest::STATUS_COMPLETED => 'Completed',
                        VehicleServiceRequest::STATUS_REJECTED => 'Rejected',
                    ]),
                TextInput::make('service_provider'),
                TextInput::make('approved_by')
                    ->numeric()
                    ->hidden(),
                DatePicker::make('approved_at'),
                DatePicker::make('service_date'),
                FileUpload::make('service_report_file')
                    ->directory('vehicle-service-reports')
                    ->acceptedFileTypes(['application/pdf', 'image/*'])
                    ->downloadable()
                    ->openable(),
            ]);
    }
}
