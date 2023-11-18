<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_MOD_WEATHER')) {
    exit('Stop!!!');
}

/**
 * nv_page_main()
 *
 * @param array  $row
 * @param array  $ab_links
 * @param string $content_comment
 * @return string
 */

function nv_theme_weather_main($array_data, $citys, $selectedCityName, $total_pages, $current_page, $is_submit) {
    global $module_file, $module_info, $global_config, $lang_module,$module_name;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $module_info['template'] . '/modules/' . $module_file);

    $xtpl->assign('IS_SUBMIT', $is_submit);
    if ($is_submit) {
        foreach ($array_data as $row) {
            $formatted_date = date('d/m/Y', strtotime($row['date_forecast']));
            $xtpl->assign('WEATHER', array(
                'name' => $row['name'],
                'date_forecast' => $formatted_date,
                'description' => $row['description'],
                'wind_speed' => $row['wind_speed'],
                'high_temperature' => $row['high_temperature'],
                'low_temperature' => $row['low_temperature'],
                'rain' => $row['rain'],
                'avatar'=>$row['avatar']
            ));
            $xtpl->parse('main.loop');
        }
    }
    else{
        foreach ($array_data as $row) {
            $formatted_date = date('d/m/Y', strtotime($row['date_forecast']));
            $xtpl->assign('WEATHER', array(
                'name' => $row['name'],
                'date_forecast' => $formatted_date,
                'description' => $row['description'],
                'wind_speed' => $row['wind_speed'],
                'high_temperature' => $row['high_temperature'],
                'low_temperature' => $row['low_temperature'],
                'rain' => $row['rain'],
                'avatar'=>$row['avatar']
            ));
            $xtpl->parse('main.table_loop');
        }
    }

    foreach ($citys as $city) {
        $xtpl->assign('CITY', array(
            'id' => $city['id'],
            'name' => $city['name'],
            'selected' => ($selectedCityName == $city['name']) ? 'selected="selected"' : ''
        ));
        $xtpl->parse('main.city_loop');
    }
    $base_page_link = $baseLink . '&amp;' . NV_OP_VARIABLE . '=main';
    for ($i = 1; $i <= $total_pages; $i++) {
        $xtpl->assign('PAGE', [
            'num' => $i,
            'link' => $base_page_link . '&page=' . $i,
            'current' => ($i == $current_page) ? 'current' : ''
        ]);
        $xtpl->parse('main.page_loop');
    }

    $xtpl->parse('main');
    return $xtpl->text('main');
}