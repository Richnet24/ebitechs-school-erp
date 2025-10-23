<?php

namespace App\Filament\Resources\Users\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class UsersTable
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
                
                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->sortable()
                    ->copyable()
                    ->copyMessage('Email copié')
                    ->copyMessageDuration(1500),
                
                TextColumn::make('phone')
                    ->label('Téléphone')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('gender')
                    ->label('Genre')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'male' => 'info',
                        'female' => 'warning',
                        'other' => 'gray',
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'male' => 'Masculin',
                        'female' => 'Féminin',
                        'other' => 'Autre',
                    })
                    ->toggleable(),
                
                TextColumn::make('date_of_birth')
                    ->label('Date de naissance')
                    ->date('d/m/Y')
                    ->sortable()
                    ->toggleable(),
                
                TextColumn::make('last_login_at')
                    ->label('Dernière connexion')
                    ->dateTime('d/m/Y H:i')
                    ->sortable()
                    ->toggleable(),
                
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
                SelectFilter::make('gender')
                    ->label('Genre')
                    ->options([
                        'male' => 'Masculin',
                        'female' => 'Féminin',
                        'other' => 'Autre',
                    ]),
                
                TernaryFilter::make('is_active')
                    ->label('Utilisateur actif')
                    ->placeholder('Tous les utilisateurs')
                    ->trueLabel('Utilisateurs actifs')
                    ->falseLabel('Utilisateurs inactifs'),
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
