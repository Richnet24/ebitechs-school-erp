<?php

namespace App\Filament\Resources\Payments\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PaymentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('payment_number')
                    ->label('Numéro')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('invoice.invoice_number')
                    ->label('Facture')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('invoice.student.user.name')
                    ->label('Élève')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('invoice.student.student_number')
                    ->label('N° Étudiant')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('amount')
                    ->label('Montant')
                    ->money('USD')
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('payment_date')
                    ->label('Date de paiement')
                    ->date('d/m/Y')
                    ->sortable(),
                
                TextColumn::make('payment_method')
                    ->label('Méthode')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'cash' => 'success',
                        'bank_transfer' => 'info',
                        'check' => 'warning',
                        'mobile_money' => 'primary',
                        'card' => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'cash' => 'Espèces',
                        'bank_transfer' => 'Virement',
                        'check' => 'Chèque',
                        'mobile_money' => 'Mobile Money',
                        'card' => 'Carte',
                    }),
                
                TextColumn::make('reference_number')
                    ->label('Référence')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('received_by.name')
                    ->label('Reçu par')
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
                SelectFilter::make('payment_method')
                    ->label('Méthode de paiement')
                    ->options([
                        'cash' => 'Espèces',
                        'bank_transfer' => 'Virement bancaire',
                        'check' => 'Chèque',
                        'mobile_money' => 'Mobile Money',
                        'card' => 'Carte bancaire',
                    ]),
                
                SelectFilter::make('invoice_id')
                    ->label('Facture')
                    ->relationship('invoice', 'invoice_number')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('received_by')
                    ->label('Reçu par')
                    ->relationship('receiver', 'name')
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