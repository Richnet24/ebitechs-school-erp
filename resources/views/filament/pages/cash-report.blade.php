<x-filament-panels::page>
    <style>
        /* Force une taille fixe pour éviter tout override global sur les SVG */
        .report-icon{width:20px!important;height:20px!important;display:inline-block;}
        /* Styles de mise en forme modernes */
        .metric-card{transition:box-shadow .2s, transform .1s;}
        .metric-card:hover{box-shadow:0 10px 20px rgba(0,0,0,.15);transform:translateY(-1px)}
        .value-positive{color:#16a34a}
        .value-negative{color:#dc2626}
        .badge{display:inline-flex;align-items:center;border-radius:9999px;padding:2px 8px;font-size:12px}
        .badge-neutral{background:rgba(148,163,184,.15);color:#94a3b8}
        .badge-green{background:rgba(34,197,94,.15);color:#34d399}
        .badge-red{background:rgba(239,68,68,.15);color:#f87171}
        /* Helpers de layout forcés */
        .cards-grid{display:flex;flex-wrap:wrap;gap:24px;margin-bottom:32px}
        .card-col{width:100%}
        @media (min-width:768px){.card-col{width:calc(50% - 12px)}}
        @media (min-width:1280px){.card-col{width:calc(25% - 18px)}}
        /* Styles pro pour cards */
        .pro-card{border:1px solid rgba(148,163,184,.25);border-radius:14px;background:linear-gradient(180deg, rgba(255,255,255,.06), rgba(255,255,255,.02));box-shadow:0 8px 20px rgba(0,0,0,.12)}
        .dark .pro-card{border-color:rgba(148,163,184,.2);background:linear-gradient(180deg, rgba(17,24,39,.9), rgba(17,24,39,.7))}
        .icon-circle{width:36px;height:36px;border-radius:9999px;background:rgba(148,163,184,.18);display:flex;align-items:center;justify-content:center}
        .kpi-value{font-size:1.5rem;line-height:1.9rem;font-weight:800}
        @media (min-width:768px){.kpi-value{font-size:1.75rem;line-height:2.1rem}}
        .soft-badge{display:inline-flex;gap:6px;align-items:center;border-radius:9999px;background:rgba(148,163,184,.15);color:#94a3b8;padding:2px 8px;font-size:12px}
        /* Aération des textes */
        .pro-card p,.pro-card span{line-height:1.6}
        .list-row{padding:10px 0;border-bottom:1px solid rgba(107,114,128,.35)}
        .list-row:last-child{border-bottom:0}
        .section-body{display:flex;flex-direction:column;gap:10px}
    </style>
    <div class="space-y-6 max-w-[1200px] mx-auto">
        <!-- Formulaire de filtres -->
        <x-filament::section>
            {{ $this->form }}
        </x-filament::section>

        <!-- Résumé financier -->
        <div class="cards-grid">
            <!-- Total des paiements -->
            <div class="card-col pro-card rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 icon-circle">
                        <x-heroicon-o-arrow-trending-up class="report-icon text-green-600 dark:text-green-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-xs uppercase tracking-wide text-green-600 dark:text-green-400">Total Paiements</p>
                        <p class="kpi-value text-green-900 dark:text-green-100">
                            {{ number_format($this->totalPayments, 0, ',', ' ') }} USD
                        </p>
                        <p class="soft-badge">{{ $this->paymentsCount }} transactions</p>
                    </div>
                </div>
            </div>

            <!-- Total des dépenses -->
            <div class="card-col pro-card rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 icon-circle">
                        <x-heroicon-o-arrow-trending-down class="report-icon text-red-600 dark:text-red-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-xs uppercase tracking-wide text-red-600 dark:text-red-400">Total Dépenses</p>
                        <p class="kpi-value text-red-900 dark:text-red-100">
                            {{ number_format($this->totalExpenses, 0, ',', ' ') }} USD
                        </p>
                        <p class="soft-badge">{{ $this->expensesCount }} transactions</p>
                    </div>
                </div>
            </div>

            <!-- Solde net -->
            <div class="card-col pro-card rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 icon-circle">
                        <x-heroicon-o-banknotes class="report-icon text-blue-600 dark:text-blue-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-xs uppercase tracking-wide text-blue-600 dark:text-blue-400">Solde Net</p>
                        <p class="kpi-value {{ $this->netCash >= 0 ? 'value-positive' : 'value-negative' }}">
                            {{ number_format($this->netCash, 0, ',', ' ') }} USD
                        </p>
                        <p class="soft-badge">{{ $this->netCash >= 0 ? 'Bénéfice' : 'Déficit' }}</p>
                    </div>
                </div>
            </div>

            <!-- Marge -->
            <div class="card-col pro-card rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                <div class="flex items-center">
                    <div class="flex-shrink-0 icon-circle">
                        <x-heroicon-o-chart-pie class="report-icon text-purple-600 dark:text-purple-400" />
                    </div>
                    <div class="ml-4">
                        <p class="text-xs uppercase tracking-wide text-purple-600 dark:text-purple-400">Marge</p>
                        <p class="kpi-value text-purple-900 dark:text-purple-100">
                            {{ $this->totalPayments > 0 ? number_format(($this->netCash / $this->totalPayments) * 100, 1) : 0 }}%
                        </p>
                        <p class="soft-badge">de rentabilité</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques et détails -->
        <div class="cards-grid">
            <!-- Paiements par type -->
            <div class="card-col pro-card rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                <h3 class="text-sm font-semibold text-gray-100 mb-1">Paiements par Type</h3>
                <p class="text-xs text-gray-400 mb-3">Répartition des encaissements</p>
                <div class="section-body">
                    @foreach($this->getPaymentsByCategory() as $payment)
                        <div class="list-row flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-300">
                                {{ ucfirst($payment->payment_method) }}
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-100">
                                    {{ number_format($payment->total, 0, ',', ' ') }} USD
                                </span>
                                <span class="text-xs text-gray-400">
                                    ({{ $payment->count }})
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Dépenses par catégorie -->
            <div class="card-col pro-card rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                <h3 class="text-sm font-semibold text-gray-100 mb-1">Dépenses par Catégorie</h3>
                <p class="text-xs text-gray-400 mb-3">Répartition des sorties</p>
                <div class="section-body">
                    @foreach($this->getExpensesByCategory() as $expense)
                        <div class="list-row flex justify-between items-center">
                            <span class="text-sm font-medium text-gray-300">
                                {{ ucfirst($expense->category) }}
                            </span>
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold text-gray-100">
                                    {{ number_format($expense->total, 0, ',', ' ') }} USD
                                </span>
                                <span class="text-xs text-gray-400">
                                    ({{ $expense->count }})
                                </span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Transactions récentes -->
        <div class="cards-grid">
            <!-- Paiements récents -->
            <div class="card-col pro-card rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                <h3 class="text-sm font-semibold text-gray-100 mb-3">Paiements Récents</h3>
                <div class="section-body">
                    @forelse($this->getRecentPayments() as $payment)
                        <div class="list-row flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-100">
                                    {{ $payment->invoice->student->name ?? 'N/A' }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $payment->payment_date->format('d/m/Y') }} - {{ ucfirst($payment->payment_method) }}
                                </p>
                            </div>
                            <span class="text-sm font-bold value-positive">
                                +{{ number_format($payment->amount, 0, ',', ' ') }} USD
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-sm text-gray-400">Aucun paiement trouvé pour cette période</div>
                    @endforelse
                </div>
            </div>

            <!-- Dépenses récentes -->
            <div class="card-col pro-card rounded-lg border border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 p-4">
                <h3 class="text-sm font-semibold text-gray-100 mb-3">Dépenses Récentes</h3>
                <div class="section-body">
                    @forelse($this->getRecentExpenses() as $expense)
                        <div class="list-row flex justify-between items-center">
                            <div>
                                <p class="text-sm font-medium text-gray-100">
                                    {{ $expense->title }}
                                </p>
                                <p class="text-xs text-gray-400">
                                    {{ $expense->expense_date->format('d/m/Y') }} - {{ ucfirst($expense->category) }}
                                </p>
                            </div>
                            <span class="text-sm font-bold value-negative">
                                -{{ number_format($expense->amount, 0, ',', ' ') }} USD
                            </span>
                        </div>
                    @empty
                        <div class="text-center py-6 text-sm text-gray-400">Aucune dépense trouvée pour cette période</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-filament-panels::page>
