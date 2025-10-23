<?php

namespace App\Filament\Resources\Expenses;

use App\Filament\Resources\Expenses\Pages\CreateExpense;
use App\Filament\Resources\Expenses\Pages\EditExpense;
use App\Filament\Resources\Expenses\Pages\ListExpenses;
use App\Models\Expense;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;

class ExpenseResource extends Resource
{
    protected static ?string $model = Expense::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;
    
    public static function getNavigationGroup(): ?string
    {
        return 'Module Financier (DAF)';
    }
    
    public static function getNavigationSort(): ?int
    {
        return 4;
    }

    public static function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                \Filament\Forms\Components\TextInput::make('title')
                    ->label('Titre de la dépense')
                    ->required()
                    ->maxLength(255),
                
                \Filament\Forms\Components\Textarea::make('description')
                    ->label('Description')
                    ->rows(3),
                
                \Filament\Forms\Components\Select::make('category')
                    ->label('Catégorie')
                    ->options([
                        'academic' => 'Académique',
                        'administrative' => 'Administratif',
                        'infrastructure' => 'Infrastructure',
                        'maintenance' => 'Maintenance',
                        'equipment' => 'Équipement',
                        'personnel' => 'Personnel',
                        'utilities' => 'Services publics',
                        'transport' => 'Transport',
                        'communication' => 'Communication',
                        'other' => 'Autre',
                    ])
                    ->required(),
                
                \Filament\Forms\Components\TextInput::make('amount')
                    ->label('Montant')
                    ->numeric()
                    ->required()
                    ->prefix('$')
                    ->step(0.01),
                
                \Filament\Forms\Components\Select::make('currency')
                    ->label('Devise')
                    ->options([
                        'USD' => 'USD - Dollar américain',
                        'EUR' => 'EUR - Euro',
                        'CDF' => 'CDF - Franc congolais',
                        'XAF' => 'XAF - Franc CFA',
                    ])
                    ->default('USD')
                    ->required(),
                
                \Filament\Forms\Components\DatePicker::make('expense_date')
                    ->label('Date de la dépense')
                    ->required()
                    ->default(now()),
                
                \Filament\Forms\Components\Select::make('status')
                    ->label('Statut')
                    ->options([
                        'draft' => 'Brouillon',
                        'pending_approval' => 'En attente d\'approbation',
                        'approved' => 'Approuvé',
                        'rejected' => 'Rejeté',
                        'paid' => 'Payé',
                        'cancelled' => 'Annulé',
                    ])
                    ->default('draft')
                    ->required(),

                \Filament\Forms\Components\Hidden::make('created_by')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(\Filament\Tables\Table $table): \Filament\Tables\Table
    {
        return $table
            ->columns([
                \Filament\Tables\Columns\TextColumn::make('expense_number')
                    ->label('N° Dépense')
                    ->searchable()
                    ->sortable(),
                
                \Filament\Tables\Columns\TextColumn::make('title')
                    ->label('Titre')
                    ->searchable()
                    ->sortable()
                    ->limit(50),
                
                \Filament\Tables\Columns\TextColumn::make('amount')
                    ->label('Montant')
                    ->money('USD')
                    ->sortable(),
                
                \Filament\Tables\Columns\TextColumn::make('expense_date')
                    ->label('Date')
                    ->date('d/m/Y')
                    ->sortable(),
                
                \Filament\Tables\Columns\TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'draft' => 'gray',
                        'pending_approval' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        'paid' => 'info',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
            ])
            ->filters([
                \Filament\Tables\Filters\SelectFilter::make('category')
                    ->label('Catégorie')
                    ->options([
                        'academic' => 'Académique',
                        'administrative' => 'Administratif',
                        'infrastructure' => 'Infrastructure',
                        'maintenance' => 'Maintenance',
                        'equipment' => 'Équipement',
                        'personnel' => 'Personnel',
                        'utilities' => 'Services publics',
                        'transport' => 'Transport',
                        'communication' => 'Communication',
                        'other' => 'Autre',
                    ]),
                
                \Filament\Tables\Filters\SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'draft' => 'Brouillon',
                        'pending_approval' => 'En attente d\'approbation',
                        'approved' => 'Approuvé',
                        'rejected' => 'Rejeté',
                        'paid' => 'Payé',
                        'cancelled' => 'Annulé',
                    ]),
            ])
            ->actions([
                \Filament\Actions\ViewAction::make(),
                \Filament\Actions\EditAction::make(),
            ])
            ->bulkActions([
                \Filament\Actions\BulkActionGroup::make([
                    \Filament\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('expense_date', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListExpenses::route('/'),
            'create' => CreateExpense::route('/create'),
            'edit' => EditExpense::route('/{record}/edit'),
        ];
    }

    public static function mutateFormDataBeforeCreate(array $data): array
    {
        $data['created_by'] = auth()->id();
        return $data;
    }
}
