<?php

namespace App\Filament\Resources\Invoices\Schemas;

use App\Models\Student;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class InvoiceForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de la Facture')
                    ->description('Configurez les informations de base de la facture')
                    ->schema([
                        TextInput::make('invoice_number')
                            ->label('Numéro de facture')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Ex: FACT-2024-001')
                            ->helperText('Numéro unique de la facture'),
                        
                        Select::make('student_id')
                            ->label('Élève')
                            ->relationship('student', 'student_number')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name . ' (' . $record->student_number . ')')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('student_number')
                                    ->required()
                                    ->unique()
                                    ->maxLength(255),
                                Select::make('user_id')
                                    ->relationship('user', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                Select::make('class_id')
                                    ->relationship('class', 'name')
                                    ->searchable()
                                    ->preload()
                                    ->required(),
                                DatePicker::make('admission_date')
                                    ->required(),
                                DatePicker::make('birth_date')
                                    ->required(),
                            ]),
                        
                        TextInput::make('description')
                            ->label('Description')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Frais de scolarité - Trimestre 1'),
                        
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Notes additionnelles sur la facture'),
                    ])
                    ->columns(2),
                
                Section::make('Montants et Dates')
                    ->description('Configurez les montants et les dates importantes')
                    ->schema([
                        TextInput::make('amount')
                            ->label('Montant HT')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Montant hors taxes'),
                        
                        TextInput::make('tax_amount')
                            ->label('Montant des taxes')
                            ->numeric()
                            ->default(0)
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Montant des taxes applicables'),
                        
                        TextInput::make('total_amount')
                            ->label('Montant total')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Montant total TTC'),
                        
                        DatePicker::make('invoice_date')
                            ->label('Date de facture')
                            ->required()
                            ->default(now()),
                        
                        DatePicker::make('due_date')
                            ->label('Date d\'échéance')
                            ->required()
                            ->default(now()->addDays(30))
                            ->helperText('Date limite de paiement'),
                        
                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                'draft' => 'Brouillon',
                                'sent' => 'Envoyée',
                                'paid' => 'Payée',
                                'overdue' => 'En retard',
                                'cancelled' => 'Annulée',
                            ])
                            ->default('draft')
                            ->required(),
                    ])
                    ->columns(3),
                
                // Champ caché pour l'utilisateur créateur
                Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }
}