<?php

namespace App\Filament\Resources\PurchaseOrders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PurchaseOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('po_number')
                    ->label('Numéro')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('requisition.requisition_number')
                    ->label('Réquisition')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('supplier_name')
                    ->label('Fournisseur')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('supplier_contact')
                    ->label('Contact')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                TextColumn::make('total_amount')
                    ->label('Montant total')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('order_date')
                    ->label('Date de commande')
                    ->date('d/m/Y')
                    ->sortable(),
                
                TextColumn::make('expected_delivery_date')
                    ->label('Livraison prévue')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->expected_delivery_date && $record->expected_delivery_date < now() ? 'danger' : 'success'),
                
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'info',
                        'confirmed' => 'warning',
                        'delivered' => 'success',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Brouillon',
                        'sent' => 'Envoyé',
                        'confirmed' => 'Confirmé',
                        'delivered' => 'Livré',
                        'cancelled' => 'Annulé',
                    }),
                
                TextColumn::make('creator.name')
                    ->label('Créé par')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('approver.name')
                    ->label('Approuvé par')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('approved_at')
                    ->label('Date d\'approbation')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'draft' => 'Brouillon',
                        'sent' => 'Envoyé',
                        'confirmed' => 'Confirmé',
                        'delivered' => 'Livré',
                        'cancelled' => 'Annulé',
                    ]),
                
                SelectFilter::make('requisition_id')
                    ->label('Réquisition')
                    ->relationship('requisition', 'requisition_number')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('created_by')
                    ->label('Créé par')
                    ->relationship('creator', 'name')
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