<?php

namespace App\Filament\Portal\Pages;

use BackedEnum;
use App\Models\Event;
use Filament\Pages\Page;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Columns\ImageColumn;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\Contracts\HasActions;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Range;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Tables\Columns\Summarizers\Average;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Actions\Concerns\InteractsWithActions;
use Filament\Schemas\Concerns\InteractsWithSchemas;

class EventReport extends Page implements HasActions, HasSchemas, HasTable
{
    use InteractsWithActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    protected static ?string $navigationLabel = 'Event Reports';
    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-document-chart-bar';

    protected string $view = 'filament.portal.pages.event-report';

    protected function getHeaderActions(): array
    {
        return [
            Action::make('print_report')
                ->color('info')
                ->label('Print')
                ->icon('heroicon-s-printer'),
        ];
    }

    public function getTabs(): array
    {   
        return [
            'all' => Tab::make('All')
                ->badge(fn () => Event::count()),
        ];
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading('Event Performance Report - Attendance Rate Analysis')
            ->query(
                Event::query()
                    ->where('status', 'Completed')
                    ->withCount([
                        'attendees as total_participants',
                        'attendees as confirmed_attending' => fn($q) => $q->where('event_users.is_attending', true)
                    ])
            )
            ->columns([
                TextColumn::make('event')
                    ->searchable()
                    ->sortable()
                    ->wrap(),
                    
                TextColumn::make('location'),
                    
                TextColumn::make('start_date')
                    ->dateTime('M d, Y'),
                
                TextColumn::make('end_date')
                    ->dateTime('M d, Y'),
                    
                TextColumn::make('status')
                    ->badge()
                    ->colors([
                        'warning' => 'Upcoming',
                        'success' => 'Ongoing',
                        'cyan' => 'Completed',
                        'danger' => 'Cancelled',
                    ]),
                    
                TextColumn::make('total_participants')
                    ->label('Total Participants')
                    ->numeric()
                    ->sortable()
                    ->summarize([
                        Sum::make(),
                        Average::make(),
                    ]),
                    
                TextColumn::make('confirmed_attending')
                    ->label('Attended')
                    ->numeric()
                    ->sortable()
                    ->summarize([
                        Sum::make(),
                        Average::make(),
                    ]),
                    
                TextColumn::make('attendance_rate')
                    ->label('Attendance Rate')
                    ->getStateUsing(function (Event $record): string {
                        $total = $record->total_participants ?? 0;
                        $confirmed = $record->confirmed_attending ?? 0;
                        if ($total == 0) return '0%';
                        return round(($confirmed / $total) * 100) . '%';
                    })
                    ->badge()
                    ->color(fn (string $state): string => 
                        (int)str_replace('%', '', $state) >= 70 ? 'success' : (
                            (int)str_replace('%', '', $state) >= 50 ? 'warning' : 'danger'
                        )
                    ),
            ])
            ->defaultSort('start_date', 'desc');
    }

    
}