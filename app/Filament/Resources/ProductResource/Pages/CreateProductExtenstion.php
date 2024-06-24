<?php

namespace App\Filament\Resources\ProductResource\Pages;

use Filament\Actions;
use Illuminate\Database\Eloquent\Builder;
use Lunar\Admin\Support\Extending\CreatePageExtension;
use Lunar\Admin\Filament\Widgets;
use Illuminate\Database\Eloquent\Model;

class CreateProductExtension extends CreatePageExtension
{
    public function heading($title): string
    {
        return $title . ' - Example';
    }

    public function subheading($title): string
    {
        return $title . ' - Example';
    }

    public function getTabs(array $tabs): array
    {
        return [
            ...$tabs,
        ];
    }

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
            Actions\Action::make('Cancel'),
        ];

        return $actions;
    }

    public function formActions(array $actions): array
    {
        $actions = [
            ...$actions,
            Actions\Action::make('Create and Edit'),
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

    // public function beforeCreate(array $data): array
    // {
    //     $data['custom_column'] .= 'ABC';

    //     return $data;
    // }

    public function beforeCreation(array $data): array
    {
        return $data;
    }

    public function afterCreation(Model $record, array $data): Model
    {
        return $record;
    }
}
