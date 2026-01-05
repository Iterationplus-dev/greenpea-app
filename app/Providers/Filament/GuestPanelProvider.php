<?php

namespace App\Providers\Filament;

use Filament\Panel;
use Filament\PanelProvider;
use Filament\Pages\Dashboard;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Filament\Http\Middleware\AuthenticateSession;
use DiogoGPinto\AuthUIEnhancer\AuthUIEnhancerPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Guest\Pages\GuestRegister;
use Filament\Auth\Pages\Login;
use Filament\Auth\Pages\PasswordReset\RequestPasswordReset;

class GuestPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('guest')
            ->path('guest')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->login()
            ->registration()
            ->passwordReset()

            ->authGuard('web') //
            ->brandName('GreenPea Apartments')
            ->brandLogo(asset('img/greenpea-favicon.png'))
            ->brandLogoHeight('3rem')
            ->favicon(asset('img/greenpea-favicon.png'))
            ->viteTheme('resources/css/filament/guest/theme.css')
            ->discoverResources(
                in: app_path('Filament/Guest/Resources'),
                for: 'App\Filament\Guest\Resources'
            )
            ->discoverPages(
                in: app_path('Filament/Guest/Pages'),
                for: 'App\Filament\Guest\Pages'
            )
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(
                in: app_path('Filament/Guest/Widgets'),
                for: 'App\Filament\Guest\Widgets'
            )
            ->widgets([
                // AccountWidget::class,
                // FilamentInfoWidget::class,
            ])
            // ->authPages([
            //     'register' => GuestRegister::class,
            // ])
            ->plugins([
                AuthUIEnhancerPlugin::make()
                    ->formPanelPosition('right')
                    ->mobileFormPanelPosition('bottom')
                    ->formPanelWidth('50%')
                    // ->formPanelBackgroundColor(Color::White, '300')
                    // ->formPanelBackgroundColor(Color::hex('#ffffff'))
                    ->emptyPanelBackgroundImageUrl('https://res.cloudinary.com/dney6qnzd/image/upload/v1767598377/guest-img-edited_rzdyoq.jpg')
                    ->emptyPanelBackgroundImageOpacity('90%')
                    ->emptyPanelBackgroundColor(Color::Blue, '300')
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
            ]);
    }
}
