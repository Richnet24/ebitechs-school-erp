<?php

namespace App\Filament\Resources\Teachers\Schemas;

use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class TeacherForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de l\'Enseignant')
                    ->schema([
                        Select::make('user_id')
                            ->label('Utilisateur')
                            ->relationship('user', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('email')
                                    ->email()
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('phone')
                                    ->tel()
                                    ->maxLength(255),
                                TextInput::make('address')
                                    ->maxLength(255),
                                DatePicker::make('date_of_birth'),
                                Select::make('gender')
                                    ->options([
                                        'male' => 'Masculin',
                                        'female' => 'Féminin',
                                        'other' => 'Autre',
                                    ]),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $user = User::create([
                                    ...$data,
                                    'password' => bcrypt('password'),
                                ]);
                                
                                return $user->id;
                            }),
                        
                        TextInput::make('employee_number')
                            ->label('Numéro d\'employé')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        TextInput::make('specialization')
                            ->label('Spécialisation')
                            ->maxLength(255),
                        
                        TextInput::make('qualification')
                            ->label('Qualification')
                            ->maxLength(255),
                        
                        DatePicker::make('hire_date')
                            ->label('Date d\'embauche')
                            ->required(),
                        
                        TextInput::make('salary')
                            ->label('Salaire')
                            ->numeric()
                            ->prefix('$'),
                        
                        Select::make('employment_type')
                            ->label('Type d\'emploi')
                            ->options([
                                'full_time' => 'Temps plein',
                                'part_time' => 'Temps partiel',
                                'contract' => 'Contrat',
                                'substitute' => 'Suppléant',
                            ])
                            ->default('full_time')
                            ->required(),
                        
                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                'active' => 'Actif',
                                'inactive' => 'Inactif',
                                'on_leave' => 'En congé',
                                'terminated' => 'Terminé',
                            ])
                            ->default('active')
                            ->required(),
                        
                        Textarea::make('notes')
                            ->label('Notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2),
            ]);
    }
}
