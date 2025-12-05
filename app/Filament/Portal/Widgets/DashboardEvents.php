<?php

namespace App\Filament\Portal\Widgets;

use App\Models\Event;
use Filament\Tables\Table;
use Filament\Actions\ViewAction;
use Filament\Widgets\TableWidget;
use Filament\Actions\BulkActionGroup;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Model;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Portal\Resources\Events\EventResource;

class DashboardEvents extends TableWidget
{
    protected static ?int $sort = 8;

    public static function canView(): bool
    {
        $user = auth()->user();
        return ! $user->hasAnyRole(['admin', 'super_admin']);
    }

    protected static ?string $heading = 'Upcoming Events';

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(Event::where('status', 'Upcoming')->orderBy('created_at', 'desc')->take(4))
            ->paginated(false)
            ->contentGrid(['xl' => 2])
            ->columns([
                Split::make([
                    ImageColumn::make('picture')
                        ->label('')
                        ->imageHeight(150)
                        ->imageWidth(250)
                        ->grow(false)
                        ->disk('public')
                        ->defaultImageUrl(url('storage/default/no-image.png'))
                        ->extraImgAttributes([
                            'alt' => 'Logo',
                            'loading' => 'lazy',
                            'class' => 'rounded-xl object-cover',
                        ]),
                    Stack::make([
                        Split::make([
                            TextColumn::make('event')
                                ->limit(40)
                                ->color('secondary')
                                ->weight('semibold')
                                ->tooltip(fn ($record) => $record->event),
    
                            TextColumn::make('status')
                                ->getStateUsing(fn ($record) => $record->status)
                                ->badge()
                                ->colors([
                                    'indigo' => 'Upcoming',
                                    'warning' => 'Ongoing',
                                    'success' => 'Completed',
                                    'danger' => 'Cancelled',
                                ]),
                        ]),
                        
                        TextColumn::make('location')
                            ->weight('semibold'),
                        
                        TextColumn::make('start_date')
                            ->dateTime('M j, Y h:i A')
                            ->badge()
                            ->color('gray'),

                        TextColumn::make('end_date')
                            ->dateTime('M j, Y h:i A')
                            ->badge()
                            ->color('gray'),
                    ])->space(2)
                ])->from('md')
            ]);
    }
}
