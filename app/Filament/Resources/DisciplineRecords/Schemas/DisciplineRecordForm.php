<?php

namespace App\Filament\Resources\DisciplineRecords\Schemas;

use App\Models\Student;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class DisciplineRecordForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de l\'Incident')
                    ->description('Enregistrez les détails de l\'incident disciplinaire')
                    ->schema([
                        Select::make('student_id')
                            ->label('Élève')
                            ->relationship('student', 'student_number')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name . ' (' . $record->student_number . ')')
                            ->searchable()
                            ->preload()
                            ->required(),
                        
                        DatePicker::make('incident_date')
                            ->label('Date de l\'incident')
                            ->required()
                            ->default(now()),
                        
                        Select::make('type')
                            ->label('Type d\'incident')
                            ->options([
                                'minor' => 'Mineur',
                                'major' => 'Majeur',
                                'serious' => 'Grave',
                            ])
                            ->required()
                            ->default('minor'),
                        
                        TextInput::make('title')
                            ->label('Titre de l\'incident')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Retard répété, Absence non justifiée, etc.'),
                        
                        Textarea::make('description')
                            ->label('Description détaillée')
                            ->required()
                            ->rows(4)
                            ->placeholder('Description complète de l\'incident'),
                        
                        Textarea::make('actions_taken')
                            ->label('Actions entreprises')
                            ->rows(3)
                            ->placeholder('Actions immédiates prises suite à l\'incident'),
                        
                        Textarea::make('consequences')
                            ->label('Conséquences')
                            ->rows(3)
                            ->placeholder('Conséquences appliquées à l\'élève'),
                        
                        Textarea::make('follow_up')
                            ->label('Suivi')
                            ->rows(3)
                            ->placeholder('Actions de suivi prévues'),
                        
                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                'open' => 'Ouvert',
                                'resolved' => 'Résolu',
                                'escalated' => 'Escaladé',
                            ])
                            ->default('open')
                            ->required(),
                        
                        Select::make('reported_by')
                            ->label('Signalé par')
                            ->relationship('reporter', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(auth()->id()),
                        
                        Select::make('handled_by')
                            ->label('Traité par')
                            ->relationship('handler', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Personne responsable du traitement de l\'incident'),
                    ])
                    ->columns(2),
            ]);
    }
}