<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\Pagination\Paginator;
use Lunar\Admin\Support\Extending\ListPageExtension;
use Lunar\Admin\Filament\Widgets;
use Lunar\Admin\Filament\Resources\ProductResource;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Components\Grid;
use Lunar\Facades\DB;
use Lunar\Models\Attribute;
use Lunar\Models\Currency;
use Lunar\Models\Product;
use Lunar\Models\TaxClass;

class ListProductsExtension extends ListPageExtension
{
    public function headerWidgets(array $widgets): array
    {
        $widgets = [
            ...$widgets,
        ];

        return $widgets;
    }

    public function headerActions(array $actions): array
    {
        $actions = [
            Actions\CreateAction::make()->createAnother(false)->form(
                [
                    Grid::make(2)->schema([
                        ProductResource::getBaseNameFormComponent(),
                        ProductResource::getProductTypeFormComponent()->required(),
                    ]),
                    Grid::make(2)->schema([
                        ProductResource::getSkuFormComponent(),
                        ProductResource::getBasePriceFormComponent(),
                    ]),
                    Grid::make(2)->schema([
                        \Filament\Forms\Components\TextInput::make('custom_column'),
                    ]),]
                )->using(
                fn (array $data, string $model) => static::createRecord($data, $model)
            )->successRedirectUrl(fn (Model $record): string => route('filament.lunar.resources.products.edit', [
                'record' => $record,
            ])),
        ];

        return $actions;
    }

    public static function createRecord(array $data, string $model): Model
    {
        // dd($data);
        $currency = Currency::getDefault();

        $nameAttribute = Attribute::whereAttributeType($model)
            ->whereHandle('name')
            ->first()
            ->type;

        DB::beginTransaction();
        $product = $model::create([
            'status' => 'draft',
            'product_type_id' => $data['product_type_id'],
            'custom_column' => $data['custom_column'],
            'attribute_data' => [
                'name' => new $nameAttribute($data['name']),
            ],
        ]);
        $variant = $product->variants()->create([
            'tax_class_id' => TaxClass::getDefault()->id,
            'sku' => $data['sku'],
        ]);
        $variant->prices()->create([
            'min_quantity' => 1,
            'currency_id' => $currency->id,
            'price' => (int) bcmul($data['base_price'], $currency->factor),
        ]);
        DB::commit();

        return $product;
    }

    public function paginateTableQuery(Builder $query, int $perPage = 25): Paginator
    {
        return $query->paginate($perPage);
    }

    public function footerWidgets(array $widgets): array
    {
        $widgets = [
            ...$widgets,
            Widgets\Dashboard\Orders\LatestOrdersTable::make(),
        ];

        return $widgets;
    }

}
