<?php

namespace App\Filament\Widgets;

use App\Models\Student;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentActivitiesTable extends BaseWidget
{
    protected static ?string $heading = 'Élèves Récemment Inscrits';

    protected static ?int $sort = 5;

    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Student::query()
                    ->with(['user', 'class.branch'])
                    ->latest()
                    ->limit(10)
            )
            ->columns([
                TextColumn::make('student_number')
                    ->label('N° Étudiant')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('user.name')
                    ->label('Nom')
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
                
                TextColumn::make('admission_date')
                    ->label('Date d\'admission')
                    ->date('d/m/Y')
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
                    })
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'active' => 'Actif',
                        'inactive' => 'Inactif',
                        'graduated' => 'Diplômé',
                        'transferred' => 'Transféré',
                        'suspended' => 'Suspendu',
                    }),
            ])
            ->paginated(false);
    }
}
