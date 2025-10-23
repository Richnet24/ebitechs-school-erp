<?php

namespace App\Filament\Resources\ClassModels\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class ClassModelsTable
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
                
                TextColumn::make('code')
                    ->label('Code')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('primary'),
                
                TextColumn::make('branch.name')
                    ->label('Filière')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->color('success'),
                
                TextColumn::make('level')
                    ->label('Niveau')
                    ->sortable()
                    ->badge()
                    ->color('info'),
                
                TextColumn::make('capacity')
                    ->label('Capacité')
                    ->sortable()
                    ->alignCenter(),
                
                TextColumn::make('students_count')
                    ->label('Élèves')
                    ->counts('students')
                    ->badge()
                    ->color('warning'),
                
                TextColumn::make('available_slots')
                    ->label('Places libres')
                    ->getStateUsing(function ($record) {
                        return $record->capacity - $record->students()->count();
                    })
                    ->badge()
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger'),
                
                TextColumn::make('teacher.name')
                    ->label('Professeur principal')
                    ->searchable()
                    ->placeholder('Non assigné')
                    ->badge()
                    ->color('secondary'),
                
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
                SelectFilter::make('branch_id')
                    ->label('Filière')
                    ->relationship('branch', 'name')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('level')
                    ->label('Niveau')
                    ->options([
                        1 => '1ère Année',
                        2 => '2ème Année',
                        3 => '3ème Année',
                        4 => '4ème Année',
                        5 => '5ème Année',
                    ]),
                
                TernaryFilter::make('is_active')
                    ->label('Classe active')
                    ->placeholder('Toutes les classes')
                    ->trueLabel('Classes actives')
                    ->falseLabel('Classes inactives'),
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
