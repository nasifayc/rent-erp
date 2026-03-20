<?php

namespace App\Filament\Widgets;

use App\Models\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget;
use Illuminate\Database\Eloquent\Builder;

class UpcomingAlerts extends TableWidget
{
    protected static ?string $heading = 'Upcoming Alerts & Deadlines';

    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(fn (): Builder => Notification::query()->latest('sent_at')->limit(20))
            ->columns([
                TextColumn::make('title')->searchable()->wrap(),
                TextColumn::make('type')->badge(),
                TextColumn::make('priority')->badge(),
                TextColumn::make('status')->badge(),
                TextColumn::make('sent_at')->dateTime()->sortable(),
            ]);
    }
}
