<?php

namespace App\Providers;

use App\Filament\Resources\ProductResourceExtension;
use App\Filament\Resources\ProductResource\Pages\EditProductExtension;
use App\Filament\Resources\ProductResource\Pages\ListProductsExtension;
use Illuminate\Support\ServiceProvider;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Facades\ModelManifest;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::panel(fn($panel) => $panel->path('admin'))
            ->extensions([
                \Lunar\Admin\Filament\Resources\ProductResource::class => ProductResourceExtension::class,
                \Lunar\Admin\Filament\Resources\ProductResource\Pages\EditProduct::class => EditProductExtension::class,
                \Lunar\Admin\Filament\Resources\ProductResource\Pages\ListProducts::class => ListProductsExtension::class
            ])
            ->register();
            $models = collect([
                \Lunar\Models\Product::class => \App\Models\Product::class,
            ]);

            ModelManifest::register($models);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
