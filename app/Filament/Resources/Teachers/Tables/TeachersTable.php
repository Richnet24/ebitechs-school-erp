<?php

namespace App\Filament\Resources\Teachers\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TeachersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('employee_number')
                    ->label('Numéro d\'employé')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('user.name')
                    ->label('Nom')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('specialization')
                    ->label('Spécialisation')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('qualification')
                    ->label('Qualification')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('employment_type')
                    ->label('Type d\'emploi')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'full_time' => 'success',
                        'part_time' => 'warning',
                        'contract' => 'info',
                        'substitute' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'full_time' => 'Temps plein',
                        'part_time' => 'Temps partiel',
                        'contract' => 'Contrat',
                        'substitute' => 'Suppléant',
                    }),
                
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'on_leave' => 'warning',
                        'terminated' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Actif',
                        'inactive' => 'Inactif',
                        'on_leave' => 'En congé',
                        'terminated' => 'Terminé',
                    }),
                
                TextColumn::make('hire_date')
                    ->label('Date d\'embauche')
                    ->date()
                    ->sortable(),
                
                TextColumn::make('salary')
                    ->label('Salaire')
                    ->money('USD')
                    ->sortable(),
                
                TextColumn::make('courses_count')
                    ->label('Cours')
                    ->counts('courses'),
                
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('employment_type')
                    ->label('Type d\'emploi')
                    ->options([
                        'full_time' => 'Temps plein',
                        'part_time' => 'Temps partiel',
                        'contract' => 'Contrat',
                        'substitute' => 'Suppléant',
                    ]),
                
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'active' => 'Actif',
                        'inactive' => 'Inactif',
                        'on_leave' => 'En congé',
                        'terminated' => 'Terminé',
                    ]),
                
                TernaryFilter::make('is_active')
                    ->label('Actif')
                    ->placeholder('Tous')
                    ->trueLabel('Actifs seulement')
                    ->falseLabel('Inactifs seulement'),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
