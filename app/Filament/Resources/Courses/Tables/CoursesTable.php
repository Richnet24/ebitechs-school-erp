<?php

namespace App\Filament\Resources\Courses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CoursesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Nom du cours')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('subject.name')
                    ->label('Matière')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('subject.branch.name')
                    ->label('Filière')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('class.name')
                    ->label('Classe')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('teacher.name')
                    ->label('Enseignant')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('credits')
                    ->label('Crédits')
                    ->numeric()
                    ->sortable(),
                
                TextColumn::make('hours_per_week')
                    ->label('Heures/semaine')
                    ->numeric()
                    ->sortable(),
                
                TextColumn::make('start_date')
                    ->label('Début')
                    ->date()
                    ->sortable(),
                
                TextColumn::make('end_date')
                    ->label('Fin')
                    ->date()
                    ->sortable(),
                
                TextColumn::make('duration_weeks')
                    ->label('Durée')
                    ->getStateUsing(function ($record) {
                        if ($record->start_date && $record->end_date) {
                            $weeks = $record->start_date->diffInWeeks($record->end_date);
                            return $weeks . ' semaines';
                        }
                        return 'N/A';
                    }),
                
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'completed' => 'info',
                        'cancelled' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Actif',
                        'completed' => 'Terminé',
                        'cancelled' => 'Annulé',
                    }),
                
                TextColumn::make('students_count')
                    ->label('Élèves')
                    ->getStateUsing(function ($record) {
                        return $record->class->students()->count();
                    }),
                
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('subject_id')
                    ->label('Matière')
                    ->relationship('subject', 'name')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('class_id')
                    ->label('Classe')
                    ->relationship('class', 'name')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('teacher_id')
                    ->label('Enseignant')
                    ->relationship('teacher', 'name')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'active' => 'Actif',
                        'completed' => 'Terminé',
                        'cancelled' => 'Annulé',
                    ]),
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
