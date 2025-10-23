<?php

namespace App\Filament\Resources\StockItems\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class StockItemsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('category')
                    ->label('Catégorie')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'electronics', 'electronique' => 'info',
                        'furniture', 'mobilier' => 'warning',
                        'office_supplies', 'fournitures' => 'success',
                        'equipment', 'equipement' => 'primary',
                        'books', 'livres' => 'secondary',
                        'other', 'autre' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'electronics', 'electronique' => 'Électronique',
                        'furniture', 'mobilier' => 'Mobilier',
                        'office_supplies', 'fournitures' => 'Fournitures',
                        'equipment', 'equipement' => 'Équipement',
                        'books', 'livres' => 'Livres',
                        'other', 'autre' => 'Autre',
                        default => ucfirst($state),
                    }),
                
                TextColumn::make('current_stock')
                    ->label('Stock actuel')
                    ->numeric()
                    ->sortable()
                    ->color(fn ($record) => $record->is_low_stock ? 'danger' : 'success'),
                
                TextColumn::make('minimum_stock')
                    ->label('Stock min')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('maximum_stock')
                    ->label('Stock max')
                    ->numeric()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('unit')
                    ->label('Unité')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('unit_cost')
                    ->label('Coût unitaire')
                    ->money('USD')
                    ->sortable(),
                
                TextColumn::make('selling_price')
                    ->label('Prix de vente')
                    ->money('USD')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('stock_value')
                    ->label('Valeur stock')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('location')
                    ->label('Emplacement')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                IconColumn::make('is_active')
                    ->label('Statut')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-circle')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('category')
                    ->label('Catégorie')
                    ->options([
                        'electronics' => 'Électronique',
                        'electronique' => 'Électronique',
                        'furniture' => 'Mobilier',
                        'mobilier' => 'Mobilier',
                        'office_supplies' => 'Fournitures de bureau',
                        'fournitures' => 'Fournitures de bureau',
                        'equipment' => 'Équipement',
                        'equipement' => 'Équipement',
                        'books' => 'Livres',
                        'livres' => 'Livres',
                        'other' => 'Autre',
                        'autre' => 'Autre',
                    ]),
                
                TernaryFilter::make('is_active')
                    ->label('Article actif')
                    ->placeholder('Tous les articles')
                    ->trueLabel('Articles actifs')
                    ->falseLabel('Articles inactifs'),
                
                TernaryFilter::make('is_low_stock')
                    ->label('Stock bas')
                    ->placeholder('Tous les niveaux')
                    ->trueLabel('Stock bas seulement')
                    ->falseLabel('Stock normal'),
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
            ->defaultSort('name');
    }
}