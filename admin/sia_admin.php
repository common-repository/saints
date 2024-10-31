<?php
/*
    
	Admin page
    Author URI: https://github.com/Siamajor
    License:     GPL2
*/
if (!defined('ABSPATH')) {
    die('Sorry, you are not allowed to access this page directly.');
}
require_once(ABSPATH . 'wp-admin/includes/file.php');

/**
 * Регистрируем настройки.
 */
function saints_plugin_settings()
{
    // параметры: $option_group, $saints_option, $saints_sanitize_callback
    register_setting('saints_group', 'saints_option', 'saints_sanitize_callback');

    // секция
    add_settings_section('section_id1', '', '', 'saints_page');

    // поля
    add_settings_field('saints_field_text', '', 'fill_saints_field_text', 'saints_page', 'section_id1');
    add_settings_field('saints_field1', __('Заголовок блока с описаниями', 'saints'), 'fill_saints_field1', 'saints_page', 'section_id1');
    add_settings_field('saints_field_data', __('Дата', 'saints'), 'fill_saints_field_data', 'saints_page', 'section_id1');
    add_settings_field('saints_field_shortinfo', __('Краткая информация', 'saints'), 'fill_saints_field_shortinfo', 'saints_page', 'section_id1');
    add_settings_field('saints_field3', __('Миниатюры икон', 'saints'), 'fill_saints_field3', 'saints_page', 'section_id1');
    add_settings_field('saints_field5', __('Источник миниатюр', 'saints'), 'fill_saints_field5', 'saints_page', 'section_id1');
    add_settings_field('saints_field4', __('Удаление миниатюр', 'saints'), 'fill_saints_field4', 'saints_page', 'section_id1');
}
function fill_saints_field_text()
{
    $val = get_option('saints_option');
    if (isset($val['textTop'])) {
        $textTop = $val['textTop'];
    } else {
        $textTop = '';
    }
    $dir_files = wp_get_upload_dir()['basedir'] . '/saints-cache/img';
    if (is_dir($dir_files)) {
        $fff = count(glob("$dir_files/*"));
    } else {$fff = 0;}
    if (!isset($fff) || $fff == 0) {
        $textTop = __("ВНИМАНИЕ! Для показа миниатюр икон в описании жития Святых архив с миниатюрами<br/> необходимо загрузить в формате zip с именем img (img.zip) в папку<br/><strong>/wp-content/uploads/saints-cache/</strong> на вашем сервере.<br />Это займет около 83мб (более 2700 картинок) на вашем диске.<br />ЛИБО подгружать картинки с внешнего источника - сайта azbуka.ru.<br/>Архив img.zip можно скачать <a href='https://xn--b1aplbci.xn--j1amh/vebmasteru/plugin-saints/' target='_blank'>отсюда</a>", 'saints');
    } else {
        $textTop = __('Загружено на ваш сервер ', 'saints')
            . $fff . __(' миниатюр.<br/>Вы можете использовать ваш сервер как источник миниатюр для описаний', 'saints');
    }
?>
    <input type="hidden" name="saints_option[textTop]" value="<?php echo esc_attr($textTop) ?>" />
    <div class="attention_admin_succ"><?php echo $textTop; ?></div>
<?php
}
## текст поле
function fill_saints_field1()
{   
    $val = get_option('saints_option');
    $titleOpt = $val ? $val['titleOpt'] : null;
    $plshldTitle = 'Заголовок';
    $titleNull = __('Если оставить пустым не будет выводится', 'saints');
?>
    <div class="sia">
        <div class="form-group">
            <input type="text" name="saints_option[titleOpt]" placeholder="<?php echo $plshldTitle; ?>" value="<?php echo esc_attr($titleOpt) ?>" />
            <div class="sinput"><label class="input-label" for="saints_option[titleOpt]"><?php echo$titleNull; ?></label></div>
        </div>
    </div>
<?php
}

function fill_saints_field_data()
{
    $val = get_option('saints_option');
    if (isset($val['titleData'])) {
        $titleData = $val['titleData'];
    } 
else {
$titleData = '';
}





    $pokDate = __('  показывать дату', 'saints');
    $esPok = __('Если отмечено, после заголовка будет показана дата', 'saints');
?>
    <div class="sia">
        <label class="checkbox"><input type="checkbox" name="saints_option[titleData]" value="1" <?php checked(1, $titleData) ?> /><span><em><strong><?php echo $pokDate; ?></strong></em></span></label>
        <div><?php echo $esPok; ?></div>
    </div>
<?php
}

