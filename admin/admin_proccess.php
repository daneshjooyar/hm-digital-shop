<?php

add_action('admin_menu', 'hmds_creat_admin_menu');
function hmds_creat_admin_menu(){
    global $hmds_page_hook;
    $hmds_page_hook = add_menu_page(
            __('Sell Download Link', 'hm-digital-shop'),
            __('Sell Download Link', 'hm-digital-shop'),
            'manage_options',
            'hmds_digital_product',
            function(){include(HMDS_ADMIN_VIEW . 'digital_product_page.php');}
        );
        
    add_submenu_page(
            'hmds_digital_product',
            __('Settings', 'hm-digital-shop'),
            __('Settings', 'hm-digital-shop'),
            'manage_options',
            'hmds_digital_product_settings',
            function(){include(HMDS_ADMIN_VIEW . 'digital_product_settings_page.php');}
        );
        
        add_submenu_page(
            'hmds_digital_product',
            __('Transactions', 'hm-digital-shop'),
            __('Transactions', 'hm-digital-shop'),
            'manage_options',
            'hmds_digital_product_transactions',
            function(){include(HMDS_ADMIN_VIEW . 'digital_product_transactions_page.php');}
        );
    
}

add_action('admin_enqueue_scripts', function($hook){
    global $hmds_page_hook;
    if( $hook != $hmds_page_hook )
        return;
    
    wp_register_script('lighboxme', HMDS_ADMIN_JS . 'jquery.lightbox_me.js', array('jquery'), '1.0.0');
    wp_enqueue_script('hmds-script', HMDS_ADMIN_JS . 'hmds-script.js', array('jquery', 'lighboxme', 'media-upload', 'thickbox'), '1.0.0');
    wp_localize_script('hmds-script', 'hmds_data', array(
        'ajaxurl'               => admin_url('admin-ajax.php'),
        'hmds_wpnonce'          => wp_create_nonce('hmds_save_link'),
        'hmds_wpnonce_delete'   => wp_create_nonce('hmds_delete_link'),
        'sure'                  => __('Are you sure?', 'hm-digital-shop'),
        'err'                   => __('An error happen', 'hm-digital-shop'),
    ));
    wp_enqueue_style('hmds-style', HMDS_ADMIN_CSS . 'digital-product.css', array('thickbox'));
    
});

add_action("admin_init", function(){
    
    add_settings_section('hmds_settings_options', __('Parspal settings', 'hm-digital-shop'), null, 'hmds_setting_parspal');
    
    add_settings_field('hmds_setting_merchant_id', __('Merchant ID', 'hm-digital-shop'), 'hmds_setting_merchant_id_callback', 'hmds_setting_parspal', 'hmds_settings_options');
    add_settings_field('hmds_setting_password', __('Password', 'hm-digital-shop'), 'hmds_setting_password_callback', 'hmds_setting_parspal', 'hmds_settings_options');
    
    register_setting('hmds_settings_options', 'hmds_pass', 'sanitize_text_field');
    register_setting('hmds_settings_options', 'hmds_mid', 'sanitize_text_field');
    
});

function hmds_setting_merchant_id_callback(){
    echo '<input class="ltr left-align" type="text" name="hmds_mid" id="hmds_mid" value="' . get_option('hmds_mid','') . '" />';
}

function hmds_setting_password_callback(){
    echo '<input class="ltr left-align" type="text" name="hmds_pass" id="hmds_pass" value="' . get_option('hmds_pass','') . '" />';
}
