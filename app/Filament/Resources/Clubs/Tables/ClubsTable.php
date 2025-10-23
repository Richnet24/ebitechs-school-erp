<?php

namespace App\Filament\Resources\Clubs\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ClubsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom du club')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('category')
                    ->label('Catégorie')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'sport' => 'success',
                        'culture' => 'info',
                        'academic' => 'primary',
                        'académique' => 'primary',
                        'social' => 'warning',
                        'environment' => 'success',
                        'technology' => 'secondary',
                        'art' => 'danger',
                        'music' => 'gray',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'sport' => 'Sport',
                        'culture' => 'Culture',
                        'academic' => 'Académique',
                        'académique' => 'Académique',
                        'social' => 'Social',
                        'environment' => 'Environnement',
                        'technology' => 'Technologie',
                        'art' => 'Art',
                        'music' => 'Musique',
                        default => ucfirst($state),
                    }),
                
                TextColumn::make('supervisor.name')
                    ->label('Superviseur')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('members_count')
                    ->label('Membres')
                    ->getStateUsing(function ($record) {
                        return $record->activeMembers()->count() . '/' . $record->max_members;
                    }),
                
                TextColumn::make('meeting_schedule')
                    ->label('Horaire')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('location')
                    ->label('Lieu')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('is_active')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (bool $state): string => $state ? 'success' : 'danger')
                    ->formatStateUsing(fn (bool $state): string => $state ? 'Actif' : 'Inactif'),
                
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
                        'sport' => 'Sport',
                        'culture' => 'Culture',
                        'academic' => 'Académique',
                        'social' => 'Social',
                        'environment' => 'Environnement',
                        'technology' => 'Technologie',
                        'art' => 'Art',
                        'music' => 'Musique',
                    ]),
                
                SelectFilter::make('supervisor_id')
                    ->label('Superviseur')
                    ->relationship('supervisor', 'name')
                    ->searchable()
                    ->preload(),
                
                TernaryFilter::make('is_active')
                    ->label('Club actif')
                    ->placeholder('Tous les clubs')
                    ->trueLabel('Clubs actifs seulement')
                    ->falseLabel('Clubs inactifs seulement'),
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