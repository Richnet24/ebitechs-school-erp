<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student_number')
                    ->label('Numéro d\'étudiant')
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
                
                TextColumn::make('class.name')
                    ->label('Classe')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('class.branch.name')
                    ->label('Filière')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('parent.name')
                    ->label('Parent/Tuteur')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('admission_date')
                    ->label('Date d\'admission')
                    ->date()
                    ->sortable(),
                
                TextColumn::make('birth_date')
                    ->label('Date de naissance')
                    ->date()
                    ->sortable(),
                
                TextColumn::make('age')
                    ->label('Âge')
                    ->getStateUsing(function ($record) {
                        return $record->birth_date ? $record->birth_date->age . ' ans' : 'N/A';
                    }),
                
                TextColumn::make('nationality')
                    ->label('Nationalité')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'gray',
                        'graduated' => 'info',
                        'transferred' => 'warning',
                        'suspended' => 'danger',
                        default => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Actif',
                        'inactive' => 'Inactif',
                        'graduated' => 'Diplômé',
                        'transferred' => 'Transféré',
                        'suspended' => 'Suspendu',
                        default => $state,
                    }),
                
                TextColumn::make('blood_type')
                    ->label('Groupe sanguin')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('class_id')
                    ->label('Classe')
                    ->relationship('class', 'name')
                    ->searchable()
                    ->preload(),
                
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'active' => 'Actif',
                        'inactive' => 'Inactif',
                        'graduated' => 'Diplômé',
                        'transferred' => 'Transféré',
                        'suspended' => 'Suspendu',
                    ]),
                
                SelectFilter::make('nationality')
                    ->label('Nationalité')
                    ->options([
                        'CD' => 'Congo Démocratique',
                        'RW' => 'Rwanda',
                        'UG' => 'Ouganda',
                        'TZ' => 'Tanzanie',
                        'KE' => 'Kenya',
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
