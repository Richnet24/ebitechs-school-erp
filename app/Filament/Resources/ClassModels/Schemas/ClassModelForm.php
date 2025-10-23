<?php

namespace App\Filament\Resources\ClassModels\Schemas;

use App\Models\Branch;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ClassModelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de la Classe')
                    ->description('Configurez les informations de base de la classe')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom de la classe')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: 1ère Année Informatique, Terminale Génie Civil, etc.'),
                        
                        TextInput::make('code')
                            ->label('Code de la classe')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(10)
                            ->placeholder('Ex: 1INFO, TGC, etc.')
                            ->helperText('Code unique pour identifier la classe'),
                        
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
                        
                        TextInput::make('level')
                            ->label('Niveau')
                            ->numeric()
                            ->default(1)
                            ->minValue(1)
                            ->maxValue(10)
                            ->helperText('Niveau de la classe (1, 2, 3, etc.)'),
                        
                        TextInput::make('capacity')
                            ->label('Capacité maximale')
                            ->numeric()
                            ->default(40)
                            ->minValue(1)
                            ->maxValue(100)
                            ->helperText('Nombre maximum d\'élèves dans cette classe'),
                        
                        Select::make('teacher_id')
                            ->label('Professeur principal')
                            ->relationship('teacher', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Professeur responsable de cette classe'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Description détaillée de la classe'),
                        
                        Toggle::make('is_active')
                            ->label('Classe active')
                            ->default(true)
                            ->helperText('Désactiver pour masquer cette classe'),
                    ])
                    ->columns(2),
            ]);
    }
}
