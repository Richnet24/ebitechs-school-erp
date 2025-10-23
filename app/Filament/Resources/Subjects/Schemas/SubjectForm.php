<?php

namespace App\Filament\Resources\Subjects\Schemas;

use Filament\Forms\Components\ColorPicker;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class SubjectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de la Matière')
                    ->description('Configurez les informations de base de la matière')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom de la matière')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Mathématiques, Physique, Programmation, etc.'),
                        
                        TextInput::make('code')
                            ->label('Code de la matière')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('Ex: MATH, PHYS, PROG, etc.')
                            ->helperText('Code unique pour identifier la matière'),
                        
                        Select::make('branch_id')
                            ->label('Filière')
                            ->relationship('branch', 'name')
                            ->required()
                            ->searchable()
                            ->preload()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nom de la filière')
                                    ->required(),
                                TextInput::make('code')
                                    ->label('Code de la filière')
                                    ->required(),
                            ]),
                        
                        TextInput::make('credits')
                            ->label('Crédits')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(10)
                            ->helperText('Nombre de crédits attribués à cette matière'),
                        
                        TextInput::make('hours_per_week')
                            ->label('Heures par semaine')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(20)
                            ->helperText('Nombre d\'heures de cours par semaine'),
                        
                        ColorPicker::make('color')
                            ->label('Couleur')
                            ->default('#10B981')
                            ->helperText('Couleur pour identifier visuellement la matière'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Description détaillée de la matière'),
                        
                        Toggle::make('is_active')
                            ->label('Matière active')
                            ->default(true)
                            ->helperText('Désactiver pour masquer cette matière'),
                    ])
                    ->columns(2),
            ]);
    }
}
