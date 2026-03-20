<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true),
                Select::make('role')
                    ->required()
                    ->options([
                        User::ROLE_ADMIN => 'Admin',
                        User::ROLE_DEPARTMENT_MANAGER => 'Department Manager',
                        User::ROLE_ADMIN_OFFICER => 'Administrative Officer',
                        User::ROLE_LEGAL_REVIEWER => 'Legal Reviewer',
                        User::ROLE_FLEET_MANAGER => 'Fleet Manager',
                        User::ROLE_FINANCE_OFFICER => 'Finance Officer',
                        User::ROLE_DRIVER => 'Driver',
                    ])
                    ->default(User::ROLE_DEPARTMENT_MANAGER),
                Toggle::make('is_admin')
                    ->label('Admin Access')
                    ->default(false),
                TextInput::make('password')
                    ->password()
                    ->revealable()
                    ->required(fn (string $operation): bool => $operation === 'create')
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->minLength(8),
            ]);
    }
}
