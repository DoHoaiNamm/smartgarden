<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['username'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Control Dashboard</title>
  <!-- Thêm thư viện jQuery trước các đoạn mã sử dụng ký hiệu $ -->
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script src="https://unpkg.com/mqtt/dist/mqtt.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

  <link rel="stylesheet" href="codeweb3.css">
  <style>
  </style>
</head>

<body style="background-image: url('back.jpg'); background-size: cover; background-position: center;">


  <div class="container">
    <menu class="menu">
      <button id="home-menu" class="menu__item active" style="--bgColorItem: #ff8c00;">
          <svg class="icon" viewBox="0 0 24 24">
              <path d="M3.8,6.6h16.4"/>
              <path d="M20.2,12.1H3.8"/>
              <path d="M3.8,17.5h16.4"/>
          </svg>
      </button>
  
      <button id="chart-menu" class="menu__item" style="--bgColorItem: #f54888;">
          <svg class="icon" viewBox="0 0 24 24">
              <path d="M6.7,4.8h10.7c0.3,0,0.6,0.2,0.7,0.5l2.8,7.3c0,0.1,0,0.2,0,0.3v5.6c0,0.4-0.4,0.8-0.8,0.8H3.8
              C3.4,19.3,3,19,3,18.5v-5.6c0-0.1,0-0.2,0.1-0.3L6,5.3C6.1,5,6.4,4.8,6.7,4.8z"/>
              <path d="M3.4,12.9H8l1.6,2.8h4.9l1.5-2.8h4.6"/>
          </svg>
      </button>
  
      <button id="data-menu" class="menu__item" style="--bgColorItem: #4343f5;">
          <svg class="icon" viewBox="0 0 24 24">
              <path d="M3.4,11.9l8.8,4.4l8.4-4.4"/>
              <path d="M3.4,16.2l8.8,4.5l8.4-4.5"/>
              <path d="M3.7,7.8l8.6-4.5l8,4.5l-8,4.3L3.7,7.8z"/>
          </svg>
      </button>
  
      <button id="device-tracker-menu" class="menu__item" style="--bgColorItem: #e0b115;">
          <svg class="icon" viewBox="0 0 24 24">
              <path d="M5.1,3.9h13.9c0.6,0,1.2,0.5,1.2,1.2v13.9c0,0.6-0.5,1.2-1.2,1.2H5.1c-0.6,0-1.2-0.5-1.2-1.2V5.1
                C3.9,4.4,4.4,3.9,5.1,3.9z"/>
              <path d="M4.2,9.3h15.6"/>
              <path d="M9.1,9.5v10.3"/>
          </svg>
      </button>
  
      <button class="menu__item" style="--bgColorItem:#e74c3c;" id="logout-menu">
        <svg class="icon" viewBox="0 0 24 24">
          <path d="M10 15l-3-3m0 0l3-3m-3 3h10m-6 9a9 9 0 100-18 9 9 0 000 18z" />
        </svg>
        <span class="menu__label"></span>
      </button>
      
  
      <div class="menu__border"></div>
  </menu>
  
  
    
    
    
    
    <!-- Main content -->
    <div class="main-content">
      <div id="home-content">
        <!-- Thông Tin Section -->
        <section class="info-section">
          <h2 class="section-title">Thông Tin</h2>
          <div class="info-cards">
            <div class="info-card">
              <img src="light.png" alt="Light Icon">
              <h3>Ánh Sáng</h3>
              <p id="light">Đang đo...</p>
            </div>
            <div class="info-card">
              <img src="temperature.png" alt="Temperature Icon">
              <h3>Nhiệt Độ</h3>
              <p id="temperature">Đang đo...</p>
            </div>
            <div class="info-card">
              <img src="humidity.png" alt="Humidity Icon">
              <h3>Độ Ẩm</h3>
              <p id="humidity">Đang đo...</p>
            </div>
            <div class="info-card">
              <img src="water.png" alt="Water Icon">
              <h3>Mực Nước</h3>
              <p id="distance">Đang đo...</p>
            </div>
            <div class="info-card">
              <img src="soil.png" alt="Soil Moisture Icon">
              <h3>Độ Ẩm Đất</h3>
              <p id="soilMoisture">Đang đo...</p>
            </div>
          </div>
        </section>

        <!-- Chức Năng Section -->
        <section class="function-section">
          <h2 class="section-title">Chức năng</h2>
          <div class="function-cards">
            <div class="function-card">
              <h3>Đèn</h3>
              <img id="light_image" src="light_off.png" alt="Đèn">
              <p id="light_status">Đèn đang tắt</p>
              <button id="light_button">Bật Đèn</button>
            </div>
            <div class="function-card">
              <h3>Quạt</h3>
              <img id="fan_image" src="fan_off.png" alt="Fan status">
              <p id="fan_status">Quạt Đang Tắt </p>
              <button id="fan_button">Bật Quạt</button>
            </div>
            <div class="function-card">
              <h3>Máy Bơm</h3>
              <img id="pump_image" src="pump_off.png" alt="Máy Bơm">
              <p id="pump_status">Máy Bơm Đang Tắt</p>
              <button id="pump_button">Bật Máy Bơm</button>
            </div>
            <div class="function-card">
              <h3>Vòi phun</h3>
              <img id="voiphun_image" src="voiphun_off.png" alt="Vòi phun">
              <p id="voiphun_status">Vòi phun Đang Tắt</p>
              <button id="voiphun_button">Bật Vòi phun</button>
            </div>
            <div class="function-card">
              <h3>Chế Độ</h3>
              <img id="mode_image" src="manual.png" alt="Chế độ">
              <p id="mode_status">Chế độ thủ công</p>
              <button id="mode_button">Bật chế độ tự động</button>
            </div>
          </div>
        </section>
      </div>
      <div id="chart-content" style="display: none;">
        <h2>Biểu Đồ Nhiệt Độ, Độ Ẩm Đất, Độ Ẩm Không Khí Trong 3 Ngày</h2>
        <div class="chart-container">
          <canvas id="chart"></canvas>
        </div>
      </div>
      
	  
     <div id="data-content" style="display: none;">
       <h2 style="text-align: center; color: #333;">10 GIÁ TRỊ CẢM BIẾN MỚI NHẤT</h2>
        <table style="width: 80%; margin: 20px auto; border-collapse: collapse; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); background-color: #ffffff; border-radius: 8px; overflow: hidden;">
          <thead style="background-color: #87CEFA; color: #ffffff;">
            <tr>
              <th style="padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">ID</th>
              <th style="padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">Nhiệt độ</th>
              <th style="padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">Độ Ẩm</th>
              <th style="padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">Cường Độ Ánh Sáng</th>
              <th style="padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">Mực nước</th>
              <th style="padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">Độ ẩm đất</th>
              <th style="padding: 15px; text-align: center; border-bottom: 1px solid #ddd;">Thời Gian</th> 
            </tr>
          </thead>
          <tbody id="sensorData">
            <!-- Dữ liệu sẽ được cập nhật tự động bằng AJAX -->
          </tbody>
        </table>

      </div>
      <div id="device-tracker" style="display: none;">
        <h2 class="section-title">Giám Sát Thiết Bị</h2>
        <div class="device-monitor">
            <div class="device">
                <h3>Thiết bị Quạt</h3>
                <p id="fan_current">Dòng: Đang đo...</p>
                <p id="fan_status">Trạng thái: Đang đo...</p>
            </div>
            <div class="device">
                <h3>Máy Phun</h3>
                <p id="sprayer_current">Dòng: Đang đo...</p>
                <p id="sprayer_status">Trạng thái: Đang đo...</p>
            </div>
            <div class="device">
                <h3>Máy Bơm</h3>
                <p id="pump_current">Dòng: Đang đo...</p>
                <p id="pump_status">Trạng thái: Đang đo...</p>
            </div>
        </div>
    </div>
    
      <div id="chart2-content" style="display: none;">
        <h2 class="section-title">Biểu Đồ</h2>
        <!-- Nội dung biểu đồ đã bị xóa -->
      </div>  
    </div>
  </div>
  
  <!-- Chuyển script jQuery lên đầu trước khi sử dụng $ -->
  <!-- Đã thêm ở phần <head> -->
  
  <!-- Các script khác -->
  <script src="codeweb3.js"></script>
  <script>
    function fetchSensorData() {
        $.ajax({
            url: 'get_sensor_data.php', // Đường dẫn đến file PHP vừa tạo
            type: 'GET',
            dataType: 'json',
            success: function(data) {
                let tableBody = '';
                data.forEach(function(row) {
                    tableBody += `
                        <tr>
                            <td>${row.id}</td>
                            <td>${row.temperature}</td>
                            <td>${row.humidity}</td>
                            <td>${row.lux}</td>
                            <td>${row.water_level}</td>
                            <td>${row.soil_moisture}</td>
                            <td>${row.local_timestamp}</td>
                        </tr>
                    `;
                });
                $('#sensorData').html(tableBody);
            },
            error: function() {
                console.error("Lỗi khi tải dữ liệu cảm biến");
            }
        });
    }

    // Gọi hàm fetchSensorData mỗi 5 giây để cập nhật dữ liệu
    setInterval(fetchSensorData, 5000);
    $(document).ready(fetchSensorData);
  </script>
  
  <script>
     // Hàm để định dạng thời gian
function formatTimestamp(timestamp) {
    const date = new Date(timestamp);
    return date.toLocaleString('vi-VN');
}

// Tạo biểu đồ ban đầu
let combinedChart; // Đặt ngoài để dễ cập nhật dữ liệu sau này

// Lấy dữ liệu ban đầu từ PHP
fetch('nhietdo.php')
    .then(response => response.json())
    .then(data => {
        const timestamps = data.map(entry => formatTimestamp(entry.local_timestamp));
        const temperatures = data.map(entry => parseFloat(entry.temperature));
        const humidities = data.map(entry => parseFloat(entry.humidity));
        const soilMoistures = data.map(entry => parseFloat(entry.soil_moisture));

        // Tạo biểu đồ kết hợp
        const ctxCombined = document.getElementById('chart').getContext('2d');
        combinedChart = new Chart(ctxCombined, {
            type: 'line',
            data: {
                labels: timestamps,
                datasets: [
                    {
                        label: 'Nhiệt độ (°C)',
                        data: temperatures,
                        borderColor: 'rgba(255, 99, 132, 1)',
                        backgroundColor: 'rgba(255, 99, 132, 0.2)',
                        yAxisID: 'y-temperature',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                    },
                    {
                        label: 'Độ ẩm không khí (%)',
                        data: humidities,
                        borderColor: 'rgba(54, 162, 235, 1)',
                        backgroundColor: 'rgba(54, 162, 235, 0.2)',
                        yAxisID: 'y-humidity',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                    },
                    {
                        label: 'Độ ẩm đất (%)',
                        data: soilMoistures,
                        borderColor: 'rgba(75, 192, 192, 1)',
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        yAxisID: 'y-soil-moisture',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.3,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Thời gian'
                        },
                        ticks: {
                            maxRotation: 90,
                            minRotation: 45
                        }
                    },
                    'y-temperature': {
                        type: 'linear',
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Nhiệt độ (°C)'
                        }
                    },
                    'y-humidity': {
                        type: 'linear',
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Độ ẩm không khí (%)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    },
                    'y-soil-moisture': {
                        type: 'linear',
                        position: 'right',
                        offset: true,
                        title: {
                            display: true,
                            text: 'Độ ẩm đất (%)'
                        },
                        grid: {
                            drawOnChartArea: false
                        }
                    }
                },
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) label += ': ';
                                if (context.parsed.y !== null) {
                                    label += context.parsed.y;
                                    if (context.dataset.yAxisID === 'y-temperature') label += ' °C';
                                    else label += ' %';
                                }
                                return label;
                            }
                        }
                    },
                    legend: {
                        display: true,
                        position: 'top',
                    }
                }
            }
        });
    })
    .catch(error => console.error('Lỗi khi lấy dữ liệu ban đầu:', error));
// Hàm cập nhật dữ liệu biểu đồ
function updateChartData() {
    fetch('nhietdo.php')
        .then(response => response.json())
        .then(data => {
            const timestamps = data.map(entry => formatTimestamp(entry.local_timestamp));
            const temperatures = data.map(entry => parseFloat(entry.temperature));
            const humidities = data.map(entry => parseFloat(entry.humidity));
            const soilMoistures = data.map(entry => parseFloat(entry.soil_moisture));

            // Cập nhật dữ liệu biểu đồ
            combinedChart.data.labels = timestamps;
            combinedChart.data.datasets[0].data = temperatures;
            combinedChart.data.datasets[1].data = humidities;
            combinedChart.data.datasets[2].data = soilMoistures;

            // Làm mới biểu đồ
            combinedChart.update();
        })
        .catch(error => console.error('Lỗi khi cập nhật dữ liệu:', error));
}

// Cập nhật dữ liệu mỗi 5 phút
setInterval(updateChartData, 300000); // 5 phút

	</script>
  
  <script>
    // Mapping nút menu với nội dung tương ứng
    const menuMap = {
        "home-menu": "home-content",            // Trang chủ
        "chart-menu": "chart-content",          // Biểu đồ
        "data-menu": "data-content",            // Dữ liệu
        "device-tracker-menu": "device-tracker",// Giám sát thiết bị
        "chart2-menu": "chart2-content"         // Biểu đồ 2
    };

    // Lấy thanh hiệu ứng (menu border)
    const menuBorder = document.querySelector(".menu__border");

    // Lấy tất cả menu item
    const menuItems = document.querySelectorAll(".menu__item");

    // Hàm xử lý khi click vào item
    function handleMenuClick(menuItem) {
        // Loại bỏ lớp "active" khỏi tất cả các nút
        menuItems.forEach(item => item.classList.remove("active"));

        // Thêm lớp "active" vào nút được chọn
        menuItem.classList.add("active");

        // Ẩn tất cả nội dung
        Object.values(menuMap).forEach(contentId => {
            document.getElementById(contentId).style.display = "none";
        });

        // Hiển thị nội dung tương ứng
        const contentId = menuMap[menuItem.id];
        if (contentId) {
            document.getElementById(contentId).style.display = "block";
        }

        // Cập nhật vị trí và kích thước thanh hiệu ứng
        updateMenuBorder(menuItem);
    }

    // Hàm cập nhật vị trí và kích thước của thanh hiệu ứng
    function updateMenuBorder(activeItem) {
        const itemRect = activeItem.getBoundingClientRect();
        const menuRect = activeItem.parentElement.getBoundingClientRect();
        menuBorder.style.width = `${itemRect.width}px`;
        menuBorder.style.left = `${itemRect.left - menuRect.left}px`;
        menuBorder.style.bottom = "0px"; // Căn sát đáy
    }

    // Lắng nghe sự kiện click cho từng menu item
    menuItems.forEach(menuItem => {
        menuItem.addEventListener("click", () => handleMenuClick(menuItem));
    });

    // Hiển thị nội dung mặc định (Trang chủ)
    document.getElementById("home-content").style.display = "block";

    // Đặt vị trí ban đầu cho thanh hiệu ứng (mục mặc định active)
    const activeItem = document.querySelector(".menu__item.active");
    if (activeItem) {
        updateMenuBorder(activeItem);
    }

    // Xử lý khi thay đổi kích thước màn hình
    window.addEventListener("resize", () => {
        if (activeItem) {
            updateMenuBorder(activeItem);
        }
    });
    // Chức năng đăng xuất
