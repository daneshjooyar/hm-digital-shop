<?php
/*
 * Plugin Name: HM File Link Shop
 * Plugin URI: http://example.com
 * Author: Hamed Moodi
 * Author URI: http://ircodex.ir/author/4dmin/
 * Description: This plugin is way to sell download link with parspal
 * Version: 1.0.0
 * Licence: GPLv2
 * Text Domain: hm-digital-shop
 * Domain Path: /languages
 */
/*
This plugin is a simple file shoping for Ecommerecing
Copyright (C) 2016  Hamed Moodi

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
Also add information on how to contact you by electronic and paper mail.

If the program is interactive, make it output a short notice like this when it starts in an interactive mode:

Gnomovision version 69, Copyright (C) year name of author
Gnomovision comes with ABSOLUTELY NO WARRANTY; for details
type `show w'.  This is free software, and you are welcome
to redistribute it under certain conditions; type `show c' 
for details.
*/

defined('ABSPATH') || exit;

define('HMDS_INC', plugin_dir_path(__FILE__) . 'inc/');
define('HMDS_ADMIN', plugin_dir_path(__FILE__) . 'admin/');
define('HMDS_ADMIN_VIEW', plugin_dir_path(__FILE__) . 'admin/view/');
define('HMDS_ADMIN_JS', plugin_dir_url(__FILE__) . 'admin/js/');
define('HMDS_ADMIN_CSS', plugin_dir_url(__FILE__) . 'admin/css/');
define('HMDS_CSS', plugin_dir_url(__FILE__) . 'css/');
define('HMDS_JS', plugin_dir_url(__FILE__) . 'js/');

add_action('plugins_loaded', function(){
    load_plugin_textdomain('hm-digital-shop', false, basename(plugin_dir_path(__FILE__)) . '/languages/');
});

register_activation_hook(__FILE__, 'hmds_activation_func');
function hmds_activation_func(){
    global $wpdb;
    
    $table = $wpdb->prefix . 'digital_product';
    $table2 = $wpdb->prefix . 'digital_product_transaction';
    
    $query1 = "CREATE TABLE `{$table}` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `title` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
    `price` int(11) DEFAULT '0',
    `download_link` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
    `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
    PRIMARY KEY (`id`)
   ) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    
    $query2 = "CREATE TABLE `{$table2}` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `user` int(11) NOT NULL,
        `file` int(11) NOT NULL,
        `price` int(11) NOT NULL,
        `res_number` varchar(40) COLLATE utf8_unicode_ci NOT NULL,
        `ref_number` varchar(20) COLLATE utf8_unicode_ci NOT NULL,
        `description` text COLLATE utf8_unicode_ci NOT NULL,
        `paymenter` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
        `email` varchar(250) COLLATE utf8_unicode_ci NOT NULL,
        `mobile` varchar(11) COLLATE utf8_unicode_ci NOT NULL,
        `create_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
        PRIMARY KEY (`id`)
       ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci";
    
    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($query1);
    dbDelta($query2);
    
}

register_uninstall_hook(__FILE__, 'hmds_uninstall_func');
function hmds_uninstall_func(){
    global $wpdb;
    
    $table = $wpdb->prefix . 'digital_product';
    $table2 = $wpdb->prefix . 'digital_product_transaction';
    
    $wpdb->query("DROP TABLE IF EXISTS $table");
    $wpdb->query("DROP TABLE IF EXISTS $table2");
}

if(is_admin()){
    require(HMDS_ADMIN . 'admin_proccess.php');
    require(HMDS_ADMIN . 'ajax_requests.php');
}

include(HMDS_INC . 'functions.php');
include(HMDS_INC . 'shortcode.php');