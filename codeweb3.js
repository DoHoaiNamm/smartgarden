document.addEventListener("DOMContentLoaded", function () {
    const temperatureTopic = 'nhietdo_DHT22';
    const humidityTopic = 'doam_DHT22';
    const maybomControlTopic = 'maybom_control';
    const quatControlTopic = 'quat_control';
    const khoangcachTopic = 'khoangcach_untra';
    const doamdatTopic = 'doamdat_sensor';
    const lightControlTopic = 'den_control';
    const voiphuntopic = 'voiphun_control';
    const dosangtopic = 'dosang';
    const modetopic = 'running-mode';
    const dong_maybom = 'dong_Maybom';
    const dong_quat = 'dong_Quat';
    const dong_voiphun = 'dong_Voiphun';

    const host = mqtt.connect('ws://broker.emqx.io:8083/mqtt');

    let currentMode = '1'; // Mặc định là chế độ thủ công

    host.on('connect', function () {
        console.log('Connected to broker');
        host.subscribe(temperatureTopic);
        host.subscribe(humidityTopic);
        host.subscribe(khoangcachTopic);
        host.subscribe(doamdatTopic);
        host.subscribe(maybomControlTopic);
        host.subscribe(quatControlTopic);
        host.subscribe(lightControlTopic);
        host.subscribe(voiphuntopic);
        host.subscribe(dosangtopic);
        host.subscribe(modetopic);
        host.subscribe(dong_quat);
        host.subscribe(dong_maybom);
        host.subscribe(dong_voiphun);
    });

    host.on('error', function (error) {
        console.error('Connection error:', error);
    });

    host.on('message', function (topic, message) {
        const data = message.toString();
        console.log(`Received message on topic "${topic}": ${data}`);

        if (topic === temperatureTopic) {
            const temperature = data.match(/temperature:\s*([\d.]+)/);
            const temperatureElem = document.getElementById('temperature');
            if (temperatureElem && temperature) {
                temperatureElem.textContent = `${temperature[1]} °C`;
            } else {
                console.warn(`Could not parse temperature from data: ${data}`);
            }
        }

        if (topic === humidityTopic) {
            const humidity = data.match(/humidity:\s*([\d.]+)/);
            const humidityElem = document.getElementById('humidity');
            if (humidityElem && humidity) {
                humidityElem.textContent = `${humidity[1]} %`;
            } else {
                console.warn(`Could not parse humidity from data: ${data}`);
            }
        }

        if (topic === dosangtopic) {
            const light = data.match(/lux:\s*([\d.]+)/);
            const lightElem = document.getElementById('light');
            if (lightElem && light) {
                const luxValue = parseFloat(light[1]);
                lightElem.textContent = luxValue > 1 ? "Sáng" : "Tối";
            } else {
                console.warn(`Could not parse light intensity from data: ${data}`);
            }
        }

        if (topic === khoangcachTopic) {
            const distance = data.match(/Khoảng cách:\s*([\d.]+)\s*%/);
            const distanceElem = document.getElementById('distance');
            if (distanceElem && distance) {
                distanceElem.textContent = `${distance[1]} %`;
            } else {
                console.warn(`Could not parse distance from data: ${data}`);
            }
        }

        if (topic === doamdatTopic) {
            const soilMoisture = data.match(/Độ ẩm đất:\s*([\d.]+)\s*%/);
            const soilMoistureElem = document.getElementById('soilMoisture');
            if (soilMoistureElem && soilMoisture) {
                soilMoistureElem.textContent = `${soilMoisture[1]} %`;
                // Nếu bạn có hàm updateChart thì bỏ comment dòng dưới
                // updateChart(soilMoistureChart, getTimestamp(), parseFloat(soilMoisture[1]));
            } else {
                console.warn(`Could not parse soil moisture from data: ${data}`);
            }
        }

        if (topic === maybomControlTopic) {
            if (data.includes("ON1")) {
                updatePumpState(true);
            } else if (data.includes("OFF1")) {
                updatePumpState(false);
            }
        }

        if (topic === quatControlTopic) {
            if (data.includes("ON2")) {
                updateFanState(true);
            } else if (data.includes("OFF2")) {
                updateFanState(false);
            }
        }

        if (topic === lightControlTopic) {
            if (data.includes("ON3")) {
                updateLightState(true);
            } else if (data.includes("OFF3")) {
                updateLightState(false);
            }
        }

        if (topic === voiphuntopic) {
            if (data.includes("ON4")) {
                updateVoiphunState(true);
            } else if (data.includes("OFF4")) {
                updateVoiphunState(false);
            }
        }

        if (topic === modetopic) {
            if (data.includes("MODE: 1")) {
                currentMode = '1';
                updateModeState(currentMode);
            } else if (data.includes("MODE: 2")) {
                currentMode = '2';
                updateModeState(currentMode);
            }
        }
// Xử lý dữ liệu từ topic "dong_quat"
if (topic === dong_quat) {
    // Điều chỉnh biểu thức chính quy để khớp đúng dữ liệu
    const voltageMatch = data.match(/FAN_voltage:([\d.]+)/);
    const currentMatch = data.match(/FAN_current:([\d.]+)/);

    if (currentMatch) {
        const current = parseFloat(currentMatch[1]);
        // Kiểm tra dòng điện và hiển thị trạng thái quạt
        if (current > 1) {
            document.getElementById('fan_status').textContent = "Quạt đang hoạt động";
        } else {
            document.getElementById('fan_status').textContent = "Quạt không hoạt động";
        }
    } else {
        console.log("Không tìm thấy giá trị dòng.");
    }
}

// Xử lý dữ liệu từ topic "dong_Maybom"
if (topic === dong_maybom) {
    // Điều chỉnh biểu thức chính quy để khớp đúng dữ liệu
    const currentMatch = data.match(/PUMP_current:([\d.]+)/);

    if (currentMatch) {
        const current = parseFloat(currentMatch[1]);
        // Kiểm tra dòng điện và hiển thị trạng thái máy bơm
        if (current > 1) {
            document.getElementById('pump_status').textContent = "Máy bơm đang hoạt động";
        } else {
            document.getElementById('pump_status').textContent = "Máy bơm không hoạt động";
        }
    } else {
        console.log("Không tìm thấy giá trị dòng cho máy bơm.");
    }
}
// Xử lý dữ liệu từ topic "dong_voiphun"
if (topic === dong_voiphun) {
    // Điều chỉnh biểu thức chính quy để khớp đúng dữ liệu
    const currentMatch = data.match(/SPRAY_current:([\d.]+)/);

    if (currentMatch) {
        const current = parseFloat(currentMatch[1]);
        // Kiểm tra dòng điện và hiển thị trạng thái máy phun
        if (current > 1) {
            document.getElementById('sprayer_status').textContent = "Máy phun đang hoạt động";
        } else {
            document.getElementById('sprayer_status').textContent = "Máy phun không hoạt động";
        }
    } else {
        console.log("Không tìm thấy giá trị dòng cho máy phun.");
    }
}
    });

    function updatePumpState(isOn) {
        const pumpStatusElem = document.getElementById('pump_status');
        const pumpImageElem = document.getElementById('pump_image');
        const pumpButton = document.getElementById('pump_button');

        const modeText = currentMode === '2' ? "Tự Động" : "Thủ Công";

        if (isOn) {
            pumpStatusElem.textContent = `Máy Bơm (${modeText}): ON1`;
            pumpImageElem.src = 'pump_on.png';
            if (pumpButton && currentMode === '1') pumpButton.textContent = 'Tắt Máy Bơm';
        } else {
            pumpStatusElem.textContent = `Máy Bơm (${modeText}): OFF1`;
            pumpImageElem.src = 'pump_off.png';
            if (pumpButton && currentMode === '1') pumpButton.textContent = 'Bật Máy Bơm';
        }
    }

    function updateFanState(isOn) {
        const fanStatusElem = document.getElementById('fan_status');
        const fanImageElem = document.getElementById('fan_image');
        const fanButton = document.getElementById('fan_button');

        const modeText = currentMode === '2' ? "Tự Động" : "Thủ Công";

        if (isOn) {
            fanStatusElem.textContent = `Quạt (${modeText}): ON2`;
            fanImageElem.src = 'fan_on.png';
            if (fanButton && currentMode === '1') fanButton.textContent = 'Tắt Quạt';
        } else {
            fanStatusElem.textContent = `Quạt (${modeText}): OFF2`;
            fanImageElem.src = 'fan_off.png';
            if (fanButton && currentMode === '1') fanButton.textContent = 'Bật Quạt';
        }
    }

    function updateLightState(isOn) {
        const lightStatusElem = document.getElementById('light_status');
        const lightImageElem = document.getElementById('light_image');
        const lightButton = document.getElementById('light_button');

        const modeText = currentMode === '2' ? "Tự Động" : "Thủ Công";

        if (isOn) {
            lightStatusElem.textContent = `Đèn (${modeText}): ON3`;
            lightImageElem.src = 'light_on.png';
            if (lightButton && currentMode === '1') lightButton.textContent = 'Tắt Đèn';
        } else {
            lightStatusElem.textContent = `Đèn (${modeText}): OFF3`;
            lightImageElem.src = 'light_off.png';
            if (lightButton && currentMode === '1') lightButton.textContent = 'Bật Đèn';
        }
    }

    function updateVoiphunState(isOn) {
        const voiphunStatusElem = document.getElementById('voiphun_status');
        const voiphunImageElem = document.getElementById('voiphun_image');
        const voiphunButton = document.getElementById('voiphun_button');

        const modeText = currentMode === '2' ? "Tự Động" : "Thủ Công";

        if (isOn) {
            voiphunStatusElem.textContent = `Vòi phun (${modeText}): ON4`;
            voiphunImageElem.src = 'voiphun_on.png';
            if (voiphunButton && currentMode === '1') voiphunButton.textContent = 'Tắt Vòi Phun';
        } else {
            voiphunStatusElem.textContent = `Vòi phun (${modeText}): OFF4`;
            voiphunImageElem.src = 'voiphun_off.png';
            if (voiphunButton && currentMode === '1') voiphunButton.textContent = 'Bật Vòi Phun';
        }
    }

    function updateModeState(modeValue) {
        const modeStatusElem = document.getElementById('mode_status');
        const modeImageElem = document.getElementById('mode_image');
        const modeButton = document.getElementById('mode_button');

        if (modeValue === '1') {
            modeStatusElem.textContent = 'Chế độ thủ công';
            modeImageElem.src = 'manual.png';
            if (modeButton) modeButton.textContent = 'Bật chế độ tự động';

            // Kích hoạt các nút điều khiển thiết bị
            enableDeviceButtons(true);
        } else if (modeValue === '2') {
            modeStatusElem.textContent = 'Chế độ tự động';
            modeImageElem.src = 'auto.png';
            if (modeButton) modeButton.textContent = 'Bật chế độ thủ công';

            // Vô hiệu hóa các nút điều khiển thiết bị
            enableDeviceButtons(false);
        }

        // Tắt các thiết bị khi chuyển chế độ
        host.publish(maybomControlTopic, 'OFF1');
        host.publish(quatControlTopic, 'OFF2');
        host.publish(lightControlTopic, 'OFF3');
        host.publish(voiphuntopic, 'OFF4');
    }

    function enableDeviceButtons(enable) {
        const deviceButtons = ['pump_button', 'fan_button', 'light_button', 'voiphun_button'];
        deviceButtons.forEach(id => {
            const button = document.getElementById(id);
            if (button) {
                button.disabled = !enable;
            }
        });
    }

    // Xử lý sự kiện click cho các nút điều khiển thiết bị
    const pumpButton = document.getElementById('pump_button');
    if (pumpButton) {
        pumpButton.addEventListener('click', function () {
            if (currentMode === '1') {
                const isOn = pumpButton.textContent.includes("Bật");
                host.publish(maybomControlTopic, isOn ? 'ON1' : 'OFF1');
            } else {
                alert('Chuyển sang chế độ thủ công để điều khiển thiết bị.');
            }
        });
    }

    const fanButton = document.getElementById('fan_button');
    if (fanButton) {
        fanButton.addEventListener('click', function () {
            if (currentMode === '1') {
                const isOn = fanButton.textContent.includes("Bật");
                host.publish(quatControlTopic, isOn ? 'ON2' : 'OFF2');
            } else {
                alert('Chuyển sang chế độ thủ công để điều khiển thiết bị.');
            }
        });
    }

    const lightButton = document.getElementById('light_button');
    if (lightButton) {
        lightButton.addEventListener('click', function () {
            if (currentMode === '1') {
                const isOn = lightButton.textContent.includes("Bật");
                host.publish(lightControlTopic, isOn ? 'ON3' : 'OFF3');
            } else {
                alert('Chuyển sang chế độ thủ công để điều khiển thiết bị.');
            }
        });
    }

    const voiphunButton = document.getElementById('voiphun_button');
    if (voiphunButton) {
        voiphunButton.addEventListener('click', function () {
            if (currentMode === '1') {
                const isOn = voiphunButton.textContent.includes("Bật");
                host.publish(voiphuntopic, isOn ? 'ON4' : 'OFF4');
            } else {
                alert('Chuyển sang chế độ thủ công để điều khiển thiết bị.');
            }
        });
    }

    const modeButton = document.getElementById('mode_button');
    if (modeButton) {
        modeButton.addEventListener('click', function () {
            const newMode = currentMode === '1' ? '2' : '1';
            host.publish(modetopic, `MODE: ${newMode}`);
            // Không cập nhật trực tiếp currentMode và giao diện ở đây
            // Việc cập nhật sẽ được xử lý khi nhận được tin nhắn từ broker
        });
    }

    function updateTime() {
        const timeDisplayElem = document.getElementById('timeDisplay');
        const now = new Date();
        const options = {
            year: 'numeric', month: 'long', day: 'numeric',
            hour: '2-digit', minute: '2-digit', second: '2-digit'
        };

        if (timeDisplayElem) {
            timeDisplayElem.innerText = now.toLocaleString('vi-VN', options);
        }
    }

    setInterval(updateTime, 1000);
});
 
