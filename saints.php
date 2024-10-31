<?php
/*
    Plugin Name: Saints
    Plugin URI: https://wordpress.org/plugins/saints
    Description: Плагин выводит жизнеописания Святых на любую дату.
    Author: SIA
    Version: 1.10.4
    Author URI: https://github.com/Siamajor
    License:     GPL2
    Text Domain: saints
    Domain Path: /languages
*/

/*  Copyright 2021  Igor Soloshenko  (email: siamajor@ukr.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if (!defined('ABSPATH')) {
    die('Sorry, you are not allowed to access this page directly.');
}

add_action('wp_enqueue_scripts', 'sia_saints_style');
function sia_saints_style()
{
    wp_register_style('sia_saints', plugins_url('css/sia_saints.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'sia_calendar_style');
function sia_calendar_style()
{
    wp_register_style('sia_scalendar', plugins_url('css/datepicker.min.css', __FILE__));
}

add_action('admin_enqueue_scripts', 'sia_admin_style');
function sia_admin_style()
{
    wp_enqueue_style('sia_admin_style', plugins_url('admin/css/sia_admin.css', __FILE__));
}

add_action('wp_enqueue_scripts', 'sia_scalendar');
function sia_scalendar()
{
    wp_register_script('sia_scalendar', plugins_url('js/datepicker.min.js', __FILE__));
}

add_action('wp_enqueue_scripts', 'sia_saints');
function sia_saints()
{
    wp_register_script('sia_saints', plugins_url('js/sia_saints.js', __FILE__));
}

add_action('admin_enqueue_scripts', 'sia_admin');
function sia_admin()
{
    wp_enqueue_script('sia_admin', plugins_url('admin/js/sia_admin.js', __FILE__));
}
//** админка */
add_action('admin_menu', 'add_saints_page');
function add_saints_page()
{
    add_options_page('Настройки плагина Saints', 'Saints', 'manage_options', 'saints_option', 'saints_options_page_output');
}

include_once('admin/sia_admin.php');
add_action('admin_init', 'saints_plugin_settings');

function saints_options_page_output()
{
?>
    <div class="wrap">
        <h2><?php echo get_admin_page_title() ?></h2>
        <form id="saints-form" action="options.php" method="POST">
            <?php
            settings_fields('saints_group');     // скрытые защитные поля
            do_settings_sections('saints_page'); // секции с настройками (опциями).
            submit_button();
            ?>
        </form>
    </div>
<?php
}

global $parDate;
$url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

$parts = parse_url($url);
if (isset($parts['query'])) parse_str($parts['query'], $query);
if (isset($query['date'])) {
    $parDate = $query['date'];
}
if (!$parDate) $parDate = date("Y-m-d");
$parDate = sanitize_option('date_format', $parDate,);

//** [sia-saints] */
add_shortcode('sia-saints', 'sia_saint_all');
function sia_saint_all()
{
    wp_enqueue_style('sia_scalendar');
    wp_enqueue_style('sia_saints');
    wp_enqueue_script('sia_saints');
    wp_enqueue_script('sia_scalendar');
    global $parDate;

    if (isset($output) && $output != '') {
        $html = get_transient($output);
        echo $html;
        return;
    }
    ob_start();
    ///---
    $urlScript = 'https://azbyka.ru/days/api/saints/' . $parDate . '/group.json';
    $response = wp_remote_get($urlScript);
    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code == 200) {

        $json = wp_remote_retrieve_body($response);
        $dataar = json_decode($json, true);

        foreach ($dataar as $titles) {
            if (isset($titles["title"])) $title[] = $titles["title"] ?? '';
        }
        unset($titles);
        foreach ($dataar as $images) {
            if (isset($images["imgs"]["0"]["image"])) $image[] = $images["imgs"]["0"]["image"] ?? '';
        }
        unset($images);
        foreach ($dataar as $descr) {
            if (isset($descr["description"])) $description[] = wp_rel_nofollow($descr["description"]) ?? '';
        }
        unset($descr);
        foreach ($dataar as $saint_ids) {
            if (isset($saint_ids["imgs"]["0"]["saint_id"])) $saint_id[] = $saint_ids["imgs"]["0"]["saint_id"] ?? '';
        }
        unset($saint_ids);
        foreach ($dataar as $title_tropars) {
            if (isset($title_tropars["taks"]["0"]["title"])) $title_tropar[] = $title_tropars["taks"]["0"]["title"] ?? '';
        }
        unset($title_tropars);
        foreach ($dataar as $descr_tropars) {
            if (isset($descr_tropars["taks"]["0"]["text"])) $descr_tropar[] = wp_rel_nofollow($descr_tropars["taks"]["0"]["text"]) ?? '';
        }
        unset($descr_tropars);
        foreach ($dataar as $title_kondaks) {
            if (isset($title_kondaks["taks"]["1"]["title"])) $title_kondak[] = $title_kondaks["taks"]["1"]["title"] ?? '';
        }
        unset($title_kondaks);
        foreach ($dataar as $descr_kondaks) {
            if (isset($descr_kondaks["taks"]["1"]["text"])) $descr_kondak[] = wp_rel_nofollow($descr_kondaks["taks"]["1"]["text"]) ?? '';
        }
        unset($descr_kondaks);
        foreach ($dataar as $title_molitvas) {
            if (isset($title_molitvas["taks"]["2"]["0"]["title"])) $title_molitva[] = $title_molitvas["taks"]["2"]["0"]["title"] ?? '';
        }
        unset($title_molitvas);
        foreach ($dataar as $descr_molitvas) {
            if (isset($descr_molitvas["taks"]["2"]["0"]["text"])) $descr_molitva[] = wp_rel_nofollow($descr_molitvas["taks"]["2"]["0"]["text"]) ?? '';
        }
        unset($descr_molitva);
    }
    $all_options = get_option('saints_option'); // массив
    if (isset($all_options['titleOpt'])) {
        $titleOpt = $all_options['titleOpt'];
    } else {
        $titleOpt = '';
    }
    if (isset($all_options['showicons'])) {
        $showicons = $all_options['showicons'];
    }
    if (isset($all_options['link'])) {
        $link = $all_options['link'];
    }
    if (isset($all_options['titleData'])) {
        $titleData = $all_options['titleData'];
    } else {
        $titleData = '';
    }
    if (isset($all_options['shortinfo'])) {
        $shortinfo = $all_options['shortinfo'];
    } else {
        $shortinfo = '';
    }
    $cnt = count($title, COUNT_RECURSIVE);
    $dateTitf =  wp_date('j F Y', strtotime($parDate));
    if ($titleData) {
        $dateShow = '<span class="dateShow">&nbsp;&nbsp; ' . $dateTitf . ' </span>';
    } else {
        $dateShow = '';
    }
    echo '<div id="saints">';
    if (isset($titleOpt) && $titleOpt != '') { // если изменен заголовок
        echo '<div class=titleOpt"><span class="titleOpt">' . sanitize_text_field($titleOpt) . '</span>';
    }
    echo $dateShow . '</div>'; // дата
    if (isset($shortinfo) && $shortinfo != '') {
        $presentations = sia_shortinfof();
        echo '<div class="presentations-s">' . $presentations . '</div>';
    }
    for ($s = 0; $s < $cnt; $s++) {
        echo '<div class="block-saints">';
        if (isset($title[$s]) && $title[$s] != '') {
            echo '<div class="title_description"><h3 id="' . $title[$s] . '">' . $title[$s] . '</h3></div>';
        }
        if (isset($showicons) && $showicons != '') { // если опция 'показывать миниатюры' включена
            if (isset($image[$s])) {
                if ($link == 2) { // если с сервера 
                    $imageurl = '<img src="' . wp_get_upload_dir()['baseurl'] . '/saints-cache/img/' . $image[$s] . '" alt="' . $title[$s] . '"/>';
                } else { // если с azbyka
                    $imageurl = '<img src="https://azbyka.ru/days/assets/img/saints/' . $saint_id[$s] . '/' . $image[$s] . '" alt="' . $title[$s] . '" rel="nofollow"/>';
                }
                echo '<div class="imageicon">' . $imageurl  . '</div>';
            }
        }
        if (isset($description[$s]) && $description[$s] != '') {
            echo '<div class="description">' . wp_unslash($description[$s]) . '</div>';
        }
        if (isset($title_tropar[$s]) && $title_tropar[$s] != '') {
            echo '<div class="title_tropar">' . $title_tropar[$s] . '</div>';
        }
        if (isset($descr_tropar[$s]) && $descr_tropar[$s] != '') {
            echo '<div class="descr_tropar">' . wp_unslash($descr_tropar[$s]) . '</div>';
        }
        if (isset($title_kondak[$s]) && $title_kondak[$s] != '') {
            echo '<div class="title_kondak">' . $title_kondak[$s] . '</div>';
        }
        if (isset($descr_kondak[$s]) && $descr_kondak[$s] != '') {
            echo '<div class="descr_kondak">' . wp_unslash($descr_kondak[$s]) . '</div>';
        }
        if (isset($title_molitva[$s]) && $title_molitva[$s] != '') {
            echo '<div class="title_molitva">' . $title_molitva[$s] . '</div>';
        }
        if (isset($descr_molitva[$s]) && $descr_molitva[$s] != '') {
            echo '<div class="descr_molitva">' . wp_unslash($descr_molitva[$s]) . '</div>';
        }
        echo '</div>';
    }
    echo '</div>';

    $expiration = YEAR_IN_SECONDS;
    $output = ob_get_contents();
    set_transient($parDate, $output, $expiration);
    ob_flush();
}

