<?php

namespace App\Filament\Resources\HealthRecords\Schemas;

use App\Models\Student;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class HealthRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations du Dossier Médical')
                    ->description('Configurez les informations du dossier médical de l\'élève')
                    ->schema([
                        Select::make('student_id')
                            ->label('Élève')
                            ->relationship('student', 'student_number')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name . ' (' . $record->student_number . ')')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        DatePicker::make('record_date')
                            ->label('Date du dossier')
                            ->required()
                            ->default(now()),
                        
                        Select::make('type')
                            ->label('Type de consultation')
                            ->options([
                                'consultation' => 'Consultation générale',
                                'vaccination' => 'Vaccination',
                                'medical_checkup' => 'Bilan médical',
                                'emergency' => 'Urgence médicale',
                                'medication' => 'Prescription médicamenteuse',
                            ])
                            ->required()
                            ->default('consultation'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->placeholder('Description détaillée de la consultation ou du problème médical'),
                        
                        Textarea::make('diagnosis')
                            ->label('Diagnostic')
                            ->rows(3)
                            ->placeholder('Diagnostic médical établi'),
                        
                        Textarea::make('treatment')
                            ->label('Traitement')
                            ->rows(3)
                            ->placeholder('Traitement prescrit ou recommandé'),
                        
                        Textarea::make('medication')
                            ->label('Médicaments')
                            ->rows(3)
                            ->placeholder('Médicaments prescrits avec posologie'),
                        
                        Textarea::make('notes')
                            ->label('Notes additionnelles')
                            ->rows(3)
                            ->placeholder('Notes médicales additionnelles'),
                    ])
                    ->columns(2),
            ]);
    }
}