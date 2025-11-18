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
use App\Filament\Pages\HealthCheckResults;
use Awcodes\LightSwitch\LightSwitchPlugin;
use Filament\Http\Middleware\Authenticate;
use Asmit\ResizedColumn\ResizedColumnPlugin;
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
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

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
            //->defaultThemeMode(ThemeMode::Light)
            ->brandLogoHeight('2rem')
            ->brandLogo(asset('assets/logo/logo-dark.png'))
            ->darkModeBrandLogo(asset('assets/logo/logo-white.png'))
            ->favicon(asset('assets/favicon/favicon.ico'))

            ->colors([
                //Nord Theme Colors
                'danger' => Color::hex('#bf616a'), // nord11
                'gray' => [
                    50 => '#eceff4',  // nord6 - snow storm
                    100 => '#e5e9f0', // nord5 - snow storm
                    200 => '#d8dee9', // nord4 - snow storm
                    300 => '#a7b1c5',
                    400 => '#8c9ab3',
                    500 => '#71829b',
                    600 => '#4c566a', // nord3 - polar night
                    700 => '#434c5e', // nord2 - polar night
                    800 => '#3b4252', // nord1 - polar night
                    900 => '#2e3440', // nord0 - polar night
                    950 => '#232831',
                ],
                'info' => Color::hex('#81a1c1'), // nord9
                'primary' => Color::hex('#013267'), // pitbi blue          
                'secondary' => Color::hex('#fe800d'), // pitbi orange
                'success' => Color::hex('#a3be8c'), // nord14
                'warning' => Color::hex('#ebcb8b'), // nord13
                'polarnight' => Color::hex('#3b4353'), // nord1

                //Neutral Colors
                'gray' => Color::Gray,
                'zinc' => Color::Zinc,
                'neutral' => Color::Neutral,
                'slate' => Color::Slate,
                'stone' => Color::Stone,

                //Tailwind Colors
                'red' => Color::Red,
                'rose' => Color::Rose,
                'orange' => Color::Orange,
                'amber' => Color::Amber,
                'yellow' => Color::Yellow,
                'green' => Color::Green,
                'emerald' => Color::Emerald,
                'teal' => Color::Teal,
                'cyan' => Color::Cyan,
                'sky' => Color::Sky,
                'blue' => Color::Blue,
                'indigo' => Color::Indigo,
                'violet' => Color::Violet,
                'purple' => Color::Purple,
                'fuchsia' => Color::Fuchsia,
                'pink' => Color::Pink,
            ])

            ->viteTheme('resources/css/filament/portal/theme.css')
            
            ->userMenuItems([
                'profile' => fn (Action $action) => $action->label('Edit profile'),
                'logout' => fn (Action $action) => $action->label('Log out'),
            ])

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Dashboard')
                    ->icon('heroicon-o-home'),
                
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
                    ->navigationLabel('Roles And Permission')
                    ->navigationGroup('System Settings')
                    ->globallySearchable(false),
                    
                LightSwitchPlugin::make()
                    ->position(Alignment::BottomRight)
                    ->enabledOn([
                        'auth.email',
                        'auth.login',
                        'auth.password',
                        'auth.register',
                    ]),
                
                ResizedColumnPlugin::make()
                    ->preserveOnDB(false),

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
