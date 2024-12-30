<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Filament\Resources\OrderResource\Widgets\Orderstatus;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Resources\Components\Tab;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array {
        return [
            OrderStatus::class
        ];
    }

    public function getTabs(): array {
        return [
            null => Tab::make('All'),
            'new' => Tab::make()->query(fn($query) => $query->where('status', 'processing')),
            'shipped' => Tab::make()->query(fn($query) => $query->where('status', 'shipped')),
            'delivered' => Tab::make()->query(fn($query) => $query->where('status', 'delivered')),
            'cancelled' => Tab::make()->query(fn($query) => $query->where('status', 'cancelled')),
        ];
    }
}
