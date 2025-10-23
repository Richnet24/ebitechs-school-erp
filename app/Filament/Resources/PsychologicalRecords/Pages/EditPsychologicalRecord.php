<?php

namespace App\Filament\Resources\PsychologicalRecords\Pages;

use App\Filament\Resources\PsychologicalRecords\PsychologicalRecordResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPsychologicalRecord extends EditRecord
{
    protected static string $resource = PsychologicalRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
