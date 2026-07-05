@extends('backend.layouts.app')
@section('content')
<!-- contentAwal -->

<!-- WELCOME CARD -->
<div class="card mb-4">
    <div class="card-body" style="padding: 24px 28px;">
        <div class="d-flex align-items-center gap-3">           
        <div>
                <h5 class="mb-1" style="font-weight:700; color:var(--dark);">
                    Selamat Datang, {{ auth('admin')->user()->nama }}!
                </h5>
                <p class="mb-0 text-muted" style="font-size:14px;">
                    Anda login sebagai
                    <span class="badge" style="background:var(--primary); color:white; font-size:11px;">
                        @if (auth('admin')->user()->role == 1) Super Admin @else Admin @endif
                    </span>
                    — Berikut ringkasan transaksi toko Anda.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- STATUS SUMMARY CARDS -->
<div class="row g-3 mb-4">

    <!-- Total Transaksi -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100" style="border-left: 4px solid var(--primary);">
            <div class="card-body text-center" style="padding:20px 12px;">
                <div style="width:40px; height:40px; background:rgba(64,192,206,0.12); border-radius:12px; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                    <i class="fa-solid fa-cart-shopping" style="color:var(--primary); font-size:16px;"></i>
                </div>
                <h4 style="font-weight:800; color:var(--dark); margin-bottom:2px;">{{ $totalTransaksi }}</h4>
                <small class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Total</small>
            </div>
        </div>
    </div>

    <!-- Belum Bayar -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100" style="border-left: 4px solid #ff5a5f;">
            <div class="card-body text-center" style="padding:20px 12px;">
                <div style="width:40px; height:40px; background:rgba(255,90,95,0.1); border-radius:12px; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                    <i class="fa-solid fa-clock" style="color:#ff5a5f; font-size:16px;"></i>
                </div>
                <h4 style="font-weight:800; color:var(--dark); margin-bottom:2px;">{{ $statusCounts['belum bayar'] ?? 0 }}</h4>
                <small class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Belum Bayar</small>
            </div>
        </div>
    </div>

    <!-- Pending -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100" style="border-left: 4px solid #ff9f1c;">
            <div class="card-body text-center" style="padding:20px 12px;">
                <div style="width:40px; height:40px; background:rgba(255,159,28,0.1); border-radius:12px; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                    <i class="fa-solid fa-hourglass-half" style="color:#ff9f1c; font-size:16px;"></i>
                </div>
                <h4 style="font-weight:800; color:var(--dark); margin-bottom:2px;">{{ $statusCounts['pending'] ?? 0 }}</h4>
                <small class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Pending</small>
            </div>
        </div>
    </div>

    <!-- Diproses -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100" style="border-left: 4px solid #40C0CE;">
            <div class="card-body text-center" style="padding:20px 12px;">
                <div style="width:40px; height:40px; background:rgba(64,192,206,0.1); border-radius:12px; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                    <i class="fa-solid fa-spinner" style="color:#40C0CE; font-size:16px;"></i>
                </div>
                <h4 style="font-weight:800; color:var(--dark); margin-bottom:2px;">{{ $statusCounts['proses'] ?? 0 }}</h4>
                <small class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Diproses</small>
            </div>
        </div>
    </div>

    <!-- Selesai -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100" style="border-left: 4px solid #2ec4b6;">
            <div class="card-body text-center" style="padding:20px 12px;">
                <div style="width:40px; height:40px; background:rgba(46,196,182,0.1); border-radius:12px; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                    <i class="fa-solid fa-circle-check" style="color:#2ec4b6; font-size:16px;"></i>
                </div>
                <h4 style="font-weight:800; color:var(--dark); margin-bottom:2px;">{{ $statusCounts['selesai'] ?? 0 }}</h4>
                <small class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Selesai</small>
            </div>
        </div>
    </div>

    <!-- Dibatalkan -->
    <div class="col-6 col-md-4 col-lg-2">
        <div class="card h-100" style="border-left: 4px solid #94a3b8;">
            <div class="card-body text-center" style="padding:20px 12px;">
                <div style="width:40px; height:40px; background:rgba(148,163,184,0.12); border-radius:12px; display:flex; align-items:center; justify-content:center; margin:0 auto 10px;">
                    <i class="fa-solid fa-ban" style="color:#94a3b8; font-size:16px;"></i>
                </div>
                <h4 style="font-weight:800; color:var(--dark); margin-bottom:2px;">{{ $statusCounts['batal'] ?? 0 }}</h4>
                <small class="text-muted" style="font-size:11px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Dibatalkan</small>
            </div>
        </div>
    </div>

</div>

