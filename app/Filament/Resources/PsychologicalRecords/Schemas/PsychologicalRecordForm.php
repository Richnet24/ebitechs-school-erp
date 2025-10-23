<?php

namespace App\Filament\Resources\PsychologicalRecords\Schemas;

use App\Models\Student;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PsychologicalRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de la Session Psychologique')
                    ->description('Configurez les informations de la session psychologique')
                    ->schema([
                        Select::make('student_id')
                            ->label('Élève')
                            ->relationship('student', 'student_number')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name . ' (' . $record->student_number . ')')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        DatePicker::make('session_date')
                            ->label('Date de la session')
                            ->required()
                            ->default(now()),
                        
                        Select::make('type')
                            ->label('Type de session')
                            ->options([
                                'assessment' => 'Évaluation psychologique',
                                'counseling' => 'Conseil psychologique',
                                'therapy' => 'Thérapie',
                                'evaluation' => 'Évaluation comportementale',
                                'follow_up' => 'Suivi',
                            ])
                            ->required()
                            ->default('assessment'),
                        
                        Select::make('psychologist_id')
                            ->label('Psychologue')
                            ->relationship('psychologist', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->helperText('Psychologue responsable de la session'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->placeholder('Description de la session et du contexte'),
                        
                        Textarea::make('observations')
                            ->label('Observations')
                            ->rows(4)
                            ->placeholder('Observations psychologiques et comportementales'),
                        
                        Textarea::make('recommendations')
                            ->label('Recommandations')
                            ->rows(4)
                            ->placeholder('Recommandations pour l\'élève, les parents ou l\'école'),
                        
                        Textarea::make('follow_up_actions')
                            ->label('Actions de suivi')
                            ->rows(3)
                            ->placeholder('Actions à entreprendre pour le suivi'),
                        
                        Textarea::make('notes')
                            ->label('Notes additionnelles')
                            ->rows(3)
                            ->placeholder('Notes confidentielles additionnelles'),
                    ])
                    ->columns(2),
            ]);
    }
}