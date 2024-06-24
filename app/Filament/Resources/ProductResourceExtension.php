<?php

namespace App\Filament\Resources;

use App\Models\Product as ModelsProduct;
use Illuminate\Database\Eloquent\Model;
use Lunar\Facades\DB;
use Lunar\Models\Attribute;
use Lunar\Models\Currency;
use Lunar\Models\Product;
use Lunar\Models\TaxClass;
use Filament\Forms\Components\Grid;


use Lunar\Admin\Filament\Resources\ProductResource;

class ProductResourceExtension extends \Lunar\Admin\Support\Extending\ResourceExtension
{
    // protected static ?string $model = ModelsProduct::class;


    public function extendForm(\Filament\Forms\Form $form): \Filament\Forms\Form
    {
        return $form->schema([
            ...$form->getComponents(withHidden: true),

            \Filament\Forms\Components\TextInput::make('custom_column')
        ]);
    }

    public function extendTable(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table->columns([
            ...$table->getColumns(),
            \Filament\Tables\Columns\TextColumn::make('custom_column')
        ]);
    }

    public function getRelations(array $managers) : array
    {
        return [
            ...$managers,
            // This is just a standard Filament relation manager.
            // see https://filamentphp.com/docs/3.x/panels/resources/relation-managers#creating-a-relation-manager
            // MyCustomProductRelationManager::class,
        ];
    }

    public function extendPages(array $pages) : array
    {
        return [
            ...$pages,
            // This is just a standard Filament page
            // see https://filamentphp.com/docs/3.x/panels/pages#creating-a-page
            // 'my-page-route-name' => MyPage::route('/{record}/my-page'),
        ];
    }

    public function extendSubNavigation(array $nav) : array
    {
        return [
            ...$nav,
            // This is just a standard Filament page
            // see https://filamentphp.com/docs/3.x/panels/pages#creating-a-page
            // MyPage::class,
        ];
    }

    protected function getFormActions(): array
    {
        return [
            // ...parent::getFormActions(),
            Action::make('close')->action('createAndClose'),
        ];
    }

    protected function getDefaultHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()->createAnother(false)->form(
                // static::createActionFormInputs()
            )->using(
                fn (array $data, string $model) => static::createRecord($data, $model)
            )->successRedirectUrl(fn (Model $record): string => route('filament.lunar.resources.products.edit', [
                'record' => $record,
            ])),
        ];
    }
    protected function getHeaderActions(): array
    {
        dd(222444);
        return [
            Actions\CreateAction::make()->createAnother(false)->form(
                static::createActionFormInputs()
            )->using(
                fn (array $data, string $model) => static::createRecord($data, $model)
            )->successRedirectUrl(fn (Model $record): string => route('filament.lunar.resources.products.edit', [
                'record' => $record,
            ])),
        ];
    }


    public static function createRecord(array $data, string $model): Model
    {
        dd(222);
        $currency = Currency::getDefault();

        $nameAttribute = Attribute::whereAttributeType($model)
            ->whereHandle('name')
            ->first()
            ->type;

        DB::beginTransaction();
        $product = $model::create([
            'status' => 'draft',
            'product_type_id' => $data['product_type_id'],
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
}
