<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Payment;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Symfony\Component\HttpFoundation\Response;

class CashReportExportController extends Controller
{
    public function __invoke(Request $request): Response
    {
        $startDate = $request->query('start_date', now()->startOfMonth()->toDateString());
        $endDate = $request->query('end_date', now()->endOfMonth()->toDateString());

        if (is_string($startDate)) {
            $startDate = Carbon::parse($startDate);
        }
        if (is_string($endDate)) {
            $endDate = Carbon::parse($endDate);
        }

        $totalPayments = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->selectRaw('SUM(amount) as total, COUNT(*) as count')
            ->first();

        $totalExpenses = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->whereIn('status', ['approved', 'paid'])
            ->selectRaw('SUM(amount) as total, COUNT(*) as count')
            ->first();

        $paymentsByMethod = Payment::whereBetween('payment_date', [$startDate, $endDate])
            ->selectRaw('payment_method, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('payment_method')
            ->get();

        $expensesByCategory = Expense::whereBetween('expense_date', [$startDate, $endDate])
            ->whereIn('status', ['approved', 'paid'])
            ->selectRaw('category, SUM(amount) as total, COUNT(*) as count')
            ->groupBy('category')
            ->get();

        $pdf = Pdf::loadView('reports.cash-report-pdf', [
            'startDate' => $startDate,
            'endDate' => $endDate,
            'totalPayments' => $totalPayments->total ?? 0,
            'totalExpenses' => $totalExpenses->total ?? 0,
            'netCash' => ($totalPayments->total ?? 0) - ($totalExpenses->total ?? 0),
            'paymentsCount' => $totalPayments->count ?? 0,
            'expensesCount' => $totalExpenses->count ?? 0,
            'paymentsByMethod' => $paymentsByMethod,
            'expensesByCategory' => $expensesByCategory,
            'recentPayments' => Payment::whereBetween('payment_date', [$startDate, $endDate])
                ->with(['invoice.student'])->orderBy('payment_date', 'desc')->limit(20)->get(),
            'recentExpenses' => Expense::whereBetween('expense_date', [$startDate, $endDate])
                ->whereIn('status', ['approved', 'paid'])->orderBy('expense_date', 'desc')->limit(20)->get(),
        ])->setPaper('a4', 'portrait');

        $fileName = 'cash-report-' . $startDate->format('Ymd') . '-' . $endDate->format('Ymd') . '.pdf';

        return $pdf->download($fileName);
    }
}


