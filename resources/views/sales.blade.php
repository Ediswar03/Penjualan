<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Visualisasi Data Penjualan</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f6f7fb; color: #111827; margin: 0; padding: 0; }
        .page { max-width: 1200px; margin: 0 auto; padding: 24px; }
        .card { background: #fff; border-radius: 16px; box-shadow: 0 20px 45px rgba(15,23,42,.08); margin-bottom: 24px; padding: 24px; }
        .grid { display: grid; gap: 24px; }
        .grid-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .badge { display: inline-flex; padding: 8px 14px; border-radius: 999px; background: #f3f4f6; color: #111827; font-size: 0.85rem; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { padding: 12px 14px; text-align: left; border-bottom: 1px solid #e5e7eb; }
        th { font-weight: 600; color: #374151; }
        tr:nth-child(even) { background: #f9fafb; }
        .insight { padding: 16px; background: #eef2ff; border-left: 4px solid #6366f1; border-radius: 12px; margin-top: 16px; }
        .header { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 16px; align-items: center; }
        h1 { margin: 0; font-size: 2rem; }
        .subtitle { color: #6b7280; margin-top: 8px; }
        @media(max-width: 860px) { .grid-2 { grid-template-columns: 1fr; } }
    </style>
</head>
<body>
<div class="page">
    <div class="card">
        <div class="header">
            <div>
                <h1>Dashboard Penjualan</h1>
                <p class="subtitle">Visualisasi tren penjualan, pengaruh kegiatan, dan pengaruh curah hujan.</p>
            </div>
            <div class="badge">Dataset impor: {{ $sales->count() }} baris</div>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h2>Grafik Tren Penjualan</h2>
            <canvas id="trendChart" height="220"></canvas>
            <div class="insight">{{ $trendInsight }}</div>
        </div>
        <div class="card">
            <h2>Pengaruh Kegiatan</h2>
            <canvas id="activityChart" height="220"></canvas>
            <div class="insight">{{ $activityInsight }}</div>
        </div>
    </div>

    <div class="grid grid-2">
        <div class="card">
            <h2>Pengaruh Curah Hujan</h2>
            <canvas id="rainChart" height="220"></canvas>
            <div class="insight">{{ $rainInsight }}</div>
        </div>
        <div class="card">
            <h2>Tabel Data Penjualan</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>Penjualan</th>
                        <th>Kegiatan</th>
                        <th>Curah Hujan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $sale)
                        <tr>
                            <td>{{ $sale->date->format('Y-m-d') }}</td>
                            <td>Rp {{ number_format($sale->sales, 0, ',', '.') }}</td>
                            <td>{{ $sale->activity ? ($sale->activity_name ?: 'Ada') : 'Tidak Ada' }}</td>
                            <td>{{ $sale->rain_level }} ({{ $sale->rainfall_mm }} mm)</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    const trendLabels = @json($trendData->pluck('date')->map(fn($date) => $date->format('Y-m-d')));
    const trendValues = @json($trendData->pluck('total_sales'));
    const activityLabels = ['Tanpa Kegiatan', 'Dengan Kegiatan'];
    const activityValues = [{{ $activityStats['without'] }}, {{ $activityStats['with'] }}];
    const rainLabels = ['Rendah', 'Tinggi'];
    const rainValues = [{{ $rainStats['low'] }}, {{ $rainStats['high'] }}];

    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: trendLabels,
            datasets: [{
                label: 'Penjualan per Tanggal',
                data: trendValues,
                borderColor: '#2563eb',
                backgroundColor: 'rgba(37, 99, 235, 0.15)',
                fill: true,
                tension: 0.25,
                pointRadius: 4,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('activityChart'), {
        type: 'bar',
        data: {
            labels: activityLabels,
            datasets: [{
                label: 'Rata-rata Penjualan',
                data: activityValues,
                backgroundColor: ['#10b981', '#f59e0b'],
                borderRadius: 12,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    new Chart(document.getElementById('rainChart'), {
        type: 'bar',
        data: {
            labels: rainLabels,
            datasets: [{
                label: 'Rata-rata Penjualan',
                data: rainValues,
                backgroundColor: ['#3b82f6', '#ef4444'],
                borderRadius: 12,
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
</script>
</body>
</html>
