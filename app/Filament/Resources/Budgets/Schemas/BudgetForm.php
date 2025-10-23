<?php

namespace App\Filament\Resources\Budgets\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BudgetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations du Budget')
                    ->description('Configurez les informations de base du budget')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom du budget')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Budget Académique 2024'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Description détaillée du budget'),
                        
                        Select::make('category')
                            ->label('Catégorie')
                            ->options([
                                'academic' => 'Académique',
                                'administrative' => 'Administratif',
                                'infrastructure' => 'Infrastructure',
                                'maintenance' => 'Maintenance',
                                'equipment' => 'Équipement',
                                'personnel' => 'Personnel',
                                'other' => 'Autre',
                            ])
                            ->required()
                            ->searchable(),
                        
                        TextInput::make('fiscal_year')
                            ->label('Année fiscale')
                            ->numeric()
                            ->required()
                            ->default(now()->year)
                            ->minValue(2020)
                            ->maxValue(2030)
                            ->helperText('Année fiscale du budget'),
                    ])
                    ->columns(2),
                
                Section::make('Montants et Statut')
                    ->description('Configurez les montants et le statut du budget')
                    ->schema([
                        TextInput::make('allocated_amount')
                            ->label('Montant alloué')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Montant total alloué au budget'),
                        
                        TextInput::make('spent_amount')
                            ->label('Montant dépensé')
                            ->numeric()
                            ->default(0)
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Montant déjà dépensé'),
                        
                        TextInput::make('remaining_amount')
                            ->label('Montant restant')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Montant restant disponible'),
                        
                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                'draft' => 'Brouillon',
                                'approved' => 'Approuvé',
                                'active' => 'Actif',
                                'closed' => 'Fermé',
                            ])
                            ->default('draft')
                            ->required(),
                        
                        Select::make('approved_by')
                            ->label('Approuvé par')
                            ->relationship('approver', 'name')
                            ->searchable()
                            ->preload()
                            ->helperText('Utilisateur qui a approuvé le budget'),
                        
                        DatePicker::make('approved_at')
                            ->label('Date d\'approbation')
                            ->helperText('Date d\'approbation du budget'),
                    ])
                    ->columns(3),
            ]);
    }
}