<?php

namespace App\Filament\Resources\PsychologicalRecords;

use App\Filament\Resources\PsychologicalRecords\Pages\CreatePsychologicalRecord;
use App\Filament\Resources\PsychologicalRecords\Pages\EditPsychologicalRecord;
use App\Filament\Resources\PsychologicalRecords\Pages\ListPsychologicalRecords;
use App\Filament\Resources\PsychologicalRecords\Schemas\PsychologicalRecordForm;
use App\Filament\Resources\PsychologicalRecords\Tables\PsychologicalRecordsTable;
use App\Models\PsychologicalRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class PsychologicalRecordResource extends Resource
{
    protected static ?string $model = PsychologicalRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAcademicCap;
    
    public static function getNavigationGroup(): ?string
    {
        return 'Bien-être et Vie Scolaire';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function form(Schema $schema): Schema
    {
        return PsychologicalRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return PsychologicalRecordsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPsychologicalRecords::route('/'),
            'create' => CreatePsychologicalRecord::route('/create'),
            'edit' => EditPsychologicalRecord::route('/{record}/edit'),
        ];
    }
}
