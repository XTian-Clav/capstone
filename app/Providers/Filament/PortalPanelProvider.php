<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Actions\Action;
use Filament\Enums\ThemeMode;
use Filament\Pages\Dashboard;
use App\Filament\Pages\Backups;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Enums\UserMenuPosition;
use Filament\Navigation\NavigationGroup;
use Filament\Widgets\FilamentInfoWidget;
use App\Filament\Pages\HealthCheckResults;
use Filament\Http\Middleware\Authenticate;
use Jeffgreco13\FilamentBreezy\BreezyCore;
use Asmit\ResizedColumn\ResizedColumnPlugin;
use App\Filament\Plugins\CustomNordThemePlugin;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Muazzam\SlickScrollbar\SlickScrollbarPlugin;
use Filament\Http\Middleware\AuthenticateSession;
use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Andreia\FilamentNordTheme\FilamentNordThemePlugin;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use ShuvroRoy\FilamentSpatieLaravelBackup\FilamentSpatieLaravelBackupPlugin;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;

class PortalPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('portal')
            ->path('portal')
            
            ->brandName('PITBI Portal')
            ->topbar(false)
            ->globalSearch(false)
            ->breadcrumbs(false)
            
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->emailChangeVerification()
            ->authGuard('web')

            ->profile()

            ->font('Poppins')
            ->defaultThemeMode(ThemeMode::Light)
            ->brandLogoHeight('2rem')
            ->brandLogo(asset('assets/logo/light-theme-alt.png'))
            ->darkModeBrandLogo(asset('assets/logo/dark-theme.png'))
            ->favicon(asset('assets/favicon/favicon.ico'))

            /*->colors([
                'primary' => Color::Amber,
                'secondary' => Color::Gray,
                'success' => Color::Emerald,
                'danger' => Color::Red,
                'warning' => Color::Yellow,
                'info' => Color::Blue,
                'indigo' => Color::Indigo,
            ])*/

            ->viteTheme('resources/css/filament/portal/theme.css')
            
            ->userMenuItems([
                'logout' => fn (Action $action) => $action->label('Log out'),
            ])

            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Learning Materials')
                    ->icon('heroicon-o-academic-cap'),
                
                NavigationGroup::make()
                    ->label('Reservation')
                    ->icon('heroicon-o-clipboard-document-check'),

                NavigationGroup::make()
                    ->label('Inventory')
                    ->icon('heroicon-o-archive-box'),
                
                NavigationGroup::make()
                    ->label('System Settings')
                    ->icon('heroicon-o-cog-6-tooth'),
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
                //AccountWidget::class,
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
                    ->navigationGroup('System Settings'),

                FilamentSpatieLaravelHealthPlugin::make()
                    ->authorize(fn (): bool => auth()->user()->email === 'superadmin@gmail.com')
                    ->navigationLabel('Website Health Check')
                    ->navigationGroup('System Settings'),

                FilamentSpatieLaravelBackupPlugin::make()
                    ->authorize(fn (): bool => auth()->user()->email === 'superadmin@gmail.com')
                    ->usingPage(Backups::class),

                CustomNordThemePlugin::make(),

                ResizedColumnPlugin::make()
                    ->preserveOnDB(true),

                BreezyCore::make()
                    ->avatarUploadComponent(fn($fileUpload) => $fileUpload->disableLabel())
                    ->enableBrowserSessions(condition: true)
                    ->myProfile(
                        shouldRegisterUserMenu: true, // Sets the 'account' link in the panel User Menu (default = true)
                        userMenuLabel: 'My Profile', // Customizes the 'account' link label in the panel User Menu (default = null)
                        shouldRegisterNavigation: false, // Adds a main navigation item for the My Profile page (default = false)
                        navigationGroup: 'Settings', // Sets the navigation group for the My Profile page (default = null)
                        hasAvatars: true, // Enables the avatar upload form component (default = false)
                        slug: 'my-profile', // Sets the slug for the profile page (default = 'my-profile')
                        //force: false, // force the user to enable 2FA before they can use the application (default = false)
                    ),

                /*
                SlickScrollbarPlugin::make()
                    ->size('6px')
                    ->palette('primary')
                    ->hoverColor(Color::Amber, 700),
                */
            ]);
    }
}
