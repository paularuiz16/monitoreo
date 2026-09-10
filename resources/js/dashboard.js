/**
 * ==========================================================================
 * AvícolaPro Control - Dashboard SCADA Telemetry & Actuator Controls
 * ==========================================================================
 */

document.addEventListener('DOMContentLoaded', () => {
    // 1. Curtain Range Slider Interactive Badge Animation
    const curtainRange = document.getElementById('curtainRange');
    const curtainBadge = document.getElementById('curtainValueBadge');

    if (curtainRange && curtainBadge) {
        curtainRange.addEventListener('input', function () {
            curtainBadge.textContent = this.value + '%';
            // Micro-animation on value shift
            curtainBadge.style.transform = 'scale(1.1)';
            setTimeout(() => {
                curtainBadge.style.transform = 'scale(1)';
            }, 120);
        });
    }

    // 2. Interactive SCADA Line Chart Initialization
    const chartCanvas = document.getElementById('scadaChart');
    if (chartCanvas && window.SCADA_TELEMETRY_DATA) {
        const { labels, temp, humidity, pressure } = window.SCADA_TELEMETRY_DATA;

        new Chart(chartCanvas, {
            type: 'line',
            data: {
                labels: labels,
                datasets: [
                    {
                        label: 'Temp. Interior (°C)',
                        data: temp,
                        borderColor: '#1b6d24',
                        backgroundColor: 'rgba(27, 109, 36, 0.08)',
                        borderWidth: 2.5,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 2,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#1b6d24',
                        yAxisID: 'yTemp'
                    },
                    {
                        label: 'Humedad (%)',
                        data: humidity,
                        borderColor: '#091523',
                        backgroundColor: 'rgba(9, 21, 35, 0.03)',
                        borderWidth: 2,
                        borderDash: [4, 4],
                        fill: false,
                        tension: 0.35,
                        pointRadius: 2,
                        pointHoverRadius: 6,
                        pointBackgroundColor: '#091523',
                        yAxisID: 'yHum'
                    },
                    {
                        label: 'Presión (hPa)',
                        data: pressure,
                        borderColor: '#0284c7',
                        backgroundColor: 'transparent',
                        borderWidth: 1.5,
                        fill: false,
                        tension: 0.35,
                        pointRadius: 1,
                        pointHoverRadius: 5,
                        hidden: true,
                        yAxisID: 'yPress'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                animation: {
                    duration: 1000,
                    easing: 'easeOutQuart'
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#091523',
                        titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                        bodyFont: { family: 'Inter', size: 12 },
                        padding: 12,
                        cornerRadius: 10,
                        displayColors: true
                    }
                },
                scales: {
                    x: {
                        grid: {
                            color: 'rgba(224, 227, 229, 0.4)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            color: '#75777c',
                            maxTicksLimit: 8
                        }
                    },
                    yTemp: {
                        type: 'linear',
                        position: 'left',
                        min: 15,
                        max: 35,
                        grid: {
                            color: 'rgba(224, 227, 229, 0.4)',
                            drawBorder: false
                        },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            color: '#1b6d24',
                            callback: (val) => val + '°C'
                        }
                    },
                    yHum: {
                        type: 'linear',
                        position: 'right',
                        min: 40,
                        max: 90,
                        grid: {
                            drawOnChartArea: false
                        },
                        ticks: {
                            font: { family: 'Inter', size: 11 },
                            color: '#091523',
                            callback: (val) => val + '%'
                        }
                    },
                    yPress: {
                        type: 'linear',
                        display: false,
                        min: 1000,
                        max: 1030
                    }
                }
            }
        });
    }
});

// 3. Quick Actuator Feedback Notifications
window.notifyActuator = function (systemName, message) {
    alert(`[COMANDO SCADA]: ${systemName}\n${message}`);
};
