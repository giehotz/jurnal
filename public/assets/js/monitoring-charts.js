/**
 * Monitoring Dashboard Charts - Chart.js v2 Compatible
 * Handles the rendering of charts on the Admin Monitoring page.
 */

window.MonitoringCharts = {
    defaults: {
        fontFamily: "'Inter', sans-serif",
        fontColor: '#64748B',
        colors: {
            primary: '#0EA5E9',   // Sky 500
            success: '#10B981',   // Emerald 500
            warning: '#F59E0B',   // Amber 500
            danger: '#EF4444',    // Red 500
            info: '#8B5CF6',      // Violet 500
            secondary: '#64748B', // Slate 500
            dark: '#1E293B'       // Slate 800
        }
    },

    init: function (data) {
        console.log('MonitoringCharts init called with data:', data);

        if (typeof Chart === 'undefined') {
            console.error('Chart.js is not loaded!');
            return;
        }

        this.setupDefaults();

        if (data.dailyActivity && Array.isArray(data.dailyActivity) && data.dailyActivity.length > 0) {
            this.initDailyActivityChart(data.dailyActivity);
        } else {
            console.warn('No valid dailyActivity data found');
        }

        if (data.studentAttendance && Object.keys(data.studentAttendance).length > 0) {
            this.initStudentAttendanceChart(data.studentAttendance);
        } else {
            console.warn('No valid studentAttendance data found');
        }

        if (data.classAttendance && Array.isArray(data.classAttendance) && data.classAttendance.length > 0) {
            this.initClassAttendanceChart(data.classAttendance);
        } else {
            console.warn('No valid classAttendance data found');
        }

        if (data.monthlyTrend && Array.isArray(data.monthlyTrend) && data.monthlyTrend.length > 0) {
            this.initMonthlyTrendChart(data.monthlyTrend);
        } else {
            console.warn('No valid monthlyTrend data found');
        }
    },

    setupDefaults: function () {
        // Chart.js v2 syntax
        Chart.defaults.global.defaultFontFamily = this.defaults.fontFamily;
        Chart.defaults.global.defaultFontColor = this.defaults.fontColor;
    },

    initDailyActivityChart: function (data) {
        const ctx = document.getElementById('dailyActivityChart');
        if (!ctx) {
            console.error('dailyActivityChart canvas not found');
            return;
        }

        console.log('Rendering Daily Activity Chart', data);

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
                }),
                datasets: [
                    {
                        label: 'Jurnal',
                        data: data.map(item => item.jurnal || 0),
                        borderColor: this.defaults.colors.primary,
                        backgroundColor: 'rgba(14, 165, 233, 0.1)',
                        pointBackgroundColor: this.defaults.colors.primary,
                        pointBorderColor: '#fff',
                        lineTension: 0.4,
                        fill: true,
                        borderWidth: 2
                    },
                    {
                        label: 'Absensi',
                        data: data.map(item => item.absensi || 0),
                        borderColor: this.defaults.colors.success,
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        pointBackgroundColor: this.defaults.colors.success,
                        pointBorderColor: '#fff',
                        lineTension: 0.4,
                        fill: true,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        fontSize: 12,
                        fontStyle: 'bold'
                    }
                },
                tooltips: {
                    backgroundColor: this.defaults.colors.dark,
                    titleFontFamily: "'Outfit', sans-serif",
                    titleFontSize: 13,
                    bodyFontFamily: "'Inter', sans-serif",
                    bodyFontSize: 12,
                    cornerRadius: 8,
                    xPadding: 12,
                    yPadding: 12,
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            stepSize: 1,
                            padding: 10
                        },
                        gridLines: {
                            borderDash: [2, 4],
                            color: '#F1F5F9',
                            drawBorder: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });
    },

    initStudentAttendanceChart: function (data) {
        const ctx = document.getElementById('studentAttendanceChart');
        if (!ctx) {
            console.error('studentAttendanceChart canvas not found');
            return;
        }

        console.log('Rendering Student Attendance Chart', data);

        const totalHadir = parseInt(data.total_hadir) || 0;
        const totalSakit = parseInt(data.total_sakit) || 0;
        const totalIzin = parseInt(data.total_izin) || 0;
        const totalAlfa = parseInt(data.total_alfa) || 0;
        const total = totalHadir + totalSakit + totalIzin + totalAlfa;

        if (total === 0) {
            console.warn('Student Attendance data is empty');
            return;
        }

        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ['Hadir', 'Sakit', 'Izin', 'Alfa'],
                datasets: [{
                    data: [totalHadir, totalSakit, totalIzin, totalAlfa],
                    backgroundColor: [
                        this.defaults.colors.success,
                        this.defaults.colors.warning,
                        this.defaults.colors.primary,
                        this.defaults.colors.danger
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        fontSize: 12
                    }
                },
                cutoutPercentage: 70
            }
        });
    },

    initClassAttendanceChart: function (data) {
        const ctx = document.getElementById('classAttendanceChart');
        if (!ctx) {
            console.error('classAttendanceChart canvas not found');
            return;
        }

        console.log('Rendering Class Attendance Chart', data);

        if (!Array.isArray(data) || data.length === 0) {
            console.warn('Class Attendance data is empty');
            return;
        }

        const classLabels = data.map(item => item.nama_rombel || 'Unknown');
        const classPercentages = data.map(item => parseFloat(item.avg_persentase || 0).toFixed(1));

        const classColors = classPercentages.map(pct => {
            if (pct < 60) return this.defaults.colors.danger;
            if (pct < 80) return this.defaults.colors.warning;
            return this.defaults.colors.success;
        });

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: classLabels,
                datasets: [{
                    label: 'Persentase Kehadiran (%)',
                    data: classPercentages,
                    backgroundColor: classColors,
                    // borderRadius not supported in v2
                    barPercentage: 0.6,
                    categoryPercentage: 0.8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    display: false
                },
                tooltips: {
                    callbacks: {
                        label: function (tooltipItem, data) {
                            return tooltipItem.yLabel + '%';
                        }
                    },
                    backgroundColor: this.defaults.colors.dark,
                    titleFontFamily: "'Outfit', sans-serif",
                    titleFontSize: 13,
                    bodyFontFamily: "'Inter', sans-serif",
                    bodyFontSize: 12,
                    cornerRadius: 8,
                    xPadding: 12,
                    yPadding: 12
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            max: 100,
                            callback: function (value) {
                                return value + '%';
                            },
                            padding: 10
                        },
                        gridLines: {
                            borderDash: [2, 4],
                            color: '#F1F5F9',
                            drawBorder: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });
    },

    initMonthlyTrendChart: function (data) {
        const ctx = document.getElementById('monthlyTrendChart');
        if (!ctx) {
            console.error('monthlyTrendChart canvas not found');
            return;
        }

        console.log('Rendering Monthly Trend Chart', data);

        if (!Array.isArray(data) || data.length === 0) {
            console.warn('Monthly Trend data is empty');
            return;
        }

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.map(item => item.month || 'Unknown'),
                datasets: [
                    {
                        label: 'Jurnal',
                        data: data.map(item => item.jurnal || 0),
                        borderColor: this.defaults.colors.info,
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        pointBackgroundColor: this.defaults.colors.info,
                        fill: true,
                        lineTension: 0.3,
                        borderWidth: 2
                    },
                    {
                        label: 'Absensi',
                        data: data.map(item => item.absensi || 0),
                        borderColor: this.defaults.colors.secondary,
                        backgroundColor: 'rgba(100, 116, 139, 0.1)',
                        pointBackgroundColor: this.defaults.colors.secondary,
                        fill: true,
                        lineTension: 0.3,
                        borderWidth: 2
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        fontSize: 12,
                        fontStyle: 'bold'
                    }
                },
                tooltips: {
                    backgroundColor: this.defaults.colors.dark,
                    titleFontFamily: "'Outfit', sans-serif",
                    titleFontSize: 13,
                    bodyFontFamily: "'Inter', sans-serif",
                    bodyFontSize: 12,
                    cornerRadius: 8,
                    xPadding: 12,
                    yPadding: 12,
                    mode: 'index',
                    intersect: false
                },
                scales: {
                    yAxes: [{
                        ticks: {
                            beginAtZero: true,
                            padding: 10
                        },
                        gridLines: {
                            borderDash: [2, 4],
                            color: '#F1F5F9',
                            drawBorder: false
                        }
                    }],
                    xAxes: [{
                        gridLines: {
                            display: false
                        }
                    }]
                }
            }
        });
    }
};
