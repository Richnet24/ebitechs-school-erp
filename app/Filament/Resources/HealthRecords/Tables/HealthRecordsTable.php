<?php

namespace App\Filament\Resources\HealthRecords\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class HealthRecordsTable
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
                
                TextColumn::make('record_date')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),
                
                TextColumn::make('type')
                    ->label('Type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'consultation' => 'info',
                        'vaccination' => 'success',
                        'medical_checkup' => 'primary',
                        'emergency' => 'danger',
                        'medication' => 'warning',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'consultation' => 'Consultation',
                        'vaccination' => 'Vaccination',
                        'medical_checkup' => 'Bilan médical',
                        'emergency' => 'Urgence',
                        'medication' => 'Médicament',
                    }),
                
                TextColumn::make('description')
                    ->label('Description')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                TextColumn::make('diagnosis')
                    ->label('Diagnostic')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->toggleable(),
                
                TextColumn::make('treatment')
                    ->label('Traitement')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->toggleable(),
                
                TextColumn::make('medication')
                    ->label('Médicaments')
                    ->searchable()
                    ->sortable()
                    ->limit(50)
                    ->toggleable(),
                
                TextColumn::make('recorder.name')
                    ->label('Enregistré par')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Type de consultation')
                    ->options([
                        'consultation' => 'Consultation générale',
                        'vaccination' => 'Vaccination',
                        'medical_checkup' => 'Bilan médical',
                        'emergency' => 'Urgence médicale',
                        'medication' => 'Prescription médicamenteuse',
                    ]),
                
                SelectFilter::make('student_id')
                    ->label('Élève')
                    ->relationship('student', 'student_number')
                    ->getOptionLabelFromRecordUsing(fn ($record) => $record->user->name . ' (' . $record->student_number . ')')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('recorded_by')
                    ->label('Enregistré par')
                    ->relationship('recorder', 'name')
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
            ->defaultSort('record_date', 'desc');
    }
}