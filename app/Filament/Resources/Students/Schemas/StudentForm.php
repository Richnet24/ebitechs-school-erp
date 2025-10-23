<?php

namespace App\Filament\Resources\Students\Schemas;

use App\Models\ClassModel;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations Personnelles')
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
                        
                        TextInput::make('student_number')
                            ->label('Numéro d\'étudiant')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),
                        
                        Select::make('class_id')
                            ->label('Classe')
                            ->relationship('class', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Select::make('parent_id')
                            ->label('Parent/Tuteur')
                            ->relationship('parent', 'name')
                            ->searchable()
                            ->preload()
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
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $user = User::create([
                                    ...$data,
                                    'password' => bcrypt('password'),
                                ]);
                                
                                return $user->id;
                            }),
                    ])
                    ->columns(2),
                
                Section::make('Informations Académiques')
                    ->schema([
                        DatePicker::make('admission_date')
                            ->label('Date d\'admission')
                            ->required(),
                        
                        DatePicker::make('birth_date')
                            ->label('Date de naissance')
                            ->required(),
                        
                        TextInput::make('birth_place')
                            ->label('Lieu de naissance')
                            ->maxLength(255),
                        
                        TextInput::make('nationality')
                            ->label('Nationalité')
                            ->default('CD')
                            ->maxLength(255),
                        
                        TextInput::make('religion')
                            ->label('Religion')
                            ->maxLength(255),
                        
                        Select::make('blood_type')
                            ->label('Groupe sanguin')
                            ->options([
                                'A+' => 'A+',
                                'A-' => 'A-',
                                'B+' => 'B+',
                                'B-' => 'B-',
                                'AB+' => 'AB+',
                                'AB-' => 'AB-',
                                'O+' => 'O+',
                                'O-' => 'O-',
                            ]),
                    ])
                    ->columns(2),
                
                Section::make('Informations Médicales et Urgences')
                    ->schema([
                        Textarea::make('medical_notes')
                            ->label('Notes médicales')
                            ->columnSpanFull(),
                        
                        Textarea::make('emergency_contact')
                            ->label('Contact d\'urgence')
                            ->columnSpanFull(),
                        
                        TextInput::make('emergency_phone')
                            ->label('Téléphone d\'urgence')
                            ->tel()
                            ->maxLength(255),
                        
                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                'active' => 'Actif',
                                'inactive' => 'Inactif',
                                'graduated' => 'Diplômé',
                                'transferred' => 'Transféré',
                                'suspended' => 'Suspendu',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(2),
            ]);
    }
}
