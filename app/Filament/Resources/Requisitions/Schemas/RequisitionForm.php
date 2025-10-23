<?php

namespace App\Filament\Resources\Requisitions\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class RequisitionForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de la Réquisition')
                    ->description('Configurez les informations de base de la réquisition')
                    ->schema([
                        TextInput::make('requisition_number')
                            ->label('Numéro de réquisition')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Ex: REQ-2024-001')
                            ->helperText('Numéro unique de la réquisition'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->placeholder('Description détaillée des articles demandés'),
                        
                        Textarea::make('justification')
                            ->label('Justification')
                            ->required()
                            ->rows(3)
                            ->placeholder('Justification de la demande et besoins'),
                        
                        TextInput::make('estimated_cost')
                            ->label('Coût estimé')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Coût estimé de la réquisition'),
                        
                        DatePicker::make('required_date')
                            ->label('Date requise')
                            ->required()
                            ->default(now()->addDays(7))
                            ->helperText('Date limite pour la livraison'),
                        
                        Select::make('priority')
                            ->label('Priorité')
                            ->options([
                                'low' => 'Faible',
                                'medium' => 'Moyenne',
                                'high' => 'Élevée',
                                'urgent' => 'Urgente',
                            ])
                            ->default('medium')
                            ->required(),
                        
                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                'draft' => 'Brouillon',
                                'submitted' => 'Soumise',
                                'approved' => 'Approuvée',
                                'rejected' => 'Rejetée',
                                'completed' => 'Terminée',
                            ])
                            ->default('draft')
                            ->required(),
                    ])
                    ->columns(2),
                
                Section::make('Approbation')
                    ->description('Informations d\'approbation')
                    ->schema([
                        Select::make('approved_by')
                            ->label('Approuvé par')
                            ->relationship('approver', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Utilisateur qui a approuvé la réquisition'),
                        
                        DatePicker::make('approved_at')
                            ->label('Date d\'approbation')
                            ->helperText('Date d\'approbation de la réquisition'),
                        
                        Textarea::make('approval_notes')
                            ->label('Notes d\'approbation')
                            ->rows(3)
                            ->placeholder('Commentaires et notes d\'approbation'),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record && $record->status === 'approved'),
            ]);
    }
}