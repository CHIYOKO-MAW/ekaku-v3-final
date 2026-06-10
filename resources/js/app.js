import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

const defaultPalette = ['#eab308', '#16a34a', '#2563eb', '#dc2626', '#7c3aed', '#0891b2'];

const parseChartConfig = (raw) => {
    if (!raw) {
        return null;
    }

    try {
        return JSON.parse(raw);
    } catch (error) {
        console.error('Invalid chart config:', error);
        return null;
    }
};

const initEkakuCharts = async () => {
    const chartNodes = document.querySelectorAll('[data-ekaku-chart]');
    if (!chartNodes.length) return;

    let chartModule;
    try {
        chartModule = await import('chart.js');
    } catch (error) {
        console.error('Unable to load chart module:', error);
        chartNodes.forEach((canvas) => {
            canvas.classList.add('hidden');

            if (canvas.parentElement?.querySelector('[data-ekaku-chart-error]')) {
                return;
            }

            const message = document.createElement('p');
            message.dataset.ekakuChartError = 'true';
            message.className = 'mt-4 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-sm text-red-700';
            message.textContent = 'Grafik gagal dimuat. Muat ulang halaman untuk mencoba lagi.';
            canvas.parentElement?.appendChild(message);
        });
        return;
    }

    const { default: Chart, ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend } = chartModule;

    Chart.register(ArcElement, BarElement, CategoryScale, LinearScale, Tooltip, Legend);

    chartNodes.forEach((canvas) => {
        const config = parseChartConfig(canvas.getAttribute('data-ekaku-chart'));
        if (!config || !Array.isArray(config.labels) || !Array.isArray(config.values)) {
            return;
        }

        const chartType = canvas.getAttribute('data-chart-type') || 'doughnut';
        const colors = Array.isArray(config.colors) && config.colors.length > 0
            ? config.colors
            : defaultPalette.slice(0, config.values.length);

        const chartInstance = Chart.getChart(canvas);
        if (chartInstance) {
            chartInstance.destroy();
        }

        new Chart(canvas, {
            type: chartType,
            data: {
                labels: config.labels,
                datasets: [
                    {
                        data: config.values,
                        backgroundColor: colors,
                        borderColor: '#ffffff',
                        borderWidth: 2,
                        borderRadius: chartType === 'bar' ? 8 : 0,
                        maxBarThickness: chartType === 'bar' ? 42 : undefined,
                    },
                ],
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 12,
                            boxHeight: 12,
                            usePointStyle: true,
                            pointStyle: 'circle',
                        },
                    },
                },
                scales: chartType === 'bar'
                    ? {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0,
                            },
                        },
                    }
                    : undefined,
            },
        });
    });
};

document.addEventListener('DOMContentLoaded', () => {
    initEkakuCharts().catch((err) => console.error('Chart init failed:', err));
});

document.addEventListener('submit', (e) => {
    const targetButtons = e.submitter
        ? [e.submitter]
        : document.querySelectorAll('.btn-primary, .btn-danger');
    targetButtons.forEach((btn) => {
        if (btn.closest('form')) {
            btn.disabled = true;
            btn.dataset.originalHtml = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';
        }
    });
});

window.addEventListener('unhandledrejection', (event) => {
    const status = event.reason?.status ?? event.reason?.response?.status;
    if (status === 401 || status === 419) {
        window.location.href = '/login';
    }
});

const originalFetch = window.fetch;
window.fetch = async (...args) => {
    const response = await originalFetch(...args);
    if (response.status === 401 || response.status === 419) {
        window.location.href = '/login';
    }
    return response;
};
