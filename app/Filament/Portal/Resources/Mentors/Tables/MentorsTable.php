<?php

namespace App\Filament\Portal\Resources\Mentors\Tables;

use App\Models\Mentor;
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
            ->defaultSort('created_at', 'asc')
            ->columns([
                ImageColumn::make('avatar')
                    ->label('')
                    ->disk('public')
                    ->size(50)
                    ->circular()
                    ->toggleable()
                    ->defaultImageUrl(url('storage/default/user.png')),

                TextColumn::make('name')
                    ->weight('semibold')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('contact')
                    ->searchable()
                    ->icon(Heroicon::Phone),

                TextColumn::make('email')
                    ->searchable()
                    ->sortable()
                    ->icon(Heroicon::Envelope),

                TextColumn::make('startups.startup_name')
                    ->label('Assigned To')
                    ->getStateUsing(fn ($record) =>
                        $record->startups->isNotEmpty()
                            ? $record->startups->implode('startup_name', '<br>')
                            : 'None'
                    )
                    ->html()
                    ->weight('semibold')
                    ->default('None')
                    ->color(fn ($state) => $state === 'None' ? 'gray' : 'info'),

                TextColumn::make('schedules')
                    ->label('Schedules')
                    ->getStateUsing(function ($record) {
                        if (blank($record->schedules)) {
                            return '—';
                        }
                
                        return collect($record->schedules)
                            ->map(fn ($item) => substr($item['day'], 0, 3) . " {$item['hour']}{$item['meridiem']}")
                            ->implode(', ');
                    })
                    ->toggleable()
                    ->searchable()
                    ->weight('semibold'),                
                
                TextColumn::make('created_at')
                    ->label('Created At')
                    ->since()
                    ->tooltip(fn ($record) => $record->created_at->format('F j, Y g:i A'))
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->sortable(), 
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
                ->color('secondary')
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
