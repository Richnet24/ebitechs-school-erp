<?php

namespace App\Filament\Resources\PurchaseOrders\Schemas;

use App\Models\Requisition;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PurchaseOrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations du Bon de Commande')
                    ->description('Configurez les informations de base du bon de commande')
                    ->schema([
                        TextInput::make('po_number')
                            ->label('Numéro de bon de commande')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Ex: PO-2024-001')
                            ->helperText('Numéro unique du bon de commande'),
                        
                        Select::make('requisition_id')
                            ->label('Réquisition')
                            ->relationship('requisition', 'requisition_number')
                            ->searchable()
                            ->preload()
                            ->helperText('Réquisition associée (optionnel)'),
                        
                        TextInput::make('supplier_name')
                            ->label('Nom du fournisseur')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('Ex: Fournisseur ABC'),
                        
                        TextInput::make('supplier_contact')
                            ->label('Contact du fournisseur')
                            ->maxLength(255)
                            ->placeholder('Ex: +243 123 456 789'),
                        
                        Textarea::make('description')
                            ->label('Description')
                            ->required()
                            ->rows(3)
                            ->placeholder('Description détaillée des articles commandés'),
                        
                        TextInput::make('total_amount')
                            ->label('Montant total')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Montant total de la commande'),
                        
                        DatePicker::make('order_date')
                            ->label('Date de commande')
                            ->required()
                            ->default(now()),
                        
                        DatePicker::make('expected_delivery_date')
                            ->label('Date de livraison prévue')
                            ->helperText('Date prévue de livraison'),
                        
                        Select::make('status')
                            ->label('Statut')
                            ->options([
                                'draft' => 'Brouillon',
                                'sent' => 'Envoyé',
                                'confirmed' => 'Confirmé',
                                'delivered' => 'Livré',
                                'cancelled' => 'Annulé',
                            ])
                            ->default('draft')
                            ->required(),
                        
                        Textarea::make('terms_conditions')
                            ->label('Termes et conditions')
                            ->rows(3)
                            ->placeholder('Termes et conditions de la commande'),
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
                            ->helperText('Utilisateur qui a approuvé le bon de commande'),
                        
                        DatePicker::make('approved_at')
                            ->label('Date d\'approbation')
                            ->helperText('Date d\'approbation du bon de commande'),
                    ])
                    ->columns(2)
                    ->visible(fn ($record) => $record && $record->status !== 'draft'),
            ]);
    }
}