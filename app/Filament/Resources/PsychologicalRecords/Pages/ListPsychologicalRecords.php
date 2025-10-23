<?php

namespace App\Filament\Resources\PsychologicalRecords\Pages;

use App\Filament\Resources\PsychologicalRecords\PsychologicalRecordResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPsychologicalRecords extends ListRecords
{
    protected static string $resource = PsychologicalRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
