<?php

namespace App\Filament\Resources\Invoices\Tables;

use App\Filament\Actions\SendInvoiceAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class InvoicesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('invoice_number')
                    ->label('Numéro')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('student.user.name')
                    ->label('Élève')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('student.student_number')
                    ->label('N° Étudiant')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                TextColumn::make('amount')
                    ->label('Montant HT')
                    ->money('USD')
                    ->sortable(),
                
                TextColumn::make('tax_amount')
                    ->label('Taxes')
                    ->money('USD')
                    ->sortable(),
                
                TextColumn::make('total_amount')
                    ->label('Total')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('invoice_date')
                    ->label('Date facture')
                    ->date('d/m/Y')
                    ->sortable(),
                
                TextColumn::make('due_date')
                    ->label('Échéance')
                    ->date('d/m/Y')
                    ->sortable()
                    ->color(fn ($record) => $record->due_date < now() ? 'danger' : 'success'),
                
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'sent' => 'warning',
                        'paid' => 'success',
                        'overdue' => 'danger',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'draft' => 'Brouillon',
                        'sent' => 'Envoyée',
                        'paid' => 'Payée',
                        'overdue' => 'En retard',
                        'cancelled' => 'Annulée',
                        default => $state,
                    }),
                
                TextColumn::make('paid_amount')
                    ->label('Payé')
                    ->money('USD')
                    ->sortable(),
                
                TextColumn::make('remaining_amount')
                    ->label('Restant')
                    ->money('USD')
                    ->sortable()
                    ->color(fn ($record) => $record->remaining_amount > 0 ? 'warning' : 'success'),
                
                TextColumn::make('creator.name')
                    ->label('Créé par')
                    ->searchable()
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
                        'sent' => 'Envoyée',
                        'paid' => 'Payée',
                        'overdue' => 'En retard',
                        'cancelled' => 'Annulée',
                    ]),
                
                SelectFilter::make('student_id')
                    ->label('Élève')
                    ->relationship('student', 'student_number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name . ' (' . $record->student_number . ')')
                    ->searchable()
                    ->preload(),
                
                TernaryFilter::make('is_overdue')
                    ->label('Factures en retard')
                    ->placeholder('Toutes les factures')
                    ->trueLabel('En retard seulement')
                    ->falseLabel('À jour seulement')
                    ->queries(
                        true: fn ($query) => $query->where('due_date', '<', now())->where('status', '!=', 'paid'),
                        false: fn ($query) => $query->where('due_date', '>=', now())->orWhere('status', 'paid'),
                    ),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                SendInvoiceAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}