document.getElementById("logout-menu").addEventListener("click", function () {
    // Ví dụ chuyển hướng khi đăng xuất
    window.location.href = "logout.php"; // Thay bằng endpoint thực sự để xử lý đăng xuất
});

</script>


  <!-- Hiển thị thời gian ở góc dưới bên trái -->
  <div id="timeDisplay"></div>

  <script>
    function updateTime() {
      const now = new Date();
      const options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute: '2-digit', second: '2-digit' };
      document.getElementById('timeDisplay').innerText = now.toLocaleString('vi-VN', options);
    }
    setInterval(updateTime, 1000);
  </script>
  <script>
    // Tải trạng thái thiết bị khi trang được tải
    window.onload = function() {
        fetchStatus(); // Gọi hàm tải trạng thái từ cơ sở dữ liệu khi tải trang
    };

    // Hàm lấy trạng thái từ cơ sở dữ liệu và cập nhật giao diện
    function fetchStatus() {
        fetch('get_data_bai2.php')
            .then(response => response.json())
            .then(data => {
                updateDeviceStatus('light', data['light']);
                updateDeviceStatus('fan', data['fan']);
                updateDeviceStatus('pump', data['pump']);
                updateDeviceStatus('voiphun', data['voiphun']);
            });
    }

    // Hàm cập nhật trạng thái thiết bị trong giao diện
    function updateDeviceStatus(device, status) {
        const deviceImage = document.getElementById(device + "_image");
        const deviceStatus = document.getElementById(device + "_status");
        const deviceButton = document.getElementById(device + "_button");

        const timestamp = new Date().getTime();
        if (status == 1) {
            deviceImage.src = `${device}_on.png?${timestamp}`;
            deviceStatus.textContent = device.charAt(0).toUpperCase() + device.slice(1) + " đang bật";
            deviceButton.textContent = "Tắt " + device.charAt(0).toUpperCase() + device.slice(1);
        } else {
            deviceImage.src = `${device}_off.png?${timestamp}`;
            deviceStatus.textContent = device.charAt(0).toUpperCase() + device.slice(1) + " đang tắt";
            deviceButton.textContent = "Bật " + device.charAt(0).toUpperCase() + device.slice(1);
        }
    }

    // Hàm bật/tắt thiết bị và lưu trạng thái vào cơ sở dữ liệu
    function toggleDevice(device) {
        // Xác định trạng thái hiện tại từ giao diện và đảo ngược nó
        const currentStatus = document.getElementById(device + "_status").textContent.includes("bật") ? 0 : 1;

        // Gửi yêu cầu AJAX để lưu trạng thái
        fetch('save_data_bai2.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `device=${encodeURIComponent(device)}&status=${encodeURIComponent(currentStatus)}`
        })
        .then(response => response.text())
        .then(result => {
            console.log(result);
            // Sau khi lưu xong, gọi lại fetchStatus để đảm bảo cập nhật đúng trạng thái từ CSDL
            fetchStatus();
        })
        .catch(error => {
            console.error("Lỗi khi cập nhật trạng thái:", error);
        });
    }

    // Thêm sự kiện nhấn nút cho mỗi thiết bị
    document.getElementById('light_button').addEventListener('click', function() { toggleDevice('light'); });
    document.getElementById('fan_button').addEventListener('click', function() { toggleDevice('fan'); });
    document.getElementById('pump_button').addEventListener('click', function() { toggleDevice('pump'); });
    document.getElementById('voiphun_button').addEventListener('click', function() { toggleDevice('voiphun'); });
  </script>
</body>
</html>