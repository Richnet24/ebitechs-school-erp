<?php

namespace App\Filament\Actions;

use App\Models\Requisition;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;

class ApproveRequisitionAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'approve_requisition';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Approuver la réquisition')
            ->icon('heroicon-o-check-circle')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Approuver la réquisition')
            ->modalDescription('Êtes-vous sûr de vouloir approuver cette réquisition ?')
            ->modalSubmitActionLabel('Approuver')
            ->form([
                Textarea::make('approval_notes')
                    ->label('Notes d\'approbation')
                    ->placeholder('Commentaires sur l\'approbation...')
                    ->rows(3),
            ])
            ->action(function (Requisition $record, array $data) {
                $record->update([
                    'status' => 'approved',
                    'approved_by' => auth()->id(),
                    'approved_at' => now(),
                    'approval_notes' => $data['approval_notes'] ?? null,
                ]);
                
                Notification::make()
                    ->title('Réquisition approuvée')
                    ->body('La réquisition a été approuvée avec succès.')
                    ->success()
                    ->send();
            })
            ->visible(fn (Requisition $record) => $record->status === 'submitted');
    }
}
