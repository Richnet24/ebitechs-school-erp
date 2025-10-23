<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations Personnelles')
                    ->description('Configurez les informations de base de l\'utilisateur')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom complet')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Jean Dupont'),
                        
                        TextInput::make('email')
                            ->label('Adresse email')
                            ->email()
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Ex: jean.dupont@example.com'),
                        
                        TextInput::make('password')
                            ->label('Mot de passe')
                            ->password()
                            ->required(fn (string $operation): bool => $operation === 'create')
                            ->minLength(8)
                            ->dehydrated(fn ($state) => filled($state))
                            ->dehydrateStateUsing(fn ($state) => bcrypt($state)),
                        
                        TextInput::make('phone')
                            ->label('Téléphone')
                            ->tel()
                            ->maxLength(255)
                            ->placeholder('Ex: +243 123 456 789'),
                        
                        Textarea::make('address')
                            ->label('Adresse')
                            ->rows(3)
                            ->placeholder('Adresse complète de l\'utilisateur'),
                        
                        DatePicker::make('date_of_birth')
                            ->label('Date de naissance')
                            ->maxDate(now()),
                        
                        Select::make('gender')
                            ->label('Genre')
                            ->options([
                                'male' => 'Masculin',
                                'female' => 'Féminin',
                                'other' => 'Autre',
                            ]),
                        
                        Toggle::make('is_active')
                            ->label('Utilisateur actif')
                            ->default(true)
                            ->helperText('Désactiver pour bloquer l\'accès de cet utilisateur'),
                    ])
                    ->columns(2),
            ]);
    }
}
