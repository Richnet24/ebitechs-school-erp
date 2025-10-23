<?php

namespace App\Filament\Resources\Requisitions\Tables;

use App\Filament\Actions\ApproveRequisitionAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class RequisitionsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('requisition_number')
                    ->label('Numéro')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                TextColumn::make('estimated_cost')
                    ->label('Coût estimé')
                    ->money('USD')
                    ->sortable(),
                
                TextColumn::make('required_date')
                    ->label('Date requise')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->required_date < now() ? 'danger' : 'success'),
                
                TextColumn::make('priority')
                    ->label('Priorité')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'gray',
                        'medium' => 'info',
                        'high' => 'warning',
                        'urgent' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'low' => 'Faible',
                        'medium' => 'Moyenne',
                        'high' => 'Élevée',
                        'urgent' => 'Urgente',
                    }),
                
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'submitted' => 'info',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'completed' => 'primary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Brouillon',
                        'submitted' => 'Soumise',
                        'approved' => 'Approuvée',
                        'rejected' => 'Rejetée',
                        'completed' => 'Terminée',
                    }),
                
                TextColumn::make('requester.name')
                    ->label('Demandeur')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('approver.name')
                    ->label('Approbateur')
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
                        'submitted' => 'Soumise',
                        'approved' => 'Approuvée',
                        'rejected' => 'Rejetée',
                        'completed' => 'Terminée',
                    ]),
                
                SelectFilter::make('priority')
                    ->label('Priorité')
                    ->options([
                        'low' => 'Faible',
                        'medium' => 'Moyenne',
                        'high' => 'Élevée',
                        'urgent' => 'Urgente',
                    ]),
                
                SelectFilter::make('requested_by')
                    ->label('Demandeur')
                    ->relationship('requester', 'name')
                    ->searchable()
                    ->preload(),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                ApproveRequisitionAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}