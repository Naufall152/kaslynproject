<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-slate-900 leading-tight">
                Admin Dashboard
            </h2>
            <div class="text-sm text-slate-500">
                Panel Admin Kaslyn
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="mb-6 rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('success') }}
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-slate-500">Total UKM (User)</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $totalUsers }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-slate-500">Subscription Aktif</p>
                    <p class="mt-2 text-2xl font-extrabold text-emerald-700">{{ $activeSubs }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-slate-500">Payment Pending</p>
                    <p class="mt-2 text-2xl font-extrabold text-yellow-600">{{ $pendingPayments }}</p>
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <p class="text-sm text-slate-500">Total Transaksi</p>
                    <p class="mt-2 text-2xl font-extrabold text-slate-900">{{ $totalTransactions }}</p>
                </div>
            </div>

            <!-- Visitor Analytics Chart -->
            <div class="mt-8">
                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Statistik Pengunjung</h3>
                            <p class="text-sm text-slate-500">Grafik kunjungan website 30 hari terakhir</p>
                            <p class="text-xs text-slate-400 mt-1">
                                💡 <strong>Pengunjung Unik:</strong> Berdasarkan session browser (lebih akurat dari IP address)
                            </p>
                        </div>
                        <select id="chartPeriod" class="px-3 py-2 border border-gray-300 rounded-lg text-sm">
                            <option value="7">7 hari</option>
                            <option value="30" selected>30 hari</option>
                            <option value="90">90 hari</option>
                        </select>
                    </div>

                    <!-- Analytics Actions -->
                    <div class="flex gap-2 mb-4">
                        <button id="refreshChart" class="px-3 py-1.5 bg-blue-600 text-white text-sm rounded hover:bg-blue-700">
                            🔄 Refresh
                        </button>
                        <button id="viewDetails" class="px-3 py-1.5 bg-green-600 text-white text-sm rounded hover:bg-green-700">
                            📊 Detail Analytics
                        </button>
                    </div>

                    <!-- Chart Stats -->
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600" id="totalVisitors">-</p>
                            <p class="text-sm text-slate-500">Total Kunjungan</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600" id="uniqueVisitors">-</p>
                            <p class="text-sm text-slate-500">Pengunjung Unik</p>
                            <p class="text-xs text-slate-400">Berdasarkan Session</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600" id="todayVisitors">-</p>
                            <p class="text-sm text-slate-500">Hari Ini</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-orange-600" id="yesterdayVisitors">-</p>
                            <p class="text-sm text-slate-500">Kemarin</p>
                        </div>
                    </div>

                    <!-- Chart Canvas -->
                    <div class="relative">
                        <canvas id="visitorChart" width="400" height="200"></canvas>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex flex-wrap gap-2">
                <a href="{{ route('admin.users.index') }}"
                   class="px-4 py-2 rounded-lg bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700">
                    Kelola User
                </a>
                <a href="{{ route('admin.transactions.index') }}"
                   class="px-4 py-2 rounded-lg border border-emerald-200 text-emerald-700 text-sm font-semibold hover:bg-emerald-50">
                    Lihat Transaksi
                </a>
                <a href="{{ route('admin.plans.index') }}"
                   class="px-4 py-2 rounded-lg border border-emerald-200 text-emerald-700 text-sm font-semibold hover:bg-emerald-50">
                    Manajemen Paket
                </a>
            </div>

        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        let visitorChart;

        document.addEventListener('DOMContentLoaded', function() {
            loadVisitorStats(30);

            // Handle period change
            document.getElementById('chartPeriod').addEventListener('change', function() {
                loadVisitorStats(this.value);
            });

            // Handle refresh button
            document.getElementById('refreshChart').addEventListener('click', function() {
                const period = document.getElementById('chartPeriod').value;
                loadVisitorStats(period);
                showNotification('Chart refreshed!', 'success');
            });

            // Handle view details button
            document.getElementById('viewDetails').addEventListener('click', function() {
                const period = document.getElementById('chartPeriod').value;
                loadDetailedAnalytics(period);
            });
        });

        function loadVisitorStats(days) {
            fetch(`/admin/analytics/visitor-stats?days=${days}`)
                .then(response => response.json())
                .then(data => {
                    updateChart(data);
                    updateStats(data);
                })
                .catch(error => {
                    console.error('Error loading visitor stats:', error);
                });
        }

        function updateChart(data) {
            const ctx = document.getElementById('visitorChart').getContext('2d');

            if (visitorChart) {
                visitorChart.destroy();
            }

            visitorChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.labels,
                    datasets: [{
                        label: 'Kunjungan',
                        data: data.data,
                        borderColor: 'rgb(16, 185, 129)',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointBackgroundColor: 'rgb(16, 185, 129)',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        pointHoverRadius: 6
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleColor: '#fff',
                            bodyColor: '#fff',
                            cornerRadius: 8,
                            displayColors: false
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        }
                    },
                    interaction: {
                        intersect: false,
                        mode: 'index'
                    }
                }
            });
        }

        function loadDetailedAnalytics(days) {
            fetch(`/admin/analytics/detailed?days=${days}`)
                .then(response => response.json())
                .then(data => {
                    showDetailedModal(data);
                })
                .catch(error => {
                    console.error('Error loading detailed analytics:', error);
                    showNotification('Error loading detailed analytics', 'error');
                });
        }

        function showDetailedModal(data) {
            const modal = document.createElement('div');
            modal.className = 'fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50';
            modal.innerHTML = `
                <div class="bg-white rounded-lg p-6 max-w-4xl w-full mx-4 max-h-[80vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold">Detail Analytics - ${data.period}</h3>
                        <button onclick="this.closest('.fixed').remove()" class="text-gray-500 hover:text-gray-700">✕</button>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 p-3 rounded">
                            <div class="text-2xl font-bold text-blue-600">${data.summary.total_visits}</div>
                            <div class="text-sm text-blue-800">Total Kunjungan</div>
                        </div>
                        <div class="bg-green-50 p-3 rounded">
                            <div class="text-2xl font-bold text-green-600">${data.summary.total_unique_sessions}</div>
                            <div class="text-sm text-green-800">Pengunjung Unik (Session)</div>
                        </div>
                        <div class="bg-purple-50 p-3 rounded">
                            <div class="text-2xl font-bold text-purple-600">${data.summary.total_unique_ips}</div>
                            <div class="text-sm text-purple-800">IP Address Unik</div>
                        </div>
                        <div class="bg-yellow-50 p-3 rounded">
                            <div class="text-sm font-semibold text-yellow-800">Rata-rata Harian</div>
                            <div class="text-lg text-yellow-600">${data.summary.avg_daily_visits} kunjungan</div>
                        </div>
                        <div class="bg-indigo-50 p-3 rounded">
                            <div class="text-sm font-semibold text-indigo-800">Rata-rata Unik</div>
                            <div class="text-lg text-indigo-600">${data.summary.avg_unique_sessions} session/hari</div>
                        </div>
                        <div class="bg-red-50 p-3 rounded">
                            <div class="text-sm font-semibold text-red-800">Periode</div>
                            <div class="text-sm text-red-600">${data.start_date} - ${data.end_date}</div>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm border">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="border px-3 py-2 text-left">Tanggal</th>
                                    <th class="border px-3 py-2 text-center">Total Kunjungan</th>
                                    <th class="border px-3 py-2 text-center">Pengunjung Unik (Session)</th>
                                    <th class="border px-3 py-2 text-center">IP Unik</th>
                                </tr>
                            </thead>
                            <tbody>
                                ${data.stats.map(stat => `
                                    <tr class="hover:bg-gray-50">
                                        <td class="border px-3 py-2">${new Date(stat.visit_date).toLocaleDateString('id-ID')}</td>
                                        <td class="border px-3 py-2 text-center">${stat.total_visits}</td>
                                        <td class="border px-3 py-2 text-center">${stat.unique_sessions}</td>
                                        <td class="border px-3 py-2 text-center">${stat.unique_ips}</td>
                                    </tr>
                                `).join('')}
                            </tbody>
                        </table>
                    </div>
                </div>
            `;
            document.body.appendChild(modal);
        }

        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-4 py-2 rounded shadow-lg z-50 ${
                type === 'success' ? 'bg-green-500 text-white' :
                type === 'error' ? 'bg-red-500 text-white' :
                'bg-blue-500 text-white'
            }`;
            notification.textContent = message;
            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        function updateStats(data) {
            document.getElementById('totalVisitors').textContent = data.total.toLocaleString();
            document.getElementById('uniqueVisitors').textContent = data.unique.toLocaleString();
            document.getElementById('todayVisitors').textContent = data.today.toLocaleString();
            document.getElementById('yesterdayVisitors').textContent = data.yesterday.toLocaleString();
        }
    </script>
</x-app-layout>
