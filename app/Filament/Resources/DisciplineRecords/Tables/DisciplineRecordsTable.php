<?php

namespace App\Filament\Resources\DisciplineRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DisciplineRecordsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.user.name')
                    ->label('Élève')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                
                TextColumn::make('student.student_number')
                    ->label('N° Étudiant')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('incident_date')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),
                
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'minor' => 'warning',
                        'major' => 'danger',
                        'serious' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'minor' => 'Mineur',
                        'major' => 'Majeur',
                        'serious' => 'Grave',
                    }),
                
                TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->toggleable(),
                
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'open' => 'warning',
                        'resolved' => 'success',
                        'escalated' => 'danger',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'open' => 'Ouvert',
                        'resolved' => 'Résolu',
                        'escalated' => 'Escaladé',
                    }),
                
                TextColumn::make('reporter.name')
                    ->label('Signalé par')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('handler.name')
                    ->label('Traité par')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('resolved_at')
                    ->label('Résolu le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type d\'incident')
                    ->options([
                        'minor' => 'Mineur',
                        'major' => 'Majeur',
                        'serious' => 'Grave',
                    ]),
                
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'open' => 'Ouvert',
                        'resolved' => 'Résolu',
                        'escalated' => 'Escaladé',
                    ]),
                
                SelectFilter::make('student_id')
                    ->label('Élève')
                    ->relationship('student', 'student_number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name . ' (' . $record->student_number . ')')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('reported_by')
                    ->label('Signalé par')
                    ->relationship('reporter', 'name')
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
            ->defaultSort('incident_date', 'desc');
    }
}