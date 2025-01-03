<?php

namespace App\Filament\Resources\ReceivedGoodResource\Pages;

use App\Filament\Resources\ReceivedGoodResource;
use Filament\Actions;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;

class CreateReceivedGood extends CreateRecord
{
    protected static string $resource = ReceivedGoodResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        // Process repeater items
        $items = $record->items;

        foreach ($items as $item) {
            // Update stock on Product table
            $product = Product::where('sku', $item['sku'])->first();

            if ($product) {
                $product->increment('current_stock', $item['quantity']);
            } else {
                Product::create([
                    'sku' => $item['sku'],
                    'product_name' => $item['product_name'],
                    'slug' => $item['slug'],
                    'description' => $item['product_name'],                
                    'unit_price' => '0',
                    'unit' => 'pcs',
                    'current_stock' => $item['quantity'],
                    'category' => $item['category'],
                ]);
            }
        }
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