function fill_saints_field_shortinfo()
{
    $val = get_option('saints_option');
    if (isset($val['shortinfo'])) {
        $shortinfo = $val['shortinfo'];
    } else {
        $shortinfo = '';
    }
    $pokinfo = __(' показывать информацию', 'saints');
    $esinfo = __('Если отмечено, после заголовка будет показана краткая<br/>информация на выбранный день. Также можно<br/>краткую информацию получить с помощью шорткода [shortinfo] ', 'saints');
?>
    <div class="sia">
        <label class="checkbox"><input type="checkbox" name="saints_option[shortinfo]" value="1" <?php checked(1, $shortinfo) ?> /><span><em><strong><?php echo $pokinfo; ?></strong></em></span></label>
        <div><?php echo $esinfo; ?></div>
    </div>
<?php
}

## Checkbox
function fill_saints_field3()
{
    $val = get_option('saints_option');
    if (isset($val['link'])) $link = $val['link'];
    if (isset($val['showicons'])) {
        $showicons = $val['showicons'];
    } else {$showicons = '';}


    //** подсчет количества картинок */
    
    $pokmin = __(' показывать миниатюры', 'saints');
    $esmin = __('Если отмечено, в текст будут вставлены<br/>изображения икон Святых, если есть', 'saints');
?>
    <div class="sia">
        <label class="checkbox"><input id="showmin" type="checkbox" name="saints_option[showicons]" value="1" <?php checked(1, $showicons) ?> /><span><em><strong><?php echo $pokmin; ?></strong></em></span></label>
        <div><?php echo $esmin; ?></div>
    </div>
<?php
}

function fill_saints_field5()
{
    //** обнаружение .zip */
    $val = get_option('saints_option');
    if(isset($val['link'])) {$link = $val['link'];} else {$link = 1;} 
    $dir_file = wp_get_upload_dir()['basedir'] . '/saints-cache/';
    $filename = $dir_file . '/img.zip';
    if (is_resource($zip = zip_open($filename))) {
        zip_close($zip);
        $errzip = __('<div class="zip_yes">архив img.zip загружен</div>', 'saints');
    } else {
        $errzip = '';
    }
    $dir_files = wp_get_upload_dir()['basedir'] . '/saints-cache/img';
    if (is_dir($dir_files)) {
        $fff = count(glob("$dir_files/*"));
    }
    if (isset($fff) && $fff > 0) {
        $stats = '';} else { $stats = 'disabled';}
       if ($errzip !='') {$stats = '';}
    $vs = __(' ваш сервер ', 'saints');
    $minch = __("Вы можете загрузить уже готовый архив (img.zip) с миниатюрами в папку на Вашем сервере<br/>/wp-content/uploads/saints-cache/<br/>и использовать ее как источник миниатюр для ускорения загрузки страницы.<br/>Если Вы выберете azbyka.ru, то картинки будут подгружаться с этого сервера.", 'saints');
?>


    <div class="sia" id="change-link">
        <div class="form_radio_btn">
            <input type="radio" id="radio1" name="saints_option[link]" value="1" <?php checked(1, $link) ?> />
            <label for="radio1"><span><em><strong> azbуka.ru </strong></em></span></label>
        </div>
        <div class="form_radio_btn">
            <input type="radio" <?php echo $stats ?> id="radio2" name="saints_option[link]" value="2" <?php checked(2, $link) ?> />
            <label for="radio2"><span><em><strong><?php echo $vs; ?></strong></em></span></label>
        </div>
        <?php echo $errzip; ?>
        <a href="#" class="iconq" title="<?php echo $minch; ?>"><span>&#63;</span></a>
    </div>
<?php
}

