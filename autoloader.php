<?php defined('ABSPATH') || exit;


// require_once __DIR__.'/helper.php';
require_once __DIR__.'/api.php';
require_once __DIR__.'/web.php';
require_once __DIR__.'/shortcode.php';
require_once __DIR__.'/actions/order.php';
require_once __DIR__.'/actions/ajax_req.php';


//defines


add_action('init', 'keyval_install' );
/**
 * key val installation
 *
 * @return void
 */
function keyval_install(){
    global $wpdb;

    add_option('keyval_jwt_token', '');

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    $charset_collate = $wpdb->get_charset_collate();

    require_once __DIR__.'/migrations.php';
}
