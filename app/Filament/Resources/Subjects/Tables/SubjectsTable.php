<?php

namespace App\Filament\Resources\Subjects\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\ColorColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class SubjectsTable
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
                
                TextColumn::make('credits')
                    ->label('Crédits')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('info'),
                
                TextColumn::make('hours_per_week')
                    ->label('H/Semaine')
                    ->sortable()
                    ->alignCenter()
                    ->badge()
                    ->color('warning'),
                
                TextColumn::make('courses_count')
                    ->label('Cours')
                    ->counts('courses')
                    ->badge()
                    ->color('secondary'),
                
                ColorColumn::make('color')
                    ->label('Couleur'),
                
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
                
                TernaryFilter::make('is_active')
                    ->label('Matière active')
                    ->placeholder('Toutes les matières')
                    ->trueLabel('Matières actives')
                    ->falseLabel('Matières inactives'),
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
