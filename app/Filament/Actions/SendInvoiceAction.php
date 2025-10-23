<?php

namespace App\Filament\Actions;

use App\Models\Invoice;
use Filament\Actions\Action;
use Filament\Notifications\Notification;

class SendInvoiceAction extends Action
{
    public static function getDefaultName(): string
    {
        return 'send_invoice';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->label('Envoyer la facture')
            ->icon('heroicon-o-paper-airplane')
            ->color('success')
            ->requiresConfirmation()
            ->modalHeading('Envoyer la facture')
            ->modalDescription('Êtes-vous sûr de vouloir envoyer cette facture à l\'élève ?')
            ->modalSubmitActionLabel('Envoyer')
            ->action(function (Invoice $record) {
                $record->update(['status' => 'sent']);
                
                Notification::make()
                    ->title('Facture envoyée')
                    ->body('La facture a été envoyée avec succès.')
                    ->success()
                    ->send();
            })
            ->visible(fn (Invoice $record) => $record->status === 'draft');
    }
}
