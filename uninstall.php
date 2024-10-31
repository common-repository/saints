<?php
if (!defined('ABSPATH')) {
    die('Sorry, you are not allowed to access this page directly.');
}
if (!defined('WP_UNINSTALL_PLUGIN'))
	exit;

	delete_option('saints_option');
	$dir = wp_get_upload_dir()['basedir'] . '/saints-cache/';

	require_once ( ABSPATH . '/wp-admin/includes/class-wp-filesystem-direct.php' );
$fileSystemDirect = new WP_Filesystem_Direct(false);
$fileSystemDirect->rmdir($dir, true);
