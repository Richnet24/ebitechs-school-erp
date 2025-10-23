<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            [
                'name' => 'Informatique',
                'code' => 'INFO',
                'description' => 'Filière d\'informatique et technologies de l\'information',
                'color' => '#3B82F6',
                'is_active' => true,
            ],
            [
                'name' => 'Génie Civil',
                'code' => 'GC',
                'description' => 'Filière de génie civil et construction',
                'color' => '#10B981',
                'is_active' => true,
            ],
            [
                'name' => 'Électromécanique',
                'code' => 'EM',
                'description' => 'Filière d\'électromécanique et maintenance industrielle',
                'color' => '#F59E0B',
                'is_active' => true,
            ],
            [
                'name' => 'Gestion',
                'code' => 'GEST',
                'description' => 'Filière de gestion et administration des entreprises',
                'color' => '#EF4444',
                'is_active' => true,
            ],
            [
                'name' => 'Sciences de la Santé',
                'code' => 'SANTE',
                'description' => 'Filière des sciences de la santé et soins infirmiers',
                'color' => '#8B5CF6',
                'is_active' => true,
            ],
            [
                'name' => 'Agronomie',
                'code' => 'AGRO',
                'description' => 'Filière d\'agronomie et développement rural',
                'color' => '#059669',
                'is_active' => true,
            ],
        ];

        foreach ($branches as $branch) {
            Branch::firstOrCreate(
                ['code' => $branch['code']],
                $branch
            );
        }
    }
}
