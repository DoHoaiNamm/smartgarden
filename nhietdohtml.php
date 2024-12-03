<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Biểu đồ Nhiệt độ</title>
    <!-- Tải thư viện Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        #chartContainer {
            width: 80%;
            margin: auto;
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
    <h2>Biểu đồ Nhiệt độ Trong 3 Ngày</h2>
    <div id="chartContainer">
        <canvas id="temperatureChart"></canvas>
    </div>

    <script>
    // Biến toàn cục để lưu trữ biểu đồ
    let temperatureChart;

    // Hàm định dạng thời gian
    function formatTimestamp(timestamp) {
        const date = new Date(timestamp);
        return date.toLocaleString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit',
            day: '2-digit',
            month: '2-digit',
            year: 'numeric'
        });
    }

    // Lấy dữ liệu và vẽ biểu đồ
    function fetchDataAndRenderChart() {
        fetch('nhietdo.php')
        .then(response => response.json())
        .then(data => {
            // Chuẩn bị dữ liệu
            const labels = data.map(entry => formatTimestamp(entry.local_timestamp));
            const temperatures = data.map(entry => parseFloat(entry.temperature));

            // Lấy context của canvas
            const ctx = document.getElementById('temperatureChart').getContext('2d');

            // Nếu biểu đồ đã tồn tại, hủy trước khi tạo mới
            if (temperatureChart) {
                temperatureChart.destroy();
            }

            // Tạo biểu đồ mới
            temperatureChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nhiệt độ (°C)',
                        data: temperatures,
                        borderColor: '#FF6384',
                        backgroundColor: 'rgba(255,99,132,0.2)',
                        borderWidth: 2,
                        pointRadius: 3,
                        pointBackgroundColor: '#FF6384',
                        fill: true,
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            display: true,
                            labels: {
                                font: {
                                    size: 14
                                }
                            }
                        },
                        tooltip: {
                            mode: 'index',
                            intersect: false
                        }
                    },
                    interaction: {
                        mode: 'nearest',
                        axis: 'x',
                        intersect: false
                    },
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Thời gian',
                                font: {
                                    size: 14
                                }
                            },
                            ticks: {
                                autoSkip: true,
                                maxRotation: 0,
                                minRotation: 0
                            }
                        },
                        y: {
                            title: {
                                display: true,
                                text: 'Nhiệt độ (°C)',
                                font: {
                                    size: 14
                                }
                            },
                            beginAtZero: false
                        }
                    }
                }
            });
        })
        .catch(error => console.error('Lỗi khi lấy dữ liệu:', error));
    }

    // Gọi hàm lần đầu
    fetchDataAndRenderChart();

    // Cập nhật biểu đồ mỗi 5 phút
    setInterval(fetchDataAndRenderChart, 300000); // 300,000ms = 5 phút
    </script>
</body>
</html>
