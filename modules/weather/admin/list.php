<?php
/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC (contact@vinades.vn)
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-9-2010 14:43
 */

if (!defined('NV_IS_FILE_ADMIN')) {
    exit('Stop!!!');
}
$message = "";
$type = "success";
$xtpl = new XTemplate('list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
$records_per_page = 6;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$current_page = ($current_page > 0) ? $current_page : 1;
$start = ($current_page - 1) * $records_per_page;
$sql = "SELECT COUNT(*) FROM " . NV_PREFIXLANG . "_" . $module_data;
$total_records = $db->query($sql)->fetchColumn();
$total_pages = ceil($total_records / $records_per_page);
if (isset($_POST['submit'])) {
    $timkiem = $_POST['timkiem'];
    $sql = "SELECT " . NV_PREFIXLANG . "_" . $module_data . ".id," . NV_PREFIXLANG . "_" . $module_data . "_city.name,date_forecast,description,wind_speed,high_temperature,low_temperature,weight,avatar,rain
from " . NV_PREFIXLANG . "_" . $module_data . " 
INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_city ON " . NV_PREFIXLANG . "_" . $module_data . ".id_city=" . NV_PREFIXLANG . "_" . $module_data . "_city.id
INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_time_period ON " . NV_PREFIXLANG . "_" . $module_data . ".id_time_period=" . NV_PREFIXLANG . "_" . $module_data . "_time_period.id 
WHERE " . NV_PREFIXLANG . "_" . $module_data . "_city.name LIKE :query1
ORDER BY " . NV_PREFIXLANG . "_" . $module_data . ".weight ASC
LIMIT " . $start . ", " . $records_per_page;
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':query1', '%' . $timkiem . '%', PDO::PARAM_STR);
} else {
    $sql = "SELECT " . NV_PREFIXLANG . "_" . $module_data . ".id," . NV_PREFIXLANG . "_" . $module_data . "_city.name,date_forecast,description,wind_speed,high_temperature,low_temperature,weight,avatar,rain
from " . NV_PREFIXLANG . "_" . $module_data . " 
INNER JOIN " . NV_PREFIXLANG . "_" . $module_data . "_city ON " . NV_PREFIXLANG . "_" . $module_data . ".id_city=" . NV_PREFIXLANG . "_" . $module_data . "_city.id
ORDER BY " . NV_PREFIXLANG . "_" . $module_data . ".weight ASC
 LIMIT " . $start . ", " . $records_per_page;
    $stmt = $db->prepare($sql);
}
$stmt->execute();
$weathers = $stmt->fetchAll();
$baseLink = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name;

// Thêm phần xóa trực tiếp vào danh sách
if (isset($_GET['delete'])) {
    $idToDelete = intval($_GET['delete']);
    // Xử lý chức năng xóa dữ liệu
    $stmt = $db->prepare("DELETE FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE " . NV_PREFIXLANG . "_" . $module_data . ".id=:id");
    $stmt->bindParam(':id', $idToDelete, PDO::PARAM_INT);
    if ($stmt->execute()) {
        // Hiển thị thông báo sau khi xóa thành công
        $message = "Xóa thành công!";
        $type = "success";

        // Xóa cache liên quan đến dữ liệu thời tiết
        $nv_Cache->delMod($module_name);
    } else {
        // Hiển thị thông báo lỗi nếu xóa không thành công
        $message = "Có lỗi xảy ra!";
        $type = "error";
    }
}


foreach ($weathers as $weather) {
    for ($j = 1; $j <= $total_records; $j++) {
        $xtpl->assign('J', $j);
        $xtpl->assign('J_SELECT', $j==$weather['weight'] ? 'selected="selected"':'');
        $xtpl->parse('main.loop.weight');
    }
    $weather['url_edit'] = $baseLink . '&amp;' . NV_OP_VARIABLE . '=main&amp;id=' . $weather['id'];
    $weather['url_delete'] = $baseLink . '&amp;' . NV_OP_VARIABLE . '=list&amp;delete=' . $weather['id']; // Thêm tham số delete

    $xtpl->assign('WEATHER', $weather);
    $xtpl->parse('main.loop');
}
if ($nv_Request->isset_request('change_weight', 'post,get')) {
    $id = $nv_Request->get_int('id', "pos,get", 0);
    $new_weight = $nv_Request->get_int('new_weight', "pos,get", 0);
    if ($id > 0 and $new_weight > 0) {
        $sql = "SELECT id, weight FROM " . NV_PREFIXLANG . "_" . $module_data . " WHERE id != " . $id;
        $result = $db->query($sql);
        $weight = 0;
        while ($weather = $result->fetch()) {
            ++$weight;
            if ($weight == $new_weight) {
                ++$weight;
            }
            $exe = $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET weight = " . $weight . " WHERE id = " . $weather['id']);
        }
        $exe = $db->query("UPDATE " . NV_PREFIXLANG . "_" . $module_data . " SET weight = " . $new_weight . " WHERE id = " . $id);
    }
}
// Thêm phân trang vào template
$base_page_link = $baseLink . '&amp;' . NV_OP_VARIABLE . '=list';
for ($i = 1; $i <= $total_pages; $i++) {
    $xtpl->assign('PAGE', [
        'num' => $i,
        'link' => $base_page_link . '&page=' . $i,
        'current' => ($i == $current_page) ? 'current' : ''
    ]);
    $xtpl->parse('main.page_loop');
}

$xtpl->assign('LANG', $lang_module);
$xtpl->assign('ALERT_MESSAGE', $message);
$xtpl->assign('ALERT_TYPE', $type);

$xtpl->parse('main');
$contents = $xtpl->text('main');

$page_title = $lang_module['list_teacher'];

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';