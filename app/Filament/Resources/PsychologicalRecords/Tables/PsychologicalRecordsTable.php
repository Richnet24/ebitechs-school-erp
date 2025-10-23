<?php

namespace App\Filament\Resources\PsychologicalRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PsychologicalRecordsTable
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
                
                TextColumn::make('session_date')
                    ->label('Date de session')
                    ->date('d/m/Y')
                    ->sortable(),
                
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'assessment' => 'info',
                        'counseling' => 'success',
                        'therapy' => 'primary',
                        'evaluation' => 'warning',
                        'follow_up' => 'secondary',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'assessment' => 'Évaluation',
                        'counseling' => 'Conseil',
                        'therapy' => 'Thérapie',
                        'evaluation' => 'Évaluation comportementale',
                        'follow_up' => 'Suivi',
                    }),
                
                TextColumn::make('psychologist.name')
                    ->label('Psychologue')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                TextColumn::make('observations')
                    ->label('Observations')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->toggleable(),
                
                TextColumn::make('recommendations')
                    ->label('Recommandations')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->toggleable(),
                
                TextColumn::make('follow_up_actions')
                    ->label('Actions de suivi')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->toggleable(),
                
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type de session')
                    ->options([
                        'assessment' => 'Évaluation psychologique',
                        'counseling' => 'Conseil psychologique',
                        'therapy' => 'Thérapie',
                        'evaluation' => 'Évaluation comportementale',
                        'follow_up' => 'Suivi',
                    ]),
                
                SelectFilter::make('student_id')
                    ->label('Élève')
                    ->relationship('student', 'student_number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name . ' (' . $record->student_number . ')')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('psychologist_id')
                    ->label('Psychologue')
                    ->relationship('psychologist', 'name')
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
            ->defaultSort('session_date', 'desc');
    }
}