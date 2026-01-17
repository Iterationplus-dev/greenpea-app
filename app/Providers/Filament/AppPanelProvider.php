<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Filament\Support\Colors\Color;

class AppPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            // ->id('getMultiFactorAuthenticationRequiredMiddlewareName')
            ->id('app')
            // ->domain('admin.greenpea.test')
            ->domain('admin.greenpea-app.test')
            ->path('/')
            // ->path('app')
            ->login()
            ->authGuard('admin')
            // ->authMiddleware(['auth'])//single guard
            ->authMiddleware([
                Authenticate::class,
            ])

            ->colors([
                'primary' => Color::Emerald,
                //'primary' => '#16a34a', // optional
            ])
            ->viteTheme('resources/css/filament/app/theme.css')
            ->brandName('GreenPea')
            ->brandLogo(asset('img/greenpea-favicon.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('img/greenpea-favicon.png'))
            ->font('https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap')
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                // Dashboard::class,
                \App\Filament\Pages\Dashboard::class,
                 \App\Filament\Pages\WalkInBookingWizard::class,
            ])
            ->sidebarWidth('16rem')
            ->sidebarCollapsibleOnDesktop(false)
            ->collapsedSidebarWidth('9rem')
            ->topNavigation(false)
            ->topBar(true)
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([

            ])
            // ->topbar(false)
            ->plugins([
                AuthUIEnhancerPlugin::make()
                    ->formPanelPosition('right')
                    ->mobileFormPanelPosition('bottom')
                    ->formPanelWidth('40%')
                    // ->formPanelBackgroundColor(Color::White, '300')
                    // ->formPanelBackgroundColor(Color::hex('#ffffff'))
                    ->emptyPanelBackgroundImageUrl('https://res.cloudinary.com/dney6qnzd/image/upload/v1766563654/hotel-apartments-view-edited_axyf9u.jpg')
                    ->emptyPanelBackgroundImageOpacity('90%')
                    ->emptyPanelBackgroundColor(Color::Green, '300')
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
            ]);
        // ->authMiddleware([
        //     Authenticate::class,
        // ]);
    }
}
