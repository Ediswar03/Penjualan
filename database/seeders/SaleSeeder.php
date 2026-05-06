<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SaleSeeder extends Seeder
{
    public function run(): void
    {
        // Clear existing data
        Sale::truncate();

        $sqlFile = database_path('data/Dataset.sql');
        $content = file_get_contents($sqlFile);

        // Regex to match INSERT INTO `tableName` (`Hari`, `Tanggal`, `Kegiatan`, `Curah Hujan (mm)`, `Penjualan (pcs)`) VALUES ('2', '1', '0', '1.4', '0');
        preg_match_all("/VALUES \('(\d+)', '(\d+)', '(\d+)', '([\d.]+)', '(\d+)'\);/", $content, $matches, PREG_SET_ORDER);

        $currentYear = 2024;
        $currentMonth = 1;
        $lastDay = 0;

        foreach ($matches as $match) {
            $day = (int)$match[2];
            $activity = (int)$match[3] === 1;
            $rainfall = (float)$match[4];
            $sales = (int)$match[5];

            // Detect month transition
            if ($day < $lastDay) {
                $currentMonth++;
                if ($currentMonth > 12) {
                    $currentMonth = 1;
                    $currentYear++;
                }
            }
            $lastDay = $day;

            $date = Carbon::create($currentYear, $currentMonth, $day);

            Sale::create([
                'date' => $date->format('Y-m-d'),
                'sales' => $sales,
                'activity' => $activity,
                'activity_name' => $activity ? 'Promo' : null,
                'rainfall_mm' => $rainfall,
                'rain_level' => $this->getRainLevel($rainfall),
            ]);
        }
    }

    private function getRainLevel($mm)
    {
        return $mm < 20 ? 'Low' : 'High';
    }
}
