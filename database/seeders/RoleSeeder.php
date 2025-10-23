<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer les permissions
        $permissions = [
            // Gestion des utilisateurs
            'view_users', 'create_users', 'edit_users', 'delete_users',
            
            // Gestion des rôles
            'view_roles', 'create_roles', 'edit_roles', 'delete_roles',
            
            // Module académique
            'view_branches', 'create_branches', 'edit_branches', 'delete_branches',
            'view_classes', 'create_classes', 'edit_classes', 'delete_classes',
            'view_subjects', 'create_subjects', 'edit_subjects', 'delete_subjects',
            'view_courses', 'create_courses', 'edit_courses', 'delete_courses',
            'view_timetables', 'create_timetables', 'edit_timetables', 'delete_timetables',
            
            // Gestion des élèves
            'view_students', 'create_students', 'edit_students', 'delete_students',
            'view_attendances', 'create_attendances', 'edit_attendances', 'delete_attendances',
            'view_grades', 'create_grades', 'edit_grades', 'delete_grades',
            'view_exams', 'create_exams', 'edit_exams', 'delete_exams',
            
            // Gestion des enseignants
            'view_teachers', 'create_teachers', 'edit_teachers', 'delete_teachers',
            
            // Module financier
            'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices',
            'view_payments', 'create_payments', 'edit_payments', 'delete_payments',
            'view_budgets', 'create_budgets', 'edit_budgets', 'delete_budgets',
            
            // Module DSP
            'view_requisitions', 'create_requisitions', 'edit_requisitions', 'delete_requisitions',
            'view_purchase_orders', 'create_purchase_orders', 'edit_purchase_orders', 'delete_purchase_orders',
            'view_stock_items', 'create_stock_items', 'edit_stock_items', 'delete_stock_items',
            'view_stock_movements', 'create_stock_movements', 'edit_stock_movements', 'delete_stock_movements',
            
            // Module bien-être
            'view_health_records', 'create_health_records', 'edit_health_records', 'delete_health_records',
            'view_psychological_records', 'create_psychological_records', 'edit_psychological_records', 'delete_psychological_records',
            
            // Rapports et tableaux de bord
            'view_reports', 'export_data', 'view_dashboards',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Créer les rôles
        $roles = [
            'Super Admin' => $permissions, // Accès complet
            'DG' => [ // Directeur Général
                'view_users', 'view_roles',
                'view_branches', 'view_classes', 'view_subjects', 'view_courses',
                'view_students', 'view_teachers',
                'view_invoices', 'view_payments', 'view_budgets',
                'view_requisitions', 'view_purchase_orders', 'view_stock_items',
                'view_health_records', 'view_psychological_records',
                'view_reports', 'export_data', 'view_dashboards',
            ],
            'DAF' => [ // Direction Administrative et Financière
                'view_users', 'view_students', 'view_teachers',
                'view_invoices', 'create_invoices', 'edit_invoices', 'delete_invoices',
                'view_payments', 'create_payments', 'edit_payments', 'delete_payments',
                'view_budgets', 'create_budgets', 'edit_budgets', 'delete_budgets',
                'view_reports', 'export_data', 'view_dashboards',
            ],
            'Direction pédagogique' => [
                'view_branches', 'view_classes', 'view_subjects', 'view_courses', 'view_timetables',
                'view_students', 'view_teachers',
                'view_attendances', 'create_attendances', 'edit_attendances',
                'view_grades', 'create_grades', 'edit_grades',
                'view_exams', 'create_exams', 'edit_exams',
                'view_reports', 'export_data', 'view_dashboards',
            ],
            'Direction du bien-être' => [
                'view_students',
                'view_health_records', 'create_health_records', 'edit_health_records',
                'view_psychological_records', 'create_psychological_records', 'edit_psychological_records',
                'view_reports', 'export_data', 'view_dashboards',
            ],
            'Enseignant' => [
                'view_students', 'view_classes', 'view_subjects', 'view_courses',
                'view_attendances', 'create_attendances', 'edit_attendances',
                'view_grades', 'create_grades', 'edit_grades',
                'view_exams', 'create_exams', 'edit_exams',
                'view_timetables',
            ],
            'Parent' => [
                'view_students', // Ses propres enfants
                'view_attendances', 'view_grades', 'view_exams',
            ],
            'Élève' => [
                'view_students', // Son propre profil
                'view_attendances', 'view_grades', 'view_exams', 'view_timetables',
            ],
            'Caissier' => [
                'view_invoices', 'view_payments', 'create_payments', 'edit_payments',
            ],
            'Magasinier' => [
                'view_stock_items', 'view_stock_movements', 'create_stock_movements', 'edit_stock_movements',
                'view_purchase_orders',
            ],
            'Logistique' => [
                'view_requisitions', 'create_requisitions', 'edit_requisitions',
                'view_purchase_orders', 'create_purchase_orders', 'edit_purchase_orders',
                'view_stock_items', 'view_stock_movements',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
