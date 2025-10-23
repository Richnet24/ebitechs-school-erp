<?php

namespace App\Filament\Resources\Budgets\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class BudgetsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('category')
                    ->label('Catégorie')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'academic', 'Academic' => 'success',
                        'administrative', 'Administrative' => 'info',
                        'infrastructure', 'Infrastructure' => 'warning',
                        'maintenance', 'Maintenance' => 'primary',
                        'equipment', 'Equipment' => 'secondary',
                        'personnel', 'Personnel' => 'danger',
                        'other', 'Other' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'academic', 'Academic' => 'Académique',
                        'administrative', 'Administrative' => 'Administratif',
                        'infrastructure', 'Infrastructure' => 'Infrastructure',
                        'maintenance', 'Maintenance' => 'Maintenance',
                        'equipment', 'Equipment' => 'Équipement',
                        'personnel', 'Personnel' => 'Personnel',
                        'other', 'Other' => 'Autre',
                        default => $state,
                    }),
                
                TextColumn::make('fiscal_year')
                    ->label('Année fiscale')
                    ->sortable(),
                
                TextColumn::make('allocated_amount')
                    ->label('Alloué')
                    ->money('USD')
                    ->sortable(),
                
                TextColumn::make('spent_amount')
                    ->label('Dépensé')
                    ->money('USD')
                    ->sortable(),
                
                TextColumn::make('remaining_amount')
                    ->label('Restant')
                    ->money('USD')
                    ->sortable()
                    ->color(fn ($record) => $record->remaining_amount < 0 ? 'danger' : 'success'),
                
                TextColumn::make('utilization_percentage')
                    ->label('Utilisation')
                    ->formatStateUsing(fn ($record) => $record->utilization_percentage . '%')
                    ->color(fn ($record) => match (true) {
                        $record->utilization_percentage >= 90 => 'danger',
                        $record->utilization_percentage >= 75 => 'warning',
                        default => 'success',
                    }),
                
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'approved' => 'info',
                        'active' => 'success',
                        'closed' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Brouillon',
                        'approved' => 'Approuvé',
                        'active' => 'Actif',
                        'closed' => 'Fermé',
                    }),
                
                TextColumn::make('creator.name')
                    ->label('Créé par')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('approved_by.name')
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
                SelectFilter::make('category')
                    ->label('Catégorie')
                    ->options([
                        'academic' => 'Académique',
                        'administrative' => 'Administratif',
                        'infrastructure' => 'Infrastructure',
                        'maintenance' => 'Maintenance',
                        'equipment' => 'Équipement',
                        'personnel' => 'Personnel',
                        'other' => 'Autre',
                    ]),
                
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'draft' => 'Brouillon',
                        'approved' => 'Approuvé',
                        'active' => 'Actif',
                        'closed' => 'Fermé',
                    ]),
                
                SelectFilter::make('fiscal_year')
                    ->label('Année fiscale')
                    ->options(function () {
                        $years = [];
                        for ($year = now()->year - 5; $year <= now()->year + 2; $year++) {
                            $years[$year] = $year;
                        }
                        return $years;
                    }),
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