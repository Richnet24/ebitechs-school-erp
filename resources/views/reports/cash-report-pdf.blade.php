<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rapport de Caisse</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #111827; }
        h1 { font-size: 20px; margin: 0 0 10px; }
        h2 { font-size: 16px; margin: 16px 0 8px; }
        .muted { color: #6b7280; }
        .grid { display: grid; grid-template-columns: repeat(2, 1fr); gap: 12px; }
        .card { border: 1px solid #e5e7eb; border-radius: 8px; padding: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border-bottom: 1px solid #e5e7eb; text-align: left; padding: 6px 4px; }
        th { background: #f9fafb; }
        .right { text-align: right; }
    </style>
    
    <!-- Astuce: DomPDF supporte partiellement Tailwind; on reste en CSS simple. -->
</head>
<body>
    <h1>Rapport de Caisse</h1>
    <p class="muted">Période: {{ $startDate->format('d/m/Y') }} - {{ $endDate->format('d/m/Y') }}</p>

    <div class="grid">
        <div class="card">
            <h2>Total Paiements</h2>
            <p><strong>{{ number_format($totalPayments, 0, ',', ' ') }} USD</strong></p>
            <p class="muted">{{ $paymentsCount }} transactions</p>
        </div>
        <div class="card">
            <h2>Total Dépenses</h2>
            <p><strong>{{ number_format($totalExpenses, 0, ',', ' ') }} USD</strong></p>
            <p class="muted">{{ $expensesCount }} transactions</p>
        </div>
        <div class="card">
            <h2>Solde Net</h2>
            <p><strong>{{ number_format($netCash, 0, ',', ' ') }} USD</strong></p>
            <p class="muted">{{ $netCash >= 0 ? 'Bénéfice' : 'Déficit' }}</p>
        </div>
        <div class="card">
            <h2>Marge</h2>
            <p><strong>{{ $totalPayments > 0 ? number_format(($netCash / $totalPayments) * 100, 1) : 0 }}%</strong></p>
            <p class="muted">de rentabilité</p>
        </div>
    </div>

    <h2>Paiements par Type</h2>
    <table>
        <thead>
            <tr>
                <th>Type</th>
                <th class="right">Montant</th>
                <th class="right">Transactions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentsByMethod as $row)
                <tr>
                    <td>{{ ucfirst($row->payment_method) }}</td>
                    <td class="right">{{ number_format($row->total, 0, ',', ' ') }} USD</td>
                    <td class="right">{{ $row->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Dépenses par Catégorie</h2>
    <table>
        <thead>
            <tr>
                <th>Catégorie</th>
                <th class="right">Montant</th>
                <th class="right">Transactions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($expensesByCategory as $row)
                <tr>
                    <td>{{ ucfirst($row->category) }}</td>
                    <td class="right">{{ number_format($row->total, 0, ',', ' ') }} USD</td>
                    <td class="right">{{ $row->count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Paiements Récents</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Étudiant</th>
                <th>Méthode</th>
                <th class="right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentPayments as $payment)
                <tr>
                    <td>{{ $payment->payment_date->format('d/m/Y') }}</td>
                    <td>{{ $payment->invoice->student->name ?? 'N/A' }}</td>
                    <td>{{ ucfirst($payment->payment_method) }}</td>
                    <td class="right">{{ number_format($payment->amount, 0, ',', ' ') }} USD</td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">Aucun paiement trouvé</td></tr>
            @endforelse
        </tbody>
    </table>

    <h2>Dépenses Récentes</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Titre</th>
                <th>Catégorie</th>
                <th class="right">Montant</th>
            </tr>
        </thead>
        <tbody>
            @forelse($recentExpenses as $expense)
                <tr>
                    <td>{{ $expense->expense_date->format('d/m/Y') }}</td>
                    <td>{{ $expense->title }}</td>
                    <td>{{ ucfirst($expense->category) }}</td>
                    <td class="right">{{ number_format($expense->amount, 0, ',', ' ') }} USD</td>
                </tr>
            @empty
                <tr><td colspan="4" class="muted">Aucune dépense trouvée</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>


