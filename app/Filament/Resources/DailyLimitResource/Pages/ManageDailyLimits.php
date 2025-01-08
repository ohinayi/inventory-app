<?php

namespace App\Filament\Resources\DailyLimitResource\Pages;

use App\Filament\Resources\DailyLimitResource;
use Filament\Actions;
use Filament\Resources\Pages\ManageRecords;

class ManageDailyLimits extends ManageRecords
{
    protected static string $resource = DailyLimitResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
