<?php

namespace App\Filament\Resources\StockMovements\Schemas;

use App\Models\PurchaseOrder;
use App\Models\StockItem;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StockMovementForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations du Mouvement')
                    ->description('Configurez les informations du mouvement de stock')
                    ->schema([
                        Select::make('stock_item_id')
                            ->label('Article')
                            ->relationship('stockItem', 'name')
                            ->getOptionLabelFromRecordUsing(fn ($record) => $record->name . ' (' . $record->sku . ')')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $stockItem = StockItem::find($state);
                                    if ($stockItem) {
                                        $set('unit_cost', $stockItem->unit_cost);
                                    }
                                }
                            }),
                        
                        Select::make('type')
                            ->label('Type de mouvement')
                            ->options([
                                'in' => 'Entrée',
                                'out' => 'Sortie',
                                'transfer' => 'Transfert',
                                'adjustment' => 'Ajustement',
                            ])
                            ->required()
                            ->reactive()
                            ->helperText('Type de mouvement de stock'),
                        
                        TextInput::make('quantity')
                            ->label('Quantité')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->helperText('Quantité à déplacer'),
                        
                        TextInput::make('unit_cost')
                            ->label('Coût unitaire')
                            ->numeric()
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Coût unitaire de l\'article'),
                        
                        Textarea::make('reason')
                            ->label('Raison')
                            ->rows(3)
                            ->placeholder('Raison du mouvement de stock')
                            ->required(),
                        
                        TextInput::make('reference_number')
                            ->label('Numéro de référence')
                            ->maxLength(255)
                            ->placeholder('Ex: REF-001, INVOICE-123')
                            ->helperText('Numéro de référence (facture, bon de livraison, etc.)'),
                        
                        Select::make('purchase_order_id')
                            ->label('Bon de commande')
                            ->relationship('purchaseOrder', 'po_number')
                            ->searchable()
                            ->preload()
                            ->helperText('Bon de commande associé (pour les entrées)')
                            ->visible(fn ($get) => $get('type') === 'in'),
                    ])
                    ->columns(2),
            ]);
    }
}