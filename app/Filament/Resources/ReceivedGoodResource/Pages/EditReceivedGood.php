<?php

namespace App\Filament\Resources\ReceivedGoodResource\Pages;

use App\Filament\Resources\ReceivedGoodResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditReceivedGood extends EditRecord
{
    protected static string $resource = ReceivedGoodResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
