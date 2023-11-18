
<!-- BEGIN: main -->

<div class="d-flex justify-content-end mb-3 text-center">
    <form class="form-inline" method="post" action="">
        <input class="form-control mr-sm-2" type="search" placeholder="Tìm kiếm" aria-label="Search" name="timkiem">
        <button class="btn btn-outline-success my-2 my-sm-0" type="submit" name="submit">Tìm kiếm</button>
    </form>
</div>
<div>
    <table class="table table-striped table-bordered mt-5">
        <thead class="table-dark">
        <tr>
            <th class="text-center">STT</th>
            <th class="text-center">Tên thành phố:</th>
            <th class="text-center">Ngày dự báo:</th>
            <th class="text-center">Nhiệt độ cao:</th>
            <th class="text-center">Nhiệt độ thấp:</th>
            <th class="text-center">Tốc độ gió:</th>
            <th class="text-center">Mưa:</th>
            <th class="text-center">Ghi chú:</th>
            <th class="text-center">Ảnh:</th>
            <th class="text-center">Chức năng</th>
        </thead>
        <tbody>
        <!-- BEGIN: loop -->
        <tr>
            <td class="text-center">
                <select name="weight" id="weight" class="form-control weight_{WEATHER.id}" onchange="nv_change_weight({WEATHER.id})">
                    <!-- BEGIN: weight -->
                    <option value="{J}"{J_SELECT} >{J}</option>
                    <!-- END: weight -->
                </select>
            </td>
            <td class="text-center">{WEATHER.name}</td>
            <td class="text-center">{WEATHER.date_forecast}</td>
            <td class="text-center">{WEATHER.high_temperature}</td>
            <td class="text-center">{WEATHER.low_temperature}</td>
            <td class="text-center">{WEATHER.wind_speed}</td>
            <td class="text-center">{WEATHER.rain}</td>
            <td class="text-center">{WEATHER.description}</td>
            <td class="text-center"><img src="{WEATHER.avatar}" width="42.35px" height="42.35px" alt="Avatar" class="img-thumbnail"></td>
            <td class="text-center">
                <a href="{WEATHER.url_edit}" title="Sửa" class="btn btn-primary btn-sm">Sửa</a>
                <a href="{WEATHER.url_delete}" title="Xóa" class="btn btn-danger btn-sm"  onclick="return confirm('Ban có chắc là muốn xóa dữ liệu');" >Xóa</a>


            </td>
        </tr>
        <!-- END: loop -->
        </tbody>
    </table>
</div>
<!-- Phần hiển thị phân trang -->
<nav aria-label="Page navigation">
    <ul class="pagination">
        <!-- Vòng lặp hiển thị các trang -->
        <!-- BEGIN: page_loop -->
        <li class="page-item {PAGE.current}"><a class="page-link" href="{PAGE.link}">{PAGE.num}</a></li>
        <!-- END: page_loop -->
    </ul>
</nav>
<script>
    $(document).ready(function (){
        $('.delete').click(function (){
            if(confirm("Bạn có muốn xóa")){
                return true;
            }
            else {
                return false;
            }
        });
    });
</script>
<script>
    function confirmDelete(delUrl) {
        if (confirm("Bạn có chắc chắn xóa không ?")) {
            document.location = delUrl;
        }
    }
</script>
<script>
    function nv_change_weight(id) {
        var new_weight=$('.weight_'+ id).val();
        $.ajax({
            url: script_name + '?' + nv_name_variable + '=' + nv_module_name
                + '&' + nv_fc_variable
                + '=list&change_weight=1&id=' + id+'&new_weight='+new_weight,
            success: function(result) {
                if(result!='ERR'){

                    location.reload();
                }
            }
        });
    }
</script>


<!-- END: main -->
