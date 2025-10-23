<?php

namespace App\Filament\Resources\Payments\Schemas;

use App\Models\Invoice;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PaymentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Informations du Paiement')
                    ->description('Configurez les informations du paiement')
                    ->schema([
                        Select::make('invoice_id')
                            ->label('Facture')
                            ->relationship('invoice', 'invoice_number')
                            ->getOptionLabelFromRecordUsing(fn ($record) => 
                                $record->invoice_number . ' - ' . 
                                $record->student->user->name . ' (' . 
                                $record->total_amount . '$)'
                            )
                            ->searchable()
                            ->preload()
                            ->required()
                            ->reactive()
                            ->afterStateUpdated(function ($state, callable $set) {
                                if ($state) {
                                    $invoice = Invoice::find($state);
                                    if ($invoice) {
                                        $remainingAmount = $invoice->remaining_amount;
                                        $set('amount', $remainingAmount);
                                    }
                                }
                            }),
                        
                        TextInput::make('payment_number')
                            ->label('Numéro de paiement')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255)
                            ->placeholder('Ex: PAY-2024-001')
                            ->helperText('Numéro unique du paiement'),
                        
                        TextInput::make('amount')
                            ->label('Montant payé')
                            ->numeric()
                            ->required()
                            ->prefix('$')
                            ->step(0.01)
                            ->helperText('Montant du paiement'),
                        
                        DatePicker::make('payment_date')
                            ->label('Date de paiement')
                            ->required()
                            ->default(now()),
                        
                        Select::make('payment_method')
                            ->label('Méthode de paiement')
                            ->options([
                                'cash' => 'Espèces',
                                'bank_transfer' => 'Virement bancaire',
                                'check' => 'Chèque',
                                'mobile_money' => 'Mobile Money',
                                'card' => 'Carte bancaire',
                            ])
                            ->default('cash')
                            ->required(),
                        
                        TextInput::make('reference_number')
                            ->label('Numéro de référence')
                            ->maxLength(255)
                            ->placeholder('Ex: REF-123456')
                            ->helperText('Numéro de référence du paiement (virement, chèque, etc.)'),
                        
                        Textarea::make('notes')
                            ->label('Notes')
                            ->rows(3)
                            ->placeholder('Notes additionnelles sur le paiement'),

                        Hidden::make('received_by')
                            ->default(auth()->id()),
                    ])
                    ->columns(2),
            ]);
    }
}