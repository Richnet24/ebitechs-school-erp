<?php

namespace App\Filament\Pages;

use App\Models\Expense;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Filament\Pages\Page;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CashReport extends Page implements HasForms
{
    use InteractsWithForms;

    protected string $view = 'filament.pages.cash-report';

    public static function getNavigationIcon(): ?string
    {
        return 'heroicon-o-chart-bar';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Module Financier (DAF)';
    }

    public static function getNavigationSort(): ?int
    {
        return 5;
    }

    public ?array $data = [];
    public $startDate;
    public $endDate;
    public $totalPayments = 0;
    public $totalExpenses = 0;
    public $netCash = 0;
    public $paymentsCount = 0;
    public $expensesCount = 0;

    public function mount(): void
    {
        $this->form->fill([
            'start_date' => now()->startOfMonth(),
            'end_date' => now()->endOfMonth(),
        ]);
        
        $this->loadReportData();
    }

    public function form(\Filament\Schemas\Schema $schema): \Filament\Schemas\Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Période du rapport')
                    ->description('Sélectionnez la période pour générer le rapport de caisse')
                    ->schema([
                        \Filament\Forms\Components\DatePicker::make('start_date')
                            ->label('Date de début')
                            ->required()
                            ->default(now()->startOfMonth())
                            ->live()
                            ->afterStateUpdated(fn () => $this->loadReportData()),
                        
                        \Filament\Forms\Components\DatePicker::make('end_date')
                            ->label('Date de fin')
                            ->required()
                            ->default(now()->endOfMonth())
                            ->live()
                            ->afterStateUpdated(fn () => $this->loadReportData()),
                    ])
                    ->columns(2),
            ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('generateReport')
                ->label('Générer le rapport')
                ->icon('heroicon-o-arrow-path')
                ->action('loadReportData'),
            
            Action::make('exportReport')
                ->label('Exporter PDF')
                ->icon('heroicon-o-document-arrow-down')
                ->url(fn () => route('admin.cash-report.export', [
                    'start_date' => ($this->data['start_date'] ?? now()->startOfMonth())->toDateString(),
                    'end_date' => ($this->data['end_date'] ?? now()->endOfMonth())->toDateString(),
                ]))
                ->openUrlInNewTab(),
        ];
    }

    public function loadReportData(): void
    {
        $startDate = $this->data['start_date'] ?? now()->startOfMonth();
        $endDate = $this->data['end_date'] ?? now()->endOfMonth();

        // Convertir en objets Carbon si nécessaire
        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        // Calculer les paiements
        $payments = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->selectRaw('SUM(amount) as total, COUNT(*) as count')
            ->first();

        // Calculer les dépenses
        $expenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->whereIn('status', ['approved', 'paid'])
            ->selectRaw('SUM(amount) as total, COUNT(*) as count')
            ->first();

        $this->totalPayments = $payments->total ?? 0;
        $this->totalExpenses = $expenses->total ?? 0;
        $this->netCash = $this->totalPayments - $this->totalExpenses;
        $this->paymentsCount = $payments->count ?? 0;
        $this->expensesCount = $expenses->count ?? 0;

        Notification::make()
            ->title('Rapport généré avec succès')
            ->success()
            ->send();
    }

    // L'export est désormais géré par une route dédiée

    public function getPaymentsByCategory()
    {
        $startDate = $this->data['start_date'] ?? now()->startOfMonth();
        $endDate = $this->data['end_date'] ?? now()->endOfMonth();

        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        return Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();
    }

    public function getExpensesByCategory()
    {
        $startDate = $this->data['start_date'] ?? now()->startOfMonth();
        $endDate = $this->data['end_date'] ?? now()->endOfMonth();

        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        return Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->whereIn('status', ['approved', 'paid'])
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->get();
    }

    public function getRecentPayments()
    {
        $startDate = $this->data['start_date'] ?? now()->startOfMonth();
        $endDate = $this->data['end_date'] ?? now()->endOfMonth();

        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        return Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->with(['invoice.student'])
            ->orderBy('payment_date', 'desc')
            ->limit(10)
            ->get();
    }

    public function getRecentExpenses()
    {
        $startDate = $this->data['start_date'] ?? now()->startOfMonth();
        $endDate = $this->data['end_date'] ?? now()->endOfMonth();

        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        return Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->whereIn('status', ['approved', 'paid'])
            ->orderBy('expense_date', 'desc')
            ->limit(10)
            ->get();
    }
}