function fill_saints_field4()
{
    $val = get_option('saints_option');
    if (isset($val['deleteIcons'])) {
        $deleteIcons = $val['deleteIcons'];
    } else {
        $deleteIcons = '';
    }
    //** подсчет количества картинок */
    $dir_file = wp_get_upload_dir()['basedir'] . '/saints-cache/img';
    $ff = count(glob("$dir_file/*"));

    if ($ff != 0) { //** если есть картинки */
        $stile = 'display: block;';
        $stats = '';
    } else {
        delete_option('deleteIcons');
        $stile = 'display: none;';
        $stats = 'disabled';
    }
    $udal = __(' удаление ', 'saints');
    $udalop = __('Вы можете удалить папку с миниатюрами, отметив эту опцию.<br />ВНИМАНИЕ! <strong>Это действие нельзя отменить!</strong>', 'saints');

    echo '<div class="attention_admin">';
    $rightbl1 = __("<h4 style='text-align:center;'>Спасибо за проявленный интерес к плагину Saints!</h4>
    Дорогие братия и сестры!<br />Надеюсь, что мой плагин окажется для Вас полезным!<br />Плагин Saints  предоставляет 3 шорткода:
    <ul>
        <li><strong>[sia-calendar]</strong> - для вывода виджета календаря;</li>
        <li><strong>[sia-shortinfo]</strong> - для вывода краткой информации дня;</li>
        <li><strong>[sia-saints]</strong> - для вывода жизнеописания Святых</li>
    </ul>
    Если виджет календаря не установлен, при загрузке страницы с шорткодом описания жития будет отображаться информация текущего дня.", 'saints');

    $rightbl2 = __("<br /><em>Если у Вас возникли вопросы или пожелания, вы можете направить их на siamajor@ukr.net</em><br/><strong>Береги Вас Господь!</strong>", 'saints');
    $myphoto = '<img src="' . plugins_url('saints/admin/css/435.png') . '" class="imgmy">';

    echo '<div id="rightbl">
<div>' .  $rightbl1 . '</div><div>' . $myphoto . '</div><div>' . $rightbl2 . '</div>
</div>';
echo '</div>';
?>
    <div class="sia-delete">
        <label class="checkbox"><input type="checkbox" <?php echo $stats ?> name="saints_option[deleteIcons]" value="1" <?php checked(1, $deleteIcons) ?> /><span><em><strong><?php echo $udal; ?></strong></em></span></label>
        <div style="<?php echo $stile; ?>" class=""><?php echo $udalop . ' (' . $ff . ' шт.)'; ?></div>
    </div>
   
<?php
}

## Очистка данных
function saints_sanitize_callback($options)
{
    foreach ($options as $name => &$val) {
        if ($name == 'titleOpt')
            $val = strip_tags($val);
        if ($name == 'textTop')
            $val = strip_tags($val);
        if ($name == 'showicons')
            $val = intval($val);
        if ($name == 'iconszip')
            $val = intval($val);
        if ($name == 'deleteIcons')
            $val = strip_tags($val);
        if ($name == 'link')
            $val = strip_tags($val);
    }
    return $options;
}
//** распаковка архива */
$val = get_option('saints_option');
$link = $val ? $val['link'] : null;
$dir_file = wp_get_upload_dir()['basedir'] . '/saints-cache/';
$filename = $dir_file . '/img.zip';
if ($filename && $link == 2) {
    sia_unzipIcons();
}

function sia_unzipIcons()
{
    if (!function_exists('unzip_file')) {
        require_once(ABSPATH . 'wp-admin/includes/file.php');
    }
    global $wp_filesystem;
    if (!$wp_filesystem) {
        WP_Filesystem();
    }
    $file = wp_get_upload_dir()['basedir'] . '/saints-cache/img.zip';
    $to   = wp_get_upload_dir()['basedir'] . '/saints-cache/img/';
    if (is_file($file)) {
        unzip_file($file, $to);
        wp_delete_file($file);
    }
}
//** удаление папки */
$val = get_option('saints_option');
if (isset($val['deleteIcons'])) {
    $deleteIcons = $val['deleteIcons'];
} else {
    $deleteIcons = 0;
}
if (isset($val['showicons'])) $showicons = $val['showicons'];

if ($deleteIcons == 1) {
    $folder_path = wp_get_upload_dir()['basedir'] . '/saints-cache' . DIRECTORY_SEPARATOR . 'img';
    if (is_dir($folder_path)) {
        sia_deleteIcons();
        if (isset($showicons)) delete_option('showicons');
        if (isset($deleteIcons)) delete_option('deleteIcons');
    }
}

function sia_deleteIcons()
{
    $dir = wp_get_upload_dir()['basedir'] . '/saints-cache' . DIRECTORY_SEPARATOR . 'img';
    if (is_dir($dir)) {
        $it = new RecursiveDirectoryIterator($dir);
        $files = new RecursiveIteratorIterator(
            $it,
            RecursiveIteratorIterator::CHILD_FIRST
        );
        foreach ($files as $file) {
            if ($file->getFilename() === '.' || $file->getFilename() === '..') {
                continue;
            }
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        rmdir($dir);
    }
}