//*** [sia-shortinfo] */
add_shortcode('sia-shortinfo', 'sia_shortinfof');
function sia_shortinfof()
{
    global $parDate;
    global $presentations;

    if (isset($output) && $output != '') {
        $html = get_transient($output);
        echo $html;
        return;
    }
    ob_start();

    $urlShort = 'https://azbyka.ru/days/api/presentations/' . $parDate . '.json';
    $response = wp_remote_get($urlShort);
    $response_code = wp_remote_retrieve_response_code($response);
    if ($response_code == 200) {
        $json = wp_remote_retrieve_body($response);
        $dataars = json_decode($json, true);
        $presentation = $dataars["presentations"];
        $allowed_html = array(
            'br'     => array(),
            'em'     => array(),
            'strong' => array(),
            'div'    => array(),
            'p'      => array()
        );
        $presentations = wp_kses(wp_unslash($presentation), $allowed_html);
        echo '<div class="presentations">' . $presentations . '</div>';
    }
    $transshort = 's_' . $parDate;
    $expiration = YEAR_IN_SECONDS;
    $output = ob_get_contents();
    set_transient($transshort, $output, $expiration);
    ob_flush();
}

//*** [sia-calendar] */
add_shortcode('sia-calendar', 'sia_skalendar');
function sia_skalendar()
{
    echo '<div id="scalendar"></div>';
}

//*** активация */
function sia_saints_activate()
{
    
    $upload = wp_upload_dir();
    $upload_dir = $upload['basedir'];
    $upload_dir_cache = $upload_dir . '/saints-cache';

    if (!wp_mkdir_p($upload_dir_cache)) {
        __("Не удалось создать каталог saints-cache", "saints");
    }

    $val = get_option('saints_option');
    if (!isset($val['titleOpt'])) { $titleOpt = 'Жития Святых'; }
    update_option('showicons', 1);
    update_option('link', 1);

    load_plugin_textdomain('saints', FALSE, basename(dirname(__FILE__)) . '/languages/');
}
register_activation_hook(__FILE__, 'sia_saints_activate');
//activate_plugins('saints/saints.php');
