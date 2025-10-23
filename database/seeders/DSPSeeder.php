<?php

namespace Database\Seeders;

use App\Models\PurchaseOrder;
use App\Models\Requisition;
use App\Models\StockItem;
use App\Models\StockMovement;
use App\Models\User;
use Illuminate\Database\Seeder;

class DSPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer des articles en stock de démonstration
        $stockItems = [
            [
                'name' => 'Ordinateur portable Dell',
                'sku' => 'DELL-LAT-001',
                'description' => 'Ordinateur portable Dell Inspiron 15 pouces',
                'category' => 'electronics',
                'unit' => 'piece',
                'current_stock' => 10,
                'minimum_stock' => 2,
                'maximum_stock' => 50,
                'unit_cost' => 800.00,
                'selling_price' => 1000.00,
                'location' => 'Entrepôt A - Étagère 1',
                'is_active' => true,
            ],
            [
                'name' => 'Chaise de bureau ergonomique',
                'sku' => 'CHAIR-ERG-001',
                'description' => 'Chaise de bureau ergonomique avec support lombaire',
                'category' => 'furniture',
                'unit' => 'piece',
                'current_stock' => 25,
                'minimum_stock' => 5,
                'maximum_stock' => 100,
                'unit_cost' => 150.00,
                'selling_price' => 200.00,
                'location' => 'Entrepôt B - Zone mobilier',
                'is_active' => true,
            ],
            [
                'name' => 'Papier A4 (500 feuilles)',
                'sku' => 'PAPER-A4-500',
                'description' => 'Rame de papier A4 blanc 80g',
                'category' => 'office_supplies',
                'unit' => 'ream',
                'current_stock' => 50,
                'minimum_stock' => 10,
                'maximum_stock' => 200,
                'unit_cost' => 5.00,
                'selling_price' => 7.50,
                'location' => 'Entrepôt C - Fournitures',
                'is_active' => true,
            ],
            [
                'name' => 'Projecteur HD',
                'sku' => 'PROJ-HD-001',
                'description' => 'Projecteur HD pour salles de classe',
                'category' => 'equipment',
                'unit' => 'piece',
                'current_stock' => 5,
                'minimum_stock' => 1,
                'maximum_stock' => 20,
                'unit_cost' => 300.00,
                'selling_price' => 400.00,
                'location' => 'Entrepôt A - Équipements',
                'is_active' => true,
            ],
            [
                'name' => 'Livre de programmation Python',
                'sku' => 'BOOK-PYTHON-001',
                'description' => 'Manuel de programmation Python pour débutants',
                'category' => 'books',
                'unit' => 'piece',
                'current_stock' => 15,
                'minimum_stock' => 3,
                'maximum_stock' => 50,
                'unit_cost' => 25.00,
                'selling_price' => 35.00,
                'location' => 'Bibliothèque - Section informatique',
                'is_active' => true,
            ],
        ];

        foreach ($stockItems as $itemData) {
            StockItem::firstOrCreate(
                ['sku' => $itemData['sku']],
                $itemData
            );
        }

        // Créer des réquisitions de démonstration
        $requisitions = [
            [
                'requisition_number' => 'REQ-2024-001',
                'description' => 'Achat d\'ordinateurs portables pour les nouveaux étudiants',
                'justification' => 'Besoin urgent d\'équipements informatiques pour la rentrée académique',
                'estimated_cost' => 8000.00,
                'required_date' => now()->addDays(15),
                'priority' => 'high',
                'status' => 'approved',
                'requested_by' => 1, // Super Admin
                'approved_by' => 1,
                'approved_at' => now()->subDays(5),
                'approval_notes' => 'Approuvé pour la rentrée académique',
            ],
            [
                'requisition_number' => 'REQ-2024-002',
                'description' => 'Fournitures de bureau pour les bureaux administratifs',
                'justification' => 'Réapprovisionnement des fournitures de bureau',
                'estimated_cost' => 500.00,
                'required_date' => now()->addDays(7),
                'priority' => 'medium',
                'status' => 'submitted',
                'requested_by' => 1,
            ],
            [
                'requisition_number' => 'REQ-2024-003',
                'description' => 'Mobilier pour nouvelle salle de classe',
                'justification' => 'Équipement d\'une nouvelle salle de classe',
                'estimated_cost' => 2000.00,
                'required_date' => now()->addDays(30),
                'priority' => 'low',
                'status' => 'draft',
                'requested_by' => 1,
            ],
        ];

        foreach ($requisitions as $reqData) {
            Requisition::firstOrCreate(
                ['requisition_number' => $reqData['requisition_number']],
                $reqData
            );
        }

        // Créer des bons de commande de démonstration
        $purchaseOrders = [
            [
                'po_number' => 'PO-2024-001',
                'requisition_id' => 1,
                'supplier_name' => 'Fournisseur Informatique ABC',
                'supplier_contact' => '+243 123 456 789',
                'description' => 'Commande de 10 ordinateurs portables Dell',
                'total_amount' => 8000.00,
                'order_date' => now()->subDays(3),
                'expected_delivery_date' => now()->addDays(12),
                'status' => 'confirmed',
                'terms_conditions' => 'Livraison sous 15 jours, garantie 2 ans',
                'created_by' => 1,
                'approved_by' => 1,
                'approved_at' => now()->subDays(2),
            ],
            [
                'po_number' => 'PO-2024-002',
                'supplier_name' => 'Fournisseur Mobilier XYZ',
                'supplier_contact' => '+243 987 654 321',
                'description' => 'Commande de chaises de bureau ergonomiques',
                'total_amount' => 1500.00,
                'order_date' => now()->subDays(1),
                'expected_delivery_date' => now()->addDays(10),
                'status' => 'sent',
                'terms_conditions' => 'Livraison sous 10 jours',
                'created_by' => 1,
            ],
        ];

        foreach ($purchaseOrders as $poData) {
            PurchaseOrder::firstOrCreate(
                ['po_number' => $poData['po_number']],
                $poData
            );
        }

        // Créer des mouvements de stock de démonstration
        $stockMovements = [
            [
                'stock_item_id' => 1, // Ordinateur portable Dell
                'type' => 'in',
                'quantity' => 10,
                'unit_cost' => 800.00,
                'reason' => 'Réception de la commande PO-2024-001',
                'reference_number' => 'PO-2024-001',
                'purchase_order_id' => 1,
                'processed_by' => 1,
            ],
            [
                'stock_item_id' => 2, // Chaise de bureau
                'type' => 'in',
                'quantity' => 10,
                'unit_cost' => 150.00,
                'reason' => 'Réception de la commande PO-2024-002',
                'reference_number' => 'PO-2024-002',
                'purchase_order_id' => 2,
                'processed_by' => 1,
            ],
            [
                'stock_item_id' => 1, // Ordinateur portable Dell
                'type' => 'out',
                'quantity' => 2,
                'unit_cost' => 800.00,
                'reason' => 'Attribution aux nouveaux étudiants',
                'reference_number' => 'ASSIGN-001',
                'processed_by' => 1,
            ],
            [
                'stock_item_id' => 3, // Papier A4
                'type' => 'adjustment',
                'quantity' => 5,
                'unit_cost' => 5.00,
                'reason' => 'Ajustement d\'inventaire - différence comptée',
                'reference_number' => 'ADJ-001',
                'processed_by' => 1,
            ],
        ];

        foreach ($stockMovements as $movementData) {
            StockMovement::create($movementData);
        }

        $this->command->info('Données DSP de démonstration créées avec succès !');
    }
}