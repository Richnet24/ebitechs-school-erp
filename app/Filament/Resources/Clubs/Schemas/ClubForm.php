<?php

namespace App\Filament\Resources\Clubs\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ClubForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations du Club')
                    ->description('Configurez les informations du club scolaire')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom du club')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Club de Football, Club de Théâtre, etc.'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Description des activités du club'),
                        
                        Select::make('category')
                            ->label('Catégorie')
                            ->options([
                                'sport' => 'Sport',
                                'culture' => 'Culture',
                                'academic' => 'Académique',
                                'social' => 'Social',
                                'environment' => 'Environnement',
                                'technology' => 'Technologie',
                                'art' => 'Art',
                                'music' => 'Musique',
                            ])
                            ->required()
                            ->searchable(),
                        
                        Select::make('supervisor_id')
                            ->label('Superviseur')
                            ->relationship('supervisor', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Enseignant ou personnel responsable du club'),
                        
                        TextInput::make('max_members')
                            ->label('Nombre maximum de membres')
                            ->numeric()
                            ->default(30)
                            ->minValue(5)
                            ->maxValue(100)
                            ->helperText('Limite du nombre de membres dans le club'),
                        
                        TextInput::make('meeting_schedule')
                            ->label('Horaire des réunions')
                            ->maxLength(255)
                            ->placeholder('Ex: Mercredi 15h-17h'),
                        
                        TextInput::make('location')
                            ->label('Lieu des réunions')
                            ->maxLength(255)
                            ->placeholder('Ex: Salle de sport, Bibliothèque, etc.'),
                        
                        Toggle::make('is_active')
                            ->label('Club actif')
                            ->default(true)
                            ->helperText('Désactiver pour suspendre le club'),
                    ])
                    ->columns(2),
            ]);
    }
}