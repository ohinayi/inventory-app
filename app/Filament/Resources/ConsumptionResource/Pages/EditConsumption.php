<?php

namespace App\Filament\Resources\ConsumptionResource\Pages;

use App\Filament\Resources\ConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditConsumption extends EditRecord
{
    protected static string $resource = ConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
