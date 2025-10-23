<?php

namespace Database\Seeders;

use App\Models\Estimation;
use App\Models\Hpp;
use App\Models\HppItem;
use App\Models\Project;
use Illuminate\Database\Seeder;

class HppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Creating HPP dummy data with new TKDN classifications...');

        // Get projects and estimations
        $projects = Project::all();
        $estimations = Estimation::all();

        // Get master data for creating estimation items
        $workers = \App\Models\Worker::all();
        $materials = \App\Models\Material::all();
        $equipment = \App\Models\Equipment::all();

        $createdHppCount = 0;
        $createdHppItemCount = 0;

        // Create 10 HPP records with different classifications
        for ($i = 1; $i <= 10; $i++) {
            $project = $projects->random();
            $hppCode = 'HPP-' . date('Ymd') . '-' . str_pad($i, 3, '0', STR_PAD_LEFT);

            // Check if HPP already exists
            $existingHpp = Hpp::where('code', $hppCode)->first();
            if ($existingHpp) {
                $this->command->warn("HPP with code {$hppCode} already exists. Skipping...");

                continue;
            }

            // Calculate HPP costs
            $baseCost = rand(50000, 200000); // Random base cost between 50k-200k
            $overheadPercentage = 12.00;
            $overheadAmount = $baseCost * ($overheadPercentage / 100);
            $marginPercentage = 18.00;
            $marginAmount = $baseCost * ($marginPercentage / 100);
            $subTotal = $baseCost + $overheadAmount + $marginAmount;
            $ppnPercentage = 11.00;
            $ppnAmount = $subTotal * ($ppnPercentage / 100);
            $grandTotal = $subTotal + $ppnAmount;

            $hpp = Hpp::create([
                'code' => $hppCode,
                'project_id' => $project->id,
                'sub_total_hpp' => $baseCost,
                'overhead_percentage' => $overheadPercentage,
                'overhead_amount' => $overheadAmount,
                'margin_percentage' => $marginPercentage,
                'margin_amount' => $marginAmount,
                'sub_total' => $subTotal,
                'ppn_percentage' => $ppnPercentage,
                'ppn_amount' => $ppnAmount,
                'grand_total' => $grandTotal,
                'notes' => "HPP Dummy Data #{$i} - Project: {$project->name}",
                'status' => 'draft',
            ]);

            $createdHppCount++;

            // Create HPP Items based on different TKDN classifications
            $classifications = [
                'Overhead & Manajemen',
                'Alat Kerja / Fasilitas',
                'Konstruksi & Fabrikasi',
                'Peralatan (Jasa Umum)',
                'Material (Bahan Baku)',
                'Peralatan (Barang Jadi)',
            ];

            // Create 2-4 HPP items per HPP with different classifications
            $itemCount = rand(2, 4);
            for ($j = 1; $j <= $itemCount; $j++) {
                $classification = $classifications[array_rand($classifications)];

                // Get random master data based on classification
                $estimationItem = null;
                $estimation = $estimations->random();

                if ($classification === 'Overhead & Manajemen') {
                    $worker = $workers->where('classification_tkdn', $classification)->first();
                    if (! $worker) {
                        $worker = \App\Models\Worker::inRandomOrder()->first(); // Fallback to any worker
                    }
                    $estimationItem = \App\Models\EstimationItem::create([
                        'estimation_id' => $estimation->id,
                        'code' => 'WK-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                        'category' => 'worker',
                        'reference_id' => $worker->id,
                        'coefficient' => rand(10, 50) / 10, // Random coefficient between 1.0-5.0
                        'unit_price' => $worker->price,
                        'total_price' => $worker->price * rand(10, 50),
                    ]);
                } elseif ($classification === 'Material (Bahan Baku)') {
                    $material = $materials->where('classification_tkdn', $classification)->first();
                    if (! $material) {
                        $material = \App\Models\Material::inRandomOrder()->first(); // Fallback to any material
                    }
                    $estimationItem = \App\Models\EstimationItem::create([
                        'estimation_id' => $estimation->id,
                        'code' => 'MT-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                        'category' => 'material',
                        'reference_id' => $material->id,
                        'coefficient' => rand(5, 20) / 10, // Random coefficient between 0.5-2.0
                        'unit_price' => $material->price,
                        'total_price' => $material->price * rand(5, 20),
                    ]);
                } else {
                    $equipmentItem = $equipment->where('classification_tkdn', $classification)->first();
                    if (! $equipmentItem) {
                        $equipmentItem = \App\Models\Equipment::inRandomOrder()->first(); // Fallback to any equipment
                    }
                    $estimationItem = \App\Models\EstimationItem::create([
                        'estimation_id' => $estimation->id,
                        'code' => 'EQ-' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT),
                        'category' => 'equipment',
                        'reference_id' => $equipmentItem->id,
                        'coefficient' => rand(1, 10) / 10, // Random coefficient between 0.1-1.0
                        'unit_price' => $equipmentItem->price,
                        'total_price' => $equipmentItem->price * rand(1, 10),
                    ]);
                }

                // Create HPP Item
                $volume = rand(1, 100);
                $unitPrice = $estimationItem->unit_price;
                $totalPrice = $unitPrice * $volume;

                $categoryMap = [
                    'worker'    => \App\Models\Worker::class,
                    'material'  => \App\Models\Material::class,
                    'equipment' => \App\Models\Equipment::class,
                ];

                // Get description from master data
                $description = '';
                $model = $categoryMap[$estimationItem->category] ?? null;
                if (!$model) {
                    continue; // skip kalau category tidak dikenali
                }

                $reference = $model::find($estimationItem->reference_id);
                if (!$reference) {
                    continue; // skip kalau referensi tidak ada
                }

                // Buat description
                $description = "HPP Item #{$j} - {$reference->name} ({$classification})";


                HppItem::create([
                    'hpp_id' => $hpp->id,
                    'estimation_item_id' => $estimationItem->id,
                    'item_type' => $model,
                    'item_id' => $reference->id,
                    'description' => $description,
                    'volume' => $volume,
                    'unit' => 'unit',
                    'duration' => rand(1, 30),
                    'duration_unit' => 'hari',
                    'unit_price' => $unitPrice,
                    'total_price' => $totalPrice,
                ]);

                $createdHppItemCount++;
            }
        }

        $this->command->info('HppSeeder completed successfully!');
        $this->command->info("Created: {$createdHppCount} HPP records");
        $this->command->info("Created: {$createdHppItemCount} HPP items");
        $this->command->info('Total HPP Cost Range: Rp 50,000 - Rp 200,000 per HPP');
        $this->command->info('All HPP items use new TKDN classifications!');
    }
}
