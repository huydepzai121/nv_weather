<?php
/**
 * NukeViet Content Management System
 * @version 4.x
 * @copyright VINADES.,JSC
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet/nukeviet
 */

if (!defined('NV_IS_MOD_WEATHER')) {
    exit('Stop!!!');
}

$selectedCityName = '';
$array_data = array();
$total_pages = 0;
$current_page = 1;
$is_submit = isset($_POST['submit']);
$records_per_page = 4;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = max($current_page, 1);
$start = ($current_page - 1) * $records_per_page;
if ($is_submit) {
    $current_page = 1;
    $cityName = $nv_Request->get_title('id_city', 'post', '');
    $selectedCityName = $cityName;

    // Thực hiện phân trang

    // Đếm tổng số bản ghi có điều kiện tên thành phố
    $stmt = $db->prepare('SELECT COUNT(*) FROM ' . NV_PREFIXLANG . '_' . $module_data . ' 
        INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_city 
        ON ' . NV_PREFIXLANG . '_' . $module_data . '.id_city=' . NV_PREFIXLANG . '_' . $module_data . '_city.id 
        WHERE ' . NV_PREFIXLANG . '_' . $module_data . '_city.name LIKE :city_name');
    $stmt->bindValue(':city_name', '%' . $cityName . '%', PDO::PARAM_STR);
    $stmt->execute();
    $total_records = $stmt->fetchColumn();
    $total_pages = ceil($total_records / $records_per_page);

    // Truy vấn cơ sở dữ liệu với phân trang
    $stmt = $db->prepare('SELECT ' . NV_PREFIXLANG . '_' . $module_data . '.id, ' . NV_PREFIXLANG . '_' . $module_data . '_city.name, date_forecast, description, wind_speed, high_temperature, low_temperature, avatar,rain FROM ' . NV_PREFIXLANG . '_' . $module_data . '
        INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_city 
        ON ' . NV_PREFIXLANG . '_' . $module_data . '.id_city=' . NV_PREFIXLANG . '_' . $module_data . '_city.id 
        WHERE ' . NV_PREFIXLANG . '_' . $module_data . '_city.name LIKE :city_name');
    $stmt->bindValue(':city_name', '%' . $cityName . '%', PDO::PARAM_STR);
    $stmt->execute();

    $array_data = $stmt->fetchAll();
}
else{
    $stmt = $db->prepare('SELECT ' . NV_PREFIXLANG . '_' . $module_data . '.id, ' . NV_PREFIXLANG . '_' . $module_data . '_city.name, date_forecast, description, wind_speed, high_temperature, low_temperature, avatar,rain FROM ' . NV_PREFIXLANG . '_' . $module_data . '
        INNER JOIN ' . NV_PREFIXLANG . '_' . $module_data . '_city 
        ON ' . NV_PREFIXLANG . '_' . $module_data . '.id_city=' . NV_PREFIXLANG . '_' . $module_data . '_city.id');
    $stmt->execute();

    $array_data = $stmt->fetchAll();

}

// Lấy danh sách Thành phố từ cơ sở dữ liệu
$citys = $db->query('SELECT id, name FROM ' . NV_PREFIXLANG . '_' . $module_data . '_city')->fetchAll();

// Gọi hàm theme và hiển thị dữ liệu
$contents = nv_theme_weather_main($array_data, $citys, $selectedCityName, $total_pages, $current_page, $is_submit);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';