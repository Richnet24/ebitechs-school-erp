<?php

namespace App\Filament\Resources\StockMovements\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StockMovementsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('stockItem.name')
                    ->label('Article')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('stockItem.sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'in' => 'success',
                        'out' => 'danger',
                        'transfer' => 'info',
                        'adjustment' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'in' => 'Entrée',
                        'out' => 'Sortie',
                        'transfer' => 'Transfert',
                        'adjustment' => 'Ajustement',
                    }),
                
                TextColumn::make('quantity')
                    ->label('Quantité')
                    ->numeric()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('unit_cost')
                    ->label('Coût unitaire')
                    ->money('USD')
                    ->sortable(),
                
                TextColumn::make('total_value')
                    ->label('Valeur totale')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('reason')
                    ->label('Raison')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                TextColumn::make('reference_number')
                    ->label('Référence')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('purchaseOrder.po_number')
                    ->label('Bon de commande')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('processor.name')
                    ->label('Traité par')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Date')
                    ->dateTime('d/m/Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type de mouvement')
                    ->options([
                        'in' => 'Entrée',
                        'out' => 'Sortie',
                        'transfer' => 'Transfert',
                        'adjustment' => 'Ajustement',
                    ]),
                
                SelectFilter::make('stock_item_id')
                    ->label('Article')
                    ->relationship('stockItem', 'name')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('purchase_order_id')
                    ->label('Bon de commande')
                    ->relationship('purchaseOrder', 'po_number')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('processed_by')
                    ->label('Traité par')
                    ->relationship('processor', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}