<?php

namespace App\Providers\Filament;

use Filament\Enums\ThemeMode;
use Filament\FontProviders\GoogleFontProvider;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('Territorial Army')
            ->brandLogo(fn () => view('filament.brand-logo'))
            ->brandLogoHeight('2.2rem')
            ->favicon(asset('assets/mark.svg'))
            ->defaultThemeMode(ThemeMode::Light)
            ->renderHook(
                'panels::head.end',
                fn () => new \Illuminate\Support\HtmlString(
                    '<link rel="stylesheet" href="'.asset('assets/filament-admin.css').'?v='.filemtime(public_path('assets/filament-admin.css')).'">'
                ),
            )
            ->colors([
                'primary' => Color::hex('#172e25'),
                'gray'    => Color::Stone,
                'info'    => Color::hex('#8c6d1f'),
                'success' => Color::hex('#4a7a3f'),
                'warning' => Color::hex('#ba7619'),
                'danger'  => Color::Red,
            ])
            ->font('Inter', provider: GoogleFontProvider::class)
            ->sidebarCollapsibleOnDesktop()
            ->maxContentWidth(Width::Full)
            ->navigationGroups([
                NavigationGroup::make('Content')
                    ->icon('heroicon-o-document-text')
                    ->collapsible(false),
                NavigationGroup::make('People & Exams')
                    ->icon('heroicon-o-user-group')
                    ->collapsible(false),
                NavigationGroup::make('Commerce')
                    ->icon('heroicon-o-shopping-bag')
                    ->collapsible(false),
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                \App\Filament\Widgets\SubscriberStatsWidget::class,
                \App\Filament\Widgets\QuickActionsWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
