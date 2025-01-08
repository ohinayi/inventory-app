<?php

namespace App\Filament\Resources\ConsumptionResource\Pages;

use App\Filament\Resources\ConsumptionResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListConsumptions extends ListRecords
{
    protected static string $resource = ConsumptionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
