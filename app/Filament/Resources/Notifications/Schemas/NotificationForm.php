<?php

namespace App\Filament\Resources\Notifications\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class NotificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('type')
                    ->required(),
                TextInput::make('channel')
                    ->required()
                    ->default('dashboard'),
                TextInput::make('title')
                    ->required(),
                Textarea::make('message')
                    ->required()
                    ->columnSpanFull(),
                TextInput::make('priority')
                    ->required()
                    ->default('normal'),
                TextInput::make('status')
                    ->required()
                    ->default('unread'),
                Select::make('user_id')
                    ->relationship('user', 'name'),
                TextInput::make('notifiable_type'),
                TextInput::make('notifiable_id')
                    ->numeric(),
                DateTimePicker::make('sent_at'),
                DateTimePicker::make('read_at'),
            ]);
    }
}
