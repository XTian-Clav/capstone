<?php

namespace App\Filament\Portal\Resources\Mentors\Tables;

use App\Models\Mentor;
use App\Models\Startup;
use Filament\Tables\Table;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Enums\Size;
use Filament\Actions\ActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Tables\Filters\Filter;
use Filament\Support\Icons\Heroicon;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use App\Filament\Actions\ArchiveAction;
use App\Filament\Filters\EndDateFilter;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Enums\FiltersLayout;
use App\Filament\Filters\StartDateFilter;
use Filament\Tables\Columns\Layout\Split;
use Filament\Tables\Columns\Layout\Stack;
use Filament\Tables\Columns\SelectColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Filters\TrashedFilter;
use App\Filament\Actions\ArchiveBulkAction;
use App\Filament\Filters\CreatedDateFilter;
use Filament\Actions\ForceDeleteBulkAction;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use pxlrbt\FilamentExcel\Actions\Tables\ExportBulkAction;

class MentorsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->recordUrl(null)
            ->deferFilters(false)
            ->persistFiltersInSession()
            ->defaultSort('created_at', 'desc')

            ->emptyStateIcon('heroicon-o-identification')
            ->emptyStateHeading('No mentors found')
            ->emptyStateDescription('All mentors will appear here once its created.')

            //->contentGrid(['xl' => 2])
            ->columns([
                Split::make ([
                    ImageColumn::make('avatar')
                        ->label('')
                        ->disk('public')
                        ->size(50)
                        ->circular()
                        ->grow(false)
                        ->defaultImageUrl(url('storage/default/user.png')),
                    
                    Stack::make([
                        TextColumn::make('name')
                            ->weight('semibold')
                            ->searchable()
                            ->sortable(),

                        TextColumn::make('expertise')
                            ->color('secondary')
                            ->searchable()
                            ->badge(),
                    ])->space(1),
                    
                    Stack::make([
                        TextColumn::make('email')
                            ->searchable()
                            ->sortable()
                            ->badge()
                            ->color('success')
                            ->icon(Heroicon::Envelope),
                    
                        TextColumn::make('contact')
                            ->searchable()
                            ->badge()
                            ->color('gray')
                            ->icon(Heroicon::Phone),
                    ])->space(1),
                    
                    TextColumn::make('schedules')
                        ->label('Schedules')
                        ->getStateUsing(function ($record) {
                            $schedules = $record->schedules;
                            if (!$schedules) return [];

                            return collect($schedules)->map(function ($item) {
                                $startupNames = is_array($item['startup'] ?? null)
                                    ? implode(', ', $item['startup'])
                                    : (Startup::find($item['startup'])?->startup_name ?? 'N/A');

                                return "$startupNames | {$item['day']} {$item['start_time']} - {$item['end_time']}";
                            })->toArray();
                        })
                        ->listWithLineBreaks(),
                ])->from('md')
            ])
            ->filters([
                CreatedDateFilter::make('created_at')->columnSpan(2),
                StartDateFilter::make(),
                EndDateFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make()->color('gray'),
                    EditAction::make()->color('gray')
                        ->visible(fn ($record) => ! $record->trashed())
                        ->authorize(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    RestoreAction::make()
                        ->color('success')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                    ArchiveAction::make()
                        ->authorize(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    ForceDeleteAction::make()->color('danger')
                        ->icon('heroicon-s-archive-box-x-mark')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                ])
                ->label('Actions')
                ->icon('heroicon-o-bars-arrow-down')
                ->color('gray')
                ->size(Size::ExtraSmall)
                ->button()
                ->visible(fn () => auth()->user()->hasAnyRole(['super_admin', 'admin'])),

                ViewAction::make('alt_view')
                    ->button()
                    ->color('gray')
                    ->visible(fn () => auth()->user()->hasAnyRole(['incubatee', 'investor'])),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    ExportBulkAction::make()
                        ->color('gray')
                        ->authorize(fn () => auth()->user()->hasAnyRole(['admin', 'super_admin'])),
                    RestoreBulkAction::make()
                        ->color('success')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                    ArchiveBulkAction::make(),
                    ForceDeleteBulkAction::make()
                        ->icon('heroicon-s-archive-box-x-mark')
                        ->authorize(fn () => auth()->user()->hasRole('super_admin')),
                ])
                ->label('Bulk Actions')
                ->icon('heroicon-s-cog-6-tooth')
                ->color('info')
                ->size(Size::Small)
                ->button(),
            ]);
    }
}
