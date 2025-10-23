<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockItem;
use App\Models\Requisition;
use App\Models\PurchaseOrder;
use App\Models\StockMovement;
use App\Models\User;
use Carbon\Carbon;

class StockDataSeeder extends Seeder
{
    public function run(): void
    {
        // Créer des articles de stock
        $stockItems = [
            [
                'name' => 'Ordinateur Portable Dell',
                'sku' => 'DELL-001',
                'description' => 'Ordinateur portable Dell Inspiron 15',
                'category' => 'informatique',
                'unit' => 'piece',
                'current_stock' => 25,
                'minimum_stock' => 5,
                'unit_cost' => 800.00,
                'selling_price' => 1000.00,
                'location' => 'Magasin A - Rack 1',
            ],
            [
                'name' => 'Cahiers A4',
                'sku' => 'CAH-001',
                'description' => 'Cahiers 200 pages format A4',
                'category' => 'fournitures',
                'unit' => 'pack',
                'current_stock' => 100,
                'minimum_stock' => 20,
                'unit_cost' => 15.00,
                'selling_price' => 18.00,
                'location' => 'Magasin B - Étagère 2',
            ],
            [
                'name' => 'Stylos Bic',
                'sku' => 'STY-001',
                'description' => 'Paquet de 10 stylos Bic bleus',
                'category' => 'fournitures',
                'unit' => 'pack',
                'current_stock' => 50,
                'minimum_stock' => 10,
                'unit_cost' => 5.00,
                'selling_price' => 6.00,
                'location' => 'Magasin B - Étagère 1',
            ],
            [
                'name' => 'Tableau Blanc',
                'sku' => 'TAB-001',
                'description' => 'Tableau blanc magnétique 120x80cm',
                'category' => 'mobilier',
                'unit' => 'piece',
                'current_stock' => 15,
                'minimum_stock' => 3,
                'unit_cost' => 120.00,
                'selling_price' => 150.00,
                'location' => 'Magasin C - Zone Mobilier',
            ],
            [
                'name' => 'Chaises d\'École',
                'sku' => 'CHA-001',
                'description' => 'Chaises en plastique pour salles de classe',
                'category' => 'mobilier',
                'unit' => 'piece',
                'current_stock' => 200,
                'minimum_stock' => 50,
                'unit_cost' => 25.00,
                'selling_price' => 30.00,
                'location' => 'Magasin C - Zone Mobilier',
            ],
            [
                'name' => 'Projecteur Epson',
                'sku' => 'PRO-001',
                'description' => 'Projecteur Epson PowerLite 1781W',
                'category' => 'informatique',
                'unit' => 'piece',
                'current_stock' => 8,
                'minimum_stock' => 2,
                'unit_cost' => 450.00,
                'selling_price' => 600.00,
                'location' => 'Magasin A - Rack 2',
            ],
            [
                'name' => 'Papier A4',
                'sku' => 'PAP-001',
                'description' => 'Rame de papier A4 80g',
                'category' => 'fournitures',
                'unit' => 'ream',
                'current_stock' => 200,
                'minimum_stock' => 50,
                'unit_cost' => 8.00,
                'selling_price' => 10.00,
                'location' => 'Magasin B - Étagère 3',
            ],
            [
                'name' => 'Bureau Enseignant',
                'sku' => 'BUR-001',
                'description' => 'Bureau en bois pour enseignant',
                'category' => 'mobilier',
                'unit' => 'piece',
                'current_stock' => 20,
                'minimum_stock' => 5,
                'unit_cost' => 180.00,
                'selling_price' => 220.00,
                'location' => 'Magasin C - Zone Mobilier',
            ],
        ];

        foreach ($stockItems as $itemData) {
            StockItem::create($itemData);
        }

        // Créer des réquisitions
        $requisitions = [
            [
                'requisition_number' => 'REQ-' . date('Y') . '-' . str_pad(1, 4, '0', STR_PAD_LEFT),
                'requested_by' => 2, // Directeur Académique
                'description' => 'Réquisition pour équipement de laboratoire informatique',
                'justification' => 'Les ordinateurs actuels sont obsolètes et ne permettent plus d\'enseigner les nouvelles technologies',
                'estimated_cost' => 5000.00,
                'required_date' => Carbon::now()->addDays(5),
                'priority' => 'high',
                'status' => 'approved',
                'approved_by' => 1,
                'approved_at' => Carbon::now()->subDays(8),
                'approval_notes' => 'Approuvé pour améliorer la qualité de l\'enseignement',
            ],
            [
                'requisition_number' => 'REQ-' . date('Y') . '-' . str_pad(2, 4, '0', STR_PAD_LEFT),
                'requested_by' => 3, // Comptable
                'description' => 'Réquisition pour fournitures de bureau',
                'justification' => 'Manque de fournitures de base pour le fonctionnement administratif',
                'estimated_cost' => 500.00,
                'required_date' => Carbon::now()->addDays(10),
                'priority' => 'medium',
                'status' => 'submitted',
                'approved_by' => null,
                'approved_at' => null,
                'approval_notes' => null,
            ],
            [
                'requisition_number' => 'REQ-' . date('Y') . '-' . str_pad(3, 4, '0', STR_PAD_LEFT),
                'requested_by' => 4, // Secrétaire
                'description' => 'Réquisition pour mobilier de classe',
                'justification' => 'Nouveaux étudiants nécessitent du mobilier supplémentaire',
                'estimated_cost' => 2000.00,
                'required_date' => Carbon::now()->addDays(7),
                'priority' => 'medium',
                'status' => 'draft',
                'approved_by' => null,
                'approved_at' => null,
                'approval_notes' => null,
            ],
        ];

        foreach ($requisitions as $requisitionData) {
            Requisition::create($requisitionData);
        }

        // Créer des bons de commande
        $purchaseOrders = [
            [
                'po_number' => 'PO-' . date('Y') . '-' . str_pad(1, 4, '0', STR_PAD_LEFT),
                'requisition_id' => 1,
                'supplier_name' => 'Tech Solutions SARL',
                'supplier_contact' => '+243 123 456 803',
                'description' => 'Commande d\'équipement informatique',
                'total_amount' => 2400.00,
                'order_date' => Carbon::now()->subDays(7),
                'expected_delivery_date' => Carbon::now()->addDays(10),
                'status' => 'confirmed',
                'terms_conditions' => 'Livraison sous 10 jours ouvrés',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => Carbon::now()->subDays(6),
            ],
            [
                'po_number' => 'PO-' . date('Y') . '-' . str_pad(2, 4, '0', STR_PAD_LEFT),
                'requisition_id' => null,
                'supplier_name' => 'Papeterie Centrale',
                'supplier_contact' => '+243 123 456 801',
                'description' => 'Commande de fournitures de bureau',
                'total_amount' => 500.00,
                'order_date' => Carbon::now()->subDays(3),
                'expected_delivery_date' => Carbon::now()->addDays(5),
                'status' => 'sent',
                'terms_conditions' => 'Paiement à 30 jours',
                'created_by' => 2,
                'approved_by' => 1,
                'approved_at' => Carbon::now()->subDays(2),
            ],
        ];

        foreach ($purchaseOrders as $poData) {
            PurchaseOrder::create($poData);
        }

        // Créer des mouvements de stock
        $movementTypes = ['in', 'out', 'transfer', 'adjustment'];
        $stockItems = StockItem::all();

        foreach ($stockItems as $item) {
            // Mouvement d'entrée
            $quantityIn = rand(10, 50);
            StockMovement::create([
                'stock_item_id' => $item->id,
                'type' => 'in',
                'quantity' => $quantityIn,
                'unit_cost' => $item->unit_cost,
                'reason' => 'Réception de stock',
                'reference_number' => 'REF-IN-' . rand(1000, 9999),
                'purchase_order_id' => null,
                'processed_by' => rand(1, 3),
            ]);

            // Mouvement de sortie
            if (rand(0, 1)) {
                $quantityOut = rand(1, 10);
                StockMovement::create([
                    'stock_item_id' => $item->id,
                    'type' => 'out',
                    'quantity' => $quantityOut,
                    'unit_cost' => $item->unit_cost,
                    'reason' => 'Sortie de stock',
                    'reference_number' => 'REF-OUT-' . rand(1000, 9999),
                    'purchase_order_id' => null,
                    'processed_by' => rand(1, 3),
                ]);
            }
        }
    }
}