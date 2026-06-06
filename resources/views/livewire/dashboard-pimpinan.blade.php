<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <h2 class="text-2xl font-bold text-gray-800 tracking-tight">Dashboard Pimpinan</h2>
        <span class="text-sm font-medium text-gray-500 bg-white px-3 py-1 rounded-full shadow-sm">Real-time Monitoring</span>
    </div>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Card 1 -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-blue-500 transform transition hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Aset Fisik</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalAset, 0, ',', '.') }} <span class="text-base font-medium text-gray-500">Unit</span></p>
                </div>
                <div class="w-12 h-12 bg-blue-50 rounded-full flex items-center justify-center text-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                </div>
            </div>
        </div>

        <!-- Card 2 -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-green-500 transform transition hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Total Habis Pakai</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalHabisPakai, 0, ',', '.') }} <span class="text-base font-medium text-gray-500">Item</span></p>
                </div>
                <div class="w-12 h-12 bg-green-50 rounded-full flex items-center justify-center text-green-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                </div>
            </div>
        </div>

        <!-- Card 3 -->
        <div class="bg-white rounded-xl shadow-md p-6 border-l-4 border-red-500 transform transition hover:-translate-y-1 hover:shadow-lg">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Aset Rusak / Servis</p>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalAsetRusakServis, 0, ',', '.') }} <span class="text-base font-medium text-gray-500">Unit</span></p>
                </div>
                <div class="w-12 h-12 bg-red-50 rounded-full flex items-center justify-center text-red-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">
        <!-- Chart 1: Donut -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-md font-bold text-gray-700 border-b pb-3 mb-4">Proporsi Kondisi Aset</h3>
            <div id="donut-chart" class="flex justify-center" wire:ignore></div>
            @if(empty($chart1['series']))
                <p class="text-center text-gray-400 text-sm py-10">Data aset belum tersedia.</p>
            @endif
        </div>

        <!-- Chart 2: Bar -->
        <div class="bg-white rounded-xl shadow-md p-6">
            <h3 class="text-md font-bold text-gray-700 border-b pb-3 mb-4">Tren Barang Masuk vs Keluar (6 Bulan)</h3>
            <div id="bar-chart" wire:ignore></div>
        </div>
    </div>

    <!-- Memanggil ApexCharts via CDN -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <script>
        document.addEventListener('livewire:init', function () {
            
            // --- Inisiasi Donut Chart ---
            const chart1Data = @js($chart1);
            if(chart1Data.series.length > 0) {
                const donutOptions = {
                    series: chart1Data.series,
                    labels: chart1Data.labels,
                    chart: {
                        type: 'donut',
                        height: 350,
                        fontFamily: 'Inter, sans-serif',
                    },
                    colors: ['#3b82f6', '#10b981', '#ef4444', '#f59e0b', '#6b7280'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '70%',
                            }
                        }
                    },
                    dataLabels: {
                        enabled: true,
                        formatter: function (val) {
                            return Math.round(val) + "%"
                        }
                    },
                    legend: {
                        position: 'bottom'
                    }
                };
                const donutChart = new ApexCharts(document.querySelector("#donut-chart"), donutOptions);
                donutChart.render();
            }

            // --- Inisiasi Bar Chart ---
            const chart2Data = @js($chart2);
            const barOptions = {
                series: [{
                    name: 'Barang Masuk',
                    data: chart2Data.masuk
                }, {
                    name: 'Barang Keluar (Distribusi)',
                    data: chart2Data.keluar
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    fontFamily: 'Inter, sans-serif',
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        horizontal: false,
                        columnWidth: '55%',
                        borderRadius: 4
                    },
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    show: true,
                    width: 2,
                    colors: ['transparent']
                },
                xaxis: {
                    categories: chart2Data.labels,
                },
                yaxis: {
                    title: {
                        text: 'Jumlah Quantity (Qty)'
                    }
                },
                fill: {
                    opacity: 1
                },
                colors: ['#10b981', '#ef4444'], // Hijau untuk Masuk, Merah untuk Keluar
                tooltip: {
                    y: {
                        formatter: function (val) {
                            return val + " items"
                        }
                    }
                }
            };
            const barChart = new ApexCharts(document.querySelector("#bar-chart"), barOptions);
            barChart.render();
        });
    </script>
</div>
