<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Lunar\Admin\Support\Extending\EditPageExtension;
use Lunar\Admin\Filament\Widgets;
use Illuminate\Database\Eloquent\Model;

class EditProductExtension extends EditPageExtension
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
            ...$actions,
            Actions\ActionGroup::make([
                Actions\Action::make('View on Storefront'),
                Actions\Action::make('Copy Link'),
                Actions\Action::make('Duplicate'),
            ])
        ];

        return $actions;
    }

    public function formActions(array $actions): array
    {
        $actions = [
            ...$actions,
            Actions\Action::make('Update and Edit'),
        ];

        return $actions;
    }

    public function footerWidgets(array $widgets): array
    {
        $widgets = [
            ...$widgets,
            Widgets\Dashboard\Orders\LatestOrdersTable::make(),
        ];

        return $widgets;
    }

    // public function beforeFill(array $data): array
    // {
    //     $data['model_code'] .= 'ABC';

    //     return $data;
    // }

    public function beforeSave(array $data): array
    {
        return $data;
    }

    public function beforeUpdate(array $data, Model $record): array
    {
        return $data;
    }

    public function afterUpdate(Model $record, array $data): Model
    {
        return $record;
    }

    public function relationManagers(array $managers): array
    {
        return $managers;
    }
}

// Typically placed in your AppServiceProvider file...
