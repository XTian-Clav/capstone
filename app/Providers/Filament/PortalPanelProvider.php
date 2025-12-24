<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use Filament\Pages\Dashboard;
use App\Filament\Pages\Backups;
use Filament\Support\Enums\Width;
use Filament\Support\Colors\Color;
use App\Filament\Pages\CustomLogin;
use App\Filament\Pages\EditProfile;
use Filament\Enums\UserMenuPosition;
use App\Filament\Portal\Widgets\Events;
use Awcodes\LightSwitch\Enums\Alignment;
use Filament\Navigation\NavigationGroup;
use App\Filament\Portal\Pages\Guidelines;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Auth\MultiFactor\Email\EmailAuthentication;
use Filament\Http\Middleware\DisableBladeIconComponents;
use CharrafiMed\GlobalSearchModal\GlobalSearchModalPlugin;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Leandrocfe\FilamentApexCharts\FilamentApexChartsPlugin;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;

class PortalPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('portal')
            ->path('portal')
            ->authGuard('web')
            
            ->brandName('PITBI Portal')
            ->userMenu(position: UserMenuPosition::Sidebar)
            ->globalSearch(true)
            ->breadcrumbs(false)
            
            ->login()
            ->passwordReset()
            ->emailVerification(false)
            ->emailChangeVerification(false)
            ->multiFactorAuthentication([
                EmailAuthentication::make()
                    ->codeExpiryMinutes(5),
            ])

            ->profile(EditProfile::class, isSimple: false)
            ->databaseNotifications()

            ->font('Poppins')
            ->defaultThemeMode(ThemeMode::Light)
            ->brandLogo(fn () => view('logo-dark'))
            ->darkModeBrandLogo(fn () => view('logo-white'))
            ->favicon(asset('assets/favicon/favicon.ico'))

            ->colors([
                'danger' => Color::Red,
                'gray' => Color::Gray,
                'info' => Color::Sky,
                'primary' => Color::Gray,
                'secondary' => Color::Gray,
                'success' => Color::Lime,
                'warning' => Color::Amber,

                'cyan' => Color::Cyan,
                'pitbi-orange' => '#fe800d',
                'pitbi-blue' => '#013267',
            ])

            ->viteTheme('resources/css/filament/portal/theme.css')
            
            ->userMenuItems([
                'profile' => fn (Action $action) => $action->label('Edit profile'),
                'guidelines' => Action::make('Guidelines')
                    ->label('Guidelines')
                    ->icon('heroicon-o-book-open')
                    ->url(fn (): string => Guidelines::getUrl()),
                'logout' => fn (Action $action) => $action->label('Log out'),
            ])

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Dashboard')
                    ->icon('heroicon-o-home')
                    ->collapsed(false),
                
                NavigationGroup::make()
                    ->label('Startup')
                    ->icon('heroicon-o-rocket-launch')
                    ->collapsed(false),
                
                NavigationGroup::make()
                    ->label('Learning Materials')
                    ->icon('heroicon-o-academic-cap')
                    ->collapsed(false),
                
                NavigationGroup::make()
                    ->label('Reservation')
                    ->icon('heroicon-o-clipboard-document-check')
                    ->collapsed(false),

                NavigationGroup::make()
                    ->label('Inventory')
                    ->icon('heroicon-o-archive-box')
                    ->collapsed(false),

                NavigationGroup::make()
                    ->label('Generate Reports')
                    ->icon('heroicon-o-document-chart-bar')
                    ->collapsed(false),
                
                NavigationGroup::make()
                    ->label('Manage Users')
                    ->icon('heroicon-o-user-group')
                    ->collapsed(false),
                
                NavigationGroup::make()
                    ->label('System Settings')
                    ->icon('heroicon-o-cog-6-tooth')
                    ->collapsed(false),
            ])

            ->resourceCreatePageRedirect('index')
            ->resourceEditPageRedirect('index')

            ->discoverResources(in: app_path('Filament/Portal/Resources'), for: 'App\\Filament\\Portal\\Resources')
            ->discoverPages(in: app_path('Filament/Portal/Pages'), for: 'App\\Filament\\Portal\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Portal/Widgets'), for: 'App\\Filament\\Portal\\Widgets')
            ->widgets([
                
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make()
                    ->navigationIcon('')
                    ->activeNavigationIcon('')
                    ->globallySearchable(false)
                    ->navigationGroup('System Settings')
                    ->navigationLabel('Roles And Permission')
                    ->gridColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 3
                    ])
                    ->sectionColumnSpan(1)
                    ->checkboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                        'lg' => 4,
                    ])
                    ->resourceCheckboxListColumns([
                        'default' => 1,
                        'sm' => 2,
                    ]),
                    
                LightSwitchPlugin::make()
                    ->position(Alignment::BottomRight)
                    ->enabledOn([
                        'auth.email',
                        'auth.login',
                        'auth.password',
                        'auth.register',
                    ]),

                FilamentApexChartsPlugin::make(),

                GlobalSearchModalPlugin::make()
                    ->modal(
                        slideOver: false,
                        autofocused: true,
                        hasCloseButton: true,
                        closedByClickingAway: true,
                    )
                    ->highlighter(true)
                    ->RetainRecentIfFavorite(true),
            ]);
    }
}