<!-- PENDAPATAN CARD -->
<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card" style="background: linear-gradient(135deg, var(--primary) 0%, #2daab8 100%); border:none;">
            <div class="card-body d-flex align-items-center gap-3" style="padding:28px;">
                <div style="width:55px; height:55px; background:rgba(255,255,255,0.2); border-radius:16px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-wallet" style="font-size:24px; color:white;"></i>
                </div>
                <div>
                    <small style="color:rgba(255,255,255,0.8); font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Total Pendapatan</small>
                    <h3 style="color:white; font-weight:800; margin:0;">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card" style="background: linear-gradient(135deg, var(--dark) 0%, #1a1b21 100%); border:none;">
            <div class="card-body d-flex align-items-center gap-3" style="padding:28px;">
                <div style="width:55px; height:55px; background:rgba(255,255,255,0.08); border-radius:16px; display:flex; align-items:center; justify-content:center;">
                    <i class="fa-solid fa-receipt" style="font-size:24px; color:var(--primary);"></i>
                </div>
                <div>
                    <small style="color:rgba(255,255,255,0.6); font-size:12px; font-weight:600; text-transform:uppercase; letter-spacing:0.5px;">Sudah Bayar</small>
                    <h3 style="color:white; font-weight:800; margin:0;">{{ ($statusCounts['sudah bayar'] ?? 0) + ($statusCounts['selesai'] ?? 0) }} Transaksi</h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CHARTS -->
<div class="row g-3 mb-4">

    <!-- LINE CHART: Transaksi Harian -->
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fa-solid fa-chart-line me-2" style="color:var(--primary);"></i>
                    Grafik Transaksi (30 Hari Terakhir)
                </h5>
                @if(count($chartLabels) > 0)
                    <canvas id="transaksiLineChart" height="130"></canvas>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-chart-line" style="font-size:48px; opacity:0.2;"></i>
                        <p class="mt-3">Belum ada data transaksi dalam 30 hari terakhir.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- DOUGHNUT CHART: Status -->
    <div class="col-lg-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">
                    <i class="fa-solid fa-chart-pie me-2" style="color:var(--primary);"></i>
                    Distribusi Status
                </h5>
                @if($totalTransaksi > 0)
                    <canvas id="statusDoughnutChart" height="200"></canvas>
                @else
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-chart-pie" style="font-size:48px; opacity:0.2;"></i>
                        <p class="mt-3">Belum ada data transaksi.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

</div>

<!-- contentAkhir -->

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    // ==================== LINE CHART ====================
    @if(count($chartLabels) > 0)
    const lineCtx = document.getElementById('transaksiLineChart').getContext('2d');

    // Gradien untuk area fill
    const gradient = lineCtx.createLinearGradient(0, 0, 0, 320);
    gradient.addColorStop(0, 'rgba(64, 192, 206, 0.25)');
    gradient.addColorStop(1, 'rgba(64, 192, 206, 0.01)');

    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: @json($chartLabels),
            datasets: [
                {
                    label: 'Jumlah Transaksi',
                    data: @json($chartJumlah),
                    borderColor: '#40C0CE',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#40C0CE',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 7,
                },
                {
                    label: 'Total Penjualan (Rp)',
                    data: @json($chartTotal),
                    borderColor: '#2D2F39',
                    backgroundColor: 'rgba(45, 47, 57, 0.05)',
                    borderWidth: 2,
                    borderDash: [6, 4],
                    fill: false,
                    tension: 0.4,
                    pointRadius: 3,
                    pointBackgroundColor: '#2D2F39',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    yAxisID: 'y1',
                }
            ]
        },
        options: {
            responsive: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: { family: 'Poppins', size: 12 }
                    }
                },
                tooltip: {
                    backgroundColor: '#2D2F39',
                    titleFont: { family: 'Poppins', weight: '600' },
                    bodyFont: { family: 'Poppins' },
                    padding: 12,
                    cornerRadius: 10,
                    callbacks: {
                        label: function(ctx) {
                            if (ctx.datasetIndex === 1) {
                                return 'Total: Rp ' + Number(ctx.raw).toLocaleString('id-ID');
                            }
                            return ctx.dataset.label + ': ' + ctx.raw;
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: { display: false },
                    ticks: {
                        font: { family: 'Poppins', size: 11 },
                        color: '#94a3b8'
                    }
                },
                y: {
                    position: 'left',
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        font: { family: 'Poppins', size: 11 },
                        color: '#94a3b8'
                    },
                    grid: { color: 'rgba(0,0,0,0.04)' },
                    title: {
                        display: true,
                        text: 'Jumlah',
                        font: { family: 'Poppins', size: 12, weight: '600' },
                        color: '#64748b'
                    }
                },
                y1: {
                    position: 'right',
                    beginAtZero: true,
                    grid: { drawOnChartArea: false },
                    ticks: {
                        font: { family: 'Poppins', size: 11 },
                        color: '#94a3b8',
                        callback: function(value) {
                            if (value >= 1000000) return 'Rp' + (value/1000000).toFixed(1) + 'jt';
                            if (value >= 1000) return 'Rp' + (value/1000).toFixed(0) + 'rb';
                            return 'Rp' + value;
                        }
                    },
                    title: {
                        display: true,
                        text: 'Total (Rp)',
                        font: { family: 'Poppins', size: 12, weight: '600' },
                        color: '#64748b'
                    }
                }
            }
        }
    });
    @endif

    // ==================== DOUGHNUT CHART ====================
    @if($totalTransaksi > 0)
    const doughCtx = document.getElementById('statusDoughnutChart').getContext('2d');

    new Chart(doughCtx, {
        type: 'doughnut',
        data: {
            labels: ['Belum Bayar', 'Sudah Bayar', 'Pending', 'Diproses', 'Selesai', 'Dibatalkan'],
            datasets: [{
                data: [
                    {{ $statusCounts['belum bayar'] ?? 0 }},
                    {{ $statusCounts['sudah bayar'] ?? 0 }},
                    {{ $statusCounts['pending'] ?? 0 }},
                    {{ $statusCounts['proses'] ?? 0 }},
                    {{ $statusCounts['selesai'] ?? 0 }},
                    {{ $statusCounts['batal'] ?? 0 }}
                ],
                backgroundColor: [
                    '#ff5a5f',
                    '#2ec4b6',
                    '#ff9f1c',
                    '#40C0CE',
                    '#22c55e',
                    '#94a3b8'
                ],
                borderWidth: 3,
                borderColor: '#ffffff',
                hoverOffset: 8
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 14,
                        font: { family: 'Poppins', size: 11 }
                    }
                },
                tooltip: {
                    backgroundColor: '#2D2F39',
                    titleFont: { family: 'Poppins', weight: '600' },
                    bodyFont: { family: 'Poppins' },
                    padding: 12,
                    cornerRadius: 10,
                }
            }
        }
    });
    @endif

});
</script>

@endsection