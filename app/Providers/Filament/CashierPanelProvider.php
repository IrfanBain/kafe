<?php

namespace App\Providers\Filament;

use App\Filament\Cashier\Pages\Dashboard;
use App\Filament\Cashier\Pages\CreateOrderPage;
use App\Filament\Cashier\Resources\OrderResource;
use App\Filament\Cashier\Resources\ProductResource;
use App\Filament\Cashier\Resources\TableResource;
use App\Filament\Cashier\Widgets\CashierStatsWidget;
use App\Filament\Cashier\Widgets\RecentOrdersWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class CashierPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('cashier')
            ->path('cashier')
            ->brandName('Kafe Cashier')
            ->favicon(public_path('favicon.ico'))
            ->colors([
                'primary' => Color::Orange,
            ])
            ->login()
            ->discoverResources(in: app_path('Filament/Cashier/Resources'), for: 'App\\Filament\\Cashier\\Resources')
            ->discoverPages(in: app_path('Filament/Cashier/Pages'), for: 'App\\Filament\\Cashier\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Cashier/Widgets'), for: 'App\\Filament\\Cashier\\Widgets')
            ->widgets([
                CashierStatsWidget::class,
                RecentOrdersWidget::class,
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
