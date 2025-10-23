<?php

namespace App\Filament\Resources\StockItems\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StockItemForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations de l\'Article')
                    ->description('Configurez les informations de base de l\'article en stock')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nom de l\'article')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Ordinateur portable Dell'),
                        
                        TextInput::make('sku')
                            ->label('SKU (Code produit)')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Ex: DELL-LAT-001')
                            ->helperText('Code unique de l\'article'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->rows(3)
                            ->placeholder('Description détaillée de l\'article'),
                        
                        Select::make('category')
                            ->label('Catégorie')
                            ->options([
                                'electronics' => 'Électronique',
                                'furniture' => 'Mobilier',
                                'office_supplies' => 'Fournitures de bureau',
                                'equipment' => 'Équipement',
                                'books' => 'Livres',
                                'other' => 'Autre',
                            ])
                            ->searchable()
                            ->helperText('Catégorie de l\'article'),
                        
                        TextInput::make('unit')
                            ->label('Unité')
                            ->default('piece')
                            ->maxLength(255)
                            ->placeholder('Ex: piece, kg, liter, etc.')
                            ->helperText('Unité de mesure'),
                    ])
                    ->columns(2),
                
                Section::make('Gestion du Stock')
                    ->description('Configurez les niveaux de stock et les coûts')
                    ->schema([
                        TextInput::make('current_stock')
                            ->label('Stock actuel')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Quantité actuelle en stock'),
                        
                        TextInput::make('minimum_stock')
                            ->label('Stock minimum')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->helperText('Seuil d\'alerte de stock bas'),
                        
                        TextInput::make('maximum_stock')
                            ->label('Stock maximum')
                            ->numeric()
                            ->minValue(0)
                            ->helperText('Capacité maximale de stock'),
                        
                        TextInput::make('unit_cost')
                            ->label('Coût unitaire')
                            ->numeric()
                            ->default(0)
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Coût d\'achat unitaire'),
                        
                        TextInput::make('selling_price')
                            ->label('Prix de vente')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Prix de vente unitaire (optionnel)'),
                        
                        TextInput::make('location')
                            ->label('Emplacement')
                            ->maxLength(255)
                            ->placeholder('Ex: Entrepôt A, Étagère 1')
                            ->helperText('Emplacement physique de l\'article'),
                    ])
                    ->columns(3),
                
                Section::make('Informations Supplémentaires')
                    ->description('Notes et statut')
                    ->schema([
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Notes additionnelles sur l\'article'),
                        
                        Toggle::make('is_active')
                            ->label('Article actif')
                            ->default(true)
                            ->helperText('Désactiver pour masquer cet article'),
                    ])
                    ->columns(2),
            ]);
    }
}