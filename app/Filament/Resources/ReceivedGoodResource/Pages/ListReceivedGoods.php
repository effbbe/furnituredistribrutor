<?php

namespace App\Filament\Resources\ReceivedGoodResource\Pages;

use App\Filament\Resources\ReceivedGoodResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListReceivedGoods extends ListRecords
{
    protected static string $resource = ReceivedGoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
