<?php

namespace App\Filament\Resources\HealthRecords;

use App\Filament\Resources\HealthRecords\Pages\CreateHealthRecord;
use App\Filament\Resources\HealthRecords\Pages\EditHealthRecord;
use App\Filament\Resources\HealthRecords\Pages\ListHealthRecords;
use App\Filament\Resources\HealthRecords\Schemas\HealthRecordForm;
use App\Filament\Resources\HealthRecords\Tables\HealthRecordsTable;
use App\Models\HealthRecord;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class HealthRecordResource extends Resource
{
    protected static ?string $model = HealthRecord::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedHeart;
    
    public static function getNavigationGroup(): ?string
    {
        return 'Bien-être et Vie Scolaire';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function form(Schema $schema): Schema
    {
        return HealthRecordForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return HealthRecordsTable::configure($table);
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
            'index' => ListHealthRecords::route('/'),
            'create' => CreateHealthRecord::route('/create'),
            'edit' => EditHealthRecord::route('/{record}/edit'),
        ];
    }
}
