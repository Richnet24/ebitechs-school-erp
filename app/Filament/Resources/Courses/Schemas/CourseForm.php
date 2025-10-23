<?php

namespace App\Filament\Resources\Courses\Schemas;

use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CourseForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations du Cours')
                    ->schema([
                        Select::make('subject_id')
                            ->label('Matière')
                            ->relationship('subject', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->required()
                                    ->maxLength(255),
                                TextInput::make('code')
                                    ->required()
                                    ->unique()
                                    ->maxLength(255),
                                Textarea::make('description')
                                    ->maxLength(65535),
                                TextInput::make('credits')
                                    ->numeric()
                                    ->default(1),
                                TextInput::make('hours_per_week')
                                    ->numeric()
                                    ->default(1),
                                Select::make('branch_id')
                                    ->relationship('branch', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                            ])
                            ->createOptionUsing(function (array $data): int {
                                $subject = Subject::create($data);
                                return $subject->id;
                            }),
                        
                        Select::make('class_id')
                            ->label('Classe')
                            ->relationship('class', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        Select::make('teacher_id')
                            ->label('Enseignant')
                            ->relationship('teacher', 'name')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        TextInput::make('name')
                            ->label('Nom du cours')
                            ->required()
                            ->maxLength(255),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->columnSpanFull(),
                        
                        TextInput::make('credits')
                            ->label('Crédits')
                            ->numeric()
                            ->required(),
                        
                        TextInput::make('hours_per_week')
                            ->label('Heures par semaine')
                            ->numeric()
                            ->required(),
                    ])
                    ->columns(2),
                
                Section::make('Planning du Cours')
                    ->schema([
                        DatePicker::make('start_date')
                            ->label('Date de début')
                            ->required(),
                        
                        DatePicker::make('end_date')
                            ->label('Date de fin')
                            ->required(),
                        
                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                'active' => 'Actif',
                                'completed' => 'Terminé',
                                'cancelled' => 'Annulé',
                            ])
                            ->default('active')
                            ->required(),
                    ])
                    ->columns(3),
            ]);
    }
}
