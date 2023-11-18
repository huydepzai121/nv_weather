<!-- BEGIN: main -->
<div class="weather-search-container">
    <div class="weather-search-header">Dự báo thời tiết</div>
    <form method="post" action="" id="weather-form" class="weather-form">
        <div class="form-group">
            <input type="hidden" name="id" value="{WEATHER.id}">
            <select class="form-control" name="id_city" id="id_city">
                <!-- BEGIN: city_loop -->
                <option value="{CITY.name}" {CITY.selected}>{CITY.name}</option>
                <!-- END: city_loop -->
            </select>
        </div>
        <button class="btn btn-primary" type="submit" name="submit">Tìm kiếm</button>
    </form>
</div>

<!-- IF !IS_SUBMIT -->
<div class="scroll-table">
<div class="weather-table-container">
    <table class="weather-table table-striped">
        <!-- BEGIN: table_loop -->
        <tr>
            <td class="text-center">{WEATHER.name}</td>
            <td class="text-center">{WEATHER.date_forecast}</td>
            <td class="weather-info">
                <img src="{WEATHER.avatar}" alt="Weather Icon" class="weather-icon">
                <div class="weather-details">
                    <div class="temperature-range">{WEATHER.low_temperature}°C - {WEATHER.high_temperature}°C</div>
                    <div class="weather-description">{WEATHER.description}</div>
                </div>
            </td>
        </tr>
        <!-- END: table_loop -->
    </table>
</div>
</div>
<!-- ELSE -->
<div class="weather-forecast-container">
    <!-- BEGIN: loop -->
    <div class="weather-card">
        <div class="date">{WEATHER.date_forecast}</div>
        <img src="{WEATHER.avatar}" alt="" class="weather-icon">
        <div class="temperature">
            <span>Nhiệt độ: </span>
            <span class="high">{WEATHER.high_temperature}°C -</span>
            <span class="low">{WEATHER.low_temperature}°C</span>
        </div>
        <div class="additional-info">
            <span class="humidity">Lượng mưa: {WEATHER.rain}mm</span></br>
            <span class="wind">Tốc độ gió: {WEATHER.wind_speed}m/s</span>
        </div>
        <div class="description">{WEATHER.description}</div>
    </div>
    <!-- END: loop -->
</div>

<!-- Styles và scripts cần thiết cho main.tpl -->
<style>
    .weather-search-container {
        background: linear-gradient(to right, #e6f7ff, #b3e0ff);
        padding: 20px;
        border-radius: 5px;
        margin-bottom: 20px;
    }

    .weather-search-header {
        font-size: 24px;
        color: #333;
        margin-bottom: 15px;
        text-align: center;
    }

    .weather-form .form-group {
        margin-bottom: 15px;
    }

    .weather-table-container {
        overflow-x: auto;
    }

    .weather-table {
        width: 100%;
        border-collapse: collapse;
    }

    .weather-table td {
        padding: 10px;
        border: 1px solid #ddd;
        text-align: center;
    }

    .weather-info {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .weather-icon {
        width: 50px;
        margin-right: 10px;
    }

    .weather-details .temperature-range {
        font-weight: bold;
    }

    .weather-forecast-container {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-around;
        padding: 10px;
    }

    .weather-card {
        border: 1px solid #ddd;
        border-radius: 10px;
        background-color: #f8f8f8;
        box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        padding: 15px;
        margin: 10px;
        width: 200px;
        text-align: center;
    }

    .weather-date {
        font-size: 14px;
        color: #666;
        margin-bottom: 8px;
    }

    .weather-icon img {
        width: 60px;
        height: auto;
        margin-bottom: 8px;
    }

    .weather-temperature {
        font-size: 16px;
        margin-bottom: 8px;
    }

    .temp-high {
        color: #ff6347; /* Tomato red for high temperature */
        font-weight: bold;
    }

    .temp-low {
        color: #1e90ff; /* Dodger blue for low temperature */
        font-weight: bold;
    }

    .weather-details {
        font-size: 14px;
        color: #333;
    }

    .humidity,
    .wind-speed {
        margin-bottom: 4px;
    }

    .weather-desc {
        font-style: italic;
    }
    .scroll-table{
        overflow-y: scroll;
        max-height: 1024px;
    }
</style>
<script>
    document.getElementById("weather-form").addEventListener("submit", function(event) {
        // Ngăn chặn việc submit form mặc định
        event.preventDefault();

        // Kiểm tra xem form đã được submit hay chưa
        var isSubmit = /* Thực hiện kiểm tra ở đây */;

        if (isSubmit) {
            // Nếu form đã được submit, ẩn bảng bằng cách đặt display thành none
            document.getElementById("weather-table").style.display = "none";

            // Đặt chiều cao của scrollable area thành auto sau khi submit
            document.querySelector(".scroll-table").style.height = "auto";
        }
    });

    // JavaScript để đặt chiều cao của scrollable area
    var scrollTable = document.querySelector(".scroll-table");
    scrollTable.style.height = "500px"; // Đặt chiều cao mặc định
</script>
<!-- END: main -->
