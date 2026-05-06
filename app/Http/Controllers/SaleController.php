<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::orderBy('date')->get();

        $trendData = Sale::selectRaw('date, SUM(sales) as total_sales')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $activityData = Sale::selectRaw('activity, AVG(sales) as avg_sales')
            ->groupBy('activity')
            ->get();

        $rainData = Sale::selectRaw('rain_level, AVG(sales) as avg_sales')
            ->groupBy('rain_level')
            ->get();

        $activityStats = [
            'with' => $activityData->firstWhere('activity', 1)?->avg_sales ?: 0,
            'without' => $activityData->firstWhere('activity', 0)?->avg_sales ?: 0,
        ];

        $rainStats = [
            'high' => $rainData->firstWhere('rain_level', 'High')?->avg_sales ?: 0,
            'low' => $rainData->firstWhere('rain_level', 'Low')?->avg_sales ?: 0,
        ];

        $trendInsight = '';
        if ($trendData->count() >= 2) {
            $first = $trendData->first()->total_sales;
            $last = $trendData->last()->total_sales;
            if ($last > $first) {
                $trendInsight = 'Tren penjualan menunjukkan kenaikan dari awal hingga akhir periode.';
            } elseif ($last < $first) {
                $trendInsight = 'Tren penjualan menunjukkan penurunan dari awal hingga akhir periode.';
            } else {
                $trendInsight = 'Tren penjualan relatif stabil sepanjang periode.';
            }
        }

        $activityInsight = $activityStats['with'] >= $activityStats['without']
            ? 'Rata-rata penjualan lebih tinggi saat ada kegiatan.'
            : 'Rata-rata penjualan lebih tinggi saat tidak ada kegiatan.';

        $rainInsight = $rainStats['high'] >= $rainStats['low']
            ? 'Rata-rata penjualan lebih tinggi saat curah hujan tinggi.'
            : 'Rata-rata penjualan lebih tinggi saat curah hujan rendah.';

        return view('sales', [
            'sales' => $sales,
            'trendData' => $trendData,
            'activityStats' => $activityStats,
            'rainStats' => $rainStats,
            'trendInsight' => $trendInsight,
            'activityInsight' => $activityInsight,
            'rainInsight' => $rainInsight,
        ]);
    }
}
