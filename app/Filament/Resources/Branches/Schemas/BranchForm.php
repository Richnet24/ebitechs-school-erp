<?php

namespace App\Filament\Resources\Branches\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class BranchForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de la Filière')
                    ->description('Configurez les informations de base de la filière')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom de la filière')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Informatique, Génie Civil, etc.'),
                        
                        TextInput::make('code')
                            ->label('Code de la filière')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('Ex: INFO, GC, etc.')
                            ->helperText('Code unique pour identifier la filière'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Description détaillée de la filière'),
                        
                        ColorPicker::make('color')
                            ->label('Couleur')
                            ->default('#3B82F6')
                            ->helperText('Couleur pour identifier visuellement la filière'),
                        
                        Toggle::make('is_active')
                            ->label('Filière active')
                            ->default(true)
                            ->helperText('Désactiver pour masquer cette filière'),
                    ])
                    ->columns(2),
            ]);
    }
}
