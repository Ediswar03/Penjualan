<?php

namespace Database\Seeders;

use App\Models\Sale;
use Illuminate\Database\Seeder;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        $csvPath = database_path('data/sales.csv');

        if (!file_exists($csvPath)) {
            return;
        }

        if (($handle = fopen($csvPath, 'r')) === false) {
            return;
        }

        $header = fgetcsv($handle);
        if (empty($header)) {
            fclose($handle);
            return;
        }

        while (($row = fgetcsv($handle)) !== false) {
            $data = array_combine($header, $row);
            if ($data === false) {
                continue;
            }

            Sale::create([
                'date' => $data['date'],
                'sales' => intval($data['sales']),
                'activity' => in_array(strtolower($data['activity']), ['1', 'true', 'yes', 'y', 'ada'], true),
                'activity_name' => $data['activity_name'] ?: null,
                'rainfall_mm' => intval($data['rainfall_mm']),
                'rain_level' => ucfirst(strtolower($data['rain_level'])),
            ]);
        }

        fclose($handle);
    }
}
