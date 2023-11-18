
<!-- BEGIN: main -->
<form action="{NV_BASE_ADMINURL}index.php?{NV_NAME_VARIABLE}={MODULE_NAME}&{NV_OP_VARIABLE}=submit" method="post" class="form-group" enctype="multipart/form-data">
    <input type="hidden" name="id" value="{WEATHER.id}">
    <div class="row">
        <div class="col-24 col-md-24">
            <label>Tên thành phố:</label>
            <select name="id_city" id="id_city" class="form-control">
                <!-- BEGIN: city_loop -->
                <option value="{CITY_ID}" {SELECTED_CITY}>{CITY_NAME}</option>
                <!-- END: city_loop -->
            </select>
        </div>
        <div class="col-6 col-md-6">
            <div class="form-group">
                <label>Ngày dự báo:</label>
                <input type="date" class="form-control datepicker" name="date_forecast" autocomplete="off" value="{WEATHER.date_forecast}" />
            </div>
        </div>
        <div class="col-10 col-md-10">
            <div class="form-group">
                <label for="wind_speed">Tốc độ gió:</label>
                <span><input type="number" name="wind_speed" class="form-control" value="{WEATHER.wind_speed}" required>km/h</span>
            </div>
        </div>
        <div class="col-24 col-md-24">
            <div class="form-group">
                <label for="low_temperature">Nhiệt độ thấp</label>
                <span><input type="number" name="low_temperature" class="form-control" value="{WEATHER.low_temperature}" required> °C</span>
            </div>
        </div>
        <div class="col-10 col-md-10">
            <div class="form-group">
                <label for="temperature_value">Nhiệt độ cao:</label>
                <span><input type="number" name="high_temperature" class="form-control" value="{WEATHER.high_temperature}" required> °C</span>
            </div>
        </div>
        <div class="col-10 col-md-10">
            <div class="form-group">
                <label for="rain">Lượng mưa</label>
                <span><input type="number" name="rain" class="form-control" value="{WEATHER.rain}" required> /mm</span>
            </div>
        </div>
        <div class="col-24 col-md-24">
            <label>Ảnh đại diện từ máy tính:</label>
            <input type="file" class="form-control-file" name="avatar" accept="image/*" />
            <!-- BEGIN: has_avatar -->
            <div class="col-24 col-md-24">
                <img src="{AVATAR}" alt="Ảnh đại diện" id="avatar-preview" style="max-width: 100px; max-height: 100px;">
            </div>
            <!-- END: has_avatar -->
        </div>
        <div class="col-24 col-md-24">
            <div class="form-group">
                <label for="description">Mô tả:</label>

                <textarea name="description" id="description">{WEATHER.description}</textarea>
            </div>
        </div>
    </div>

    <button type="submit" name="submit" id="submit" value="1" class="btn btn-primary mt-5">Lưu</button>
</form>
<script type="text/javascript" src="{NV_BASE_SITEURL}assets/editors/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    CKEDITOR.replace('description');
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('.datepicker').datepicker({
            format: 'yyyy-mm-dd',
            todayHighlight: true,
            autoclose: true
        });
    });
</script>
<script>   document.addEventListener('DOMContentLoaded', function() {
        let type = "{ALERT_TYPE}";
        let message = "{ALERT_MESSAGE}";
        let redirectUrl = "{REDIRECT_URL}";

        if (message && type) {
            Swal.fire({
                icon: type,
                title: message,
                showConfirmButton: true
            }).then((result) => {
                if (result.isConfirmed && type === "success" && redirectUrl) {
                    window.location.href = redirectUrl;
                }
            });
        }
    });

</script>
<!-- END: main -->