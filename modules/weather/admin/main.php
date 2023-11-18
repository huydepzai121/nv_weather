<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}

$xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$page_title = $lang_module['main'];
$message = "";
$type = "success";
$weather = [];
// Thêm cache
$city_cache_file = NV_LANG_DATA . '_' . $module_name . '_city_data_' . NV_CACHE_PREFIX . '.cache';

$weather_cache_file = NV_LANG_DATA . '_' . $module_name . '_weather_data_' . NV_CACHE_PREFIX . '.cache';
$cache_ttl = 600; // Thời gian cache là 600 giây = 10 phút

// Cache cho bảng city
if (($cache = $nv_Cache->getItem($module_name, $city_cache_file)) != false) {
    $citys = unserialize($cache);
} else {
    $citys = $db->query("SELECT id, name FROM " . NV_PREFIXLANG . "_" . $module_data. "_city")->fetchAll();
    $cache = serialize($citys);
    $nv_Cache->setItem($module_name, $city_cache_file, $cache, $cache_ttl);
}
function createAlias($str) {
    $str = strtolower(trim($str));
    $str = str_replace(
        ['à', 'á', 'ạ', 'ả', 'ã', 'â', 'ầ', 'ấ', 'ậ', 'ẩ', 'ẫ', 'ă', 'ằ', 'ắ', 'ặ', 'ẳ', 'ẵ', 'è', 'é', 'ẹ', 'ẻ', 'ẽ', 'ê', 'ề', 'ế', 'ệ', 'ể', 'ễ', 'ì', 'í', 'ị', 'ỉ', 'ĩ', 'ò', 'ó', 'ọ', 'ỏ', 'õ', 'ô', 'ồ', 'ố', 'ộ', 'ổ', 'ỗ', 'ơ', 'ờ', 'ớ', 'ợ', 'ở', 'ỡ', 'ù', 'ú', 'ụ', 'ủ', 'ũ', 'ư', 'ừ', 'ứ', 'ự', 'ử', 'ữ', 'ỳ', 'ý', 'ỵ', 'ỷ', 'ỹ', 'đ', 'À', 'Á', 'Ạ', 'Ả', 'Ã', 'Â', 'Ầ', 'Ấ', 'Ậ', 'Ẩ', 'Ẫ', 'Ă', 'Ằ', 'Ắ', 'Ặ', 'Ẳ', 'Ẵ', 'È', 'É', 'Ẹ', 'Ẻ', 'Ẽ', 'Ê', 'Ề', 'Ế', 'Ệ', 'Ể', 'Ễ', 'Ì', 'Í', 'Ị', 'Ỉ', 'Ĩ', 'Ò', 'Ó', 'Ọ', 'Ỏ', 'Õ', 'Ô', 'Ồ', 'Ố', 'Ộ', 'Ổ', 'Ỗ', 'Ơ', 'Ờ', 'Ớ', 'Ợ', 'Ở', 'Ỡ', 'Ù', 'Ú', 'Ụ', 'Ủ', 'Ũ', 'Ư', 'Ừ', 'Ứ', 'Ự', 'Ử', 'Ữ', 'Ỳ', 'Ý', 'Ỵ', 'Ỷ', 'Ỹ', 'Đ', 'đ'],
        ['a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'a', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'i', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'o', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'u', 'y', 'y', 'y', 'y', 'y', 'd', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'A', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'E', 'I', 'I', 'I', 'I', 'I', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'O', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'U', 'Y', 'Y', 'Y', 'Y', 'Y', 'D', 'd'],
        $str
    );
    $str = preg_replace('/[^a-z0-9-]/', '-', $str);
    $str = preg_replace('/-+/', "-", $str);
    return $str;
}


$id = $nv_Request->get_int('id', 'get', 0);
$isEditing = $id > 0;

if ($nv_Request->get_int('submit', 'post', 0)) {
    $data = [
        'id_city' => $nv_Request->get_int('id_city', 'post', 0),
        'date_forecast' => $nv_Request->get_title('date_forecast', 'post', ''),
        'description' => $nv_Request->get_title('description', 'post', ''),
        'wind_speed' => $nv_Request->get_int('wind_speed', 'post', 0),
        'high_temperature' => $nv_Request->get_int('high_temperature', 'post', '0'),
        'low_temperature' => $nv_Request->get_int('low_temperature', 'post', 0),
        'rain' => $nv_Request->get_int('rain', 'post', 0)
    ];
    // Tạo alias từ tên thành phố
    $cityNameQuery = $db->query("SELECT name FROM " . NV_PREFIXLANG . "_" . $module_data . "_city WHERE id=" . $data['id_city']);
    $cityName = $cityNameQuery->fetchColumn();
    $data['alias'] = createAlias($cityName);
    if (isset($_FILES['avatar']) && !empty($_FILES['avatar']['name'])) {
        $upload = new NukeViet\Files\Upload($admin_info['allow_files_type'], $global_config['forbid_extensions'], $global_config['forbid_mimes'], NV_UPLOAD_MAX_FILESIZE, NV_MAX_WIDTH, NV_MAX_HEIGHT);
        $upload->setLanguage($lang_global);
        $path = NV_UPLOADS_REAL_DIR . '/' . $module_upload;

        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }

        // Xử lý file tải lên và lưu vào thư mục.
        $upload_info = $upload->save_file($_FILES['avatar'], $path, false, $global_config['nv_auto_resize']);

        if (!empty($upload_info['complete'])) {
            // Lưu đường dẫn tương đối của ảnh vào mảng $data.
            $data['avatar'] = NV_BASE_SITEURL . 'uploads/' . $module_upload . '/' . $upload_info['basename'];
        } else {
            // Xử lý lỗi tải lên, set 'avatar' thành chuỗi rỗng hoặc ghi log lỗi tùy bạn.
            $data['avatar'] = '';
        }
    } else {
        // Nếu không có file được tải lên, giữ nguyên giá trị avatar hiện tại hoặc set thành chuỗi rỗng.
        $data['avatar'] = isset($weather['avatar']) ? $weather['avatar'] : '';
    }
    if ($isEditing) {
        $sql = "UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET 
                `id_city` = :id_city,          
                `date_forecast` = :date_forecast,
                `description` = :description,
                `wind_speed` = :wind_speed,
                `high_temperature` = :high_temperature,
                `low_temperature` = :low_temperature,
                `avatar`=:avatar,
                `rain`=:rain,
                `alias`=:alias
            WHERE 
                `id` = :id";
        $sth = $db->prepare($sql);
        $sth->bindParam(':id', $id, PDO::PARAM_INT);
    } else {
        $weight_query = $db->query("SELECT MAX(weight) as max_weight FROM " . NV_PREFIXLANG . "_" . $module_data);
        $max_weight = $weight_query->fetch();
        $new_weight = $max_weight['max_weight'] + 1;
        $sql = "INSERT INTO " . NV_PREFIXLANG . "_" . $module_data . " (
            `id_city`, `date_forecast`,  `description`, 
            `wind_speed`, `high_temperature`, `low_temperature`, `weight`,`avatar`,`rain`,`alias`
        ) VALUES (
            :id_city, :date_forecast, :description, 
            :wind_speed, :high_temperature, :low_temperature, :new_weight, :avatar,:rain,:alias
        )";
        $sth = $db->prepare($sql);
        // Bổ sung tham số mới cho weight
        $sth->bindParam(':new_weight', $new_weight, PDO::PARAM_INT);
    }

    foreach ($data as $key => $value) {
        $sth->bindParam(':' . $key, $data[$key]);
    }

    if ($sth->execute()) {
        $message = ($isEditing) ? "Cập nhật thành công!" : "Thêm mới thành công!";

        $weather_data = $db->query("SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data)->fetchAll();
        $cache = serialize([$weather_data]);
        $nv_Cache->setItem($module_name, $weather_cache_file, $cache, $cache_ttl);
    } else {
        $message = "Có lỗi xảy ra: " . implode(' ', $sth->errorInfo());
        $type = "error";
    }
}
$xtpl->assign('LANG', $lang_module);
$xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
$xtpl->assign('NV_LANG_DATA', NV_LANG_DATA);
$xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
$xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
$xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
$xtpl->assign('MODULE_NAME', $module_name);
$xtpl->assign('OP', $op);
$xtpl->assign('ALERT_MESSAGE', $message);
$xtpl->assign('ALERT_TYPE', $type);
if ($isEditing) {
    $sql = "SELECT * FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id=:id";
    $sth = $db->prepare($sql);
    $sth->bindParam(':id', $id, PDO::PARAM_INT);
    $sth->execute();
    $weather = $sth->fetch();
    $xtpl->assign('WEATHER', $weather);
} else {
    $weather = [
        'id_city' => 0,
        'date_forecast' => '',
        'description' => '',
        'wind_speed' => '',
        'high_temperature' => '',
        'low_temperature' => '',
        'avatar'=>'',
        'rain'=>''
    ];
    $xtpl->assign('WEATHER', $weather);
}

foreach ($citys as $city) {
    $xtpl->assign('CITY_ID', $city['id']);
    $xtpl->assign('CITY_NAME', $city['name']);
    if ($weather['id_city'] == $city['id']) {
        $xtpl->assign('SELECTED_CITY', 'selected="selected"');
    } else {
        $xtpl->assign('SELECTED_CITY', '');
    }
    $xtpl->parse('main.city_loop');
}

$xtpl->assign('ALERT_MESSAGE', $message);
$xtpl->assign('ALERT_TYPE', $type);

$xtpl->parse('main');
$contents = $xtpl->text('main');

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';