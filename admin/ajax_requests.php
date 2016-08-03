<?php

add_action('wp_ajax_hmds_save_link', function(){
    //wp_send_json($_POST);
    $result = array(
        'result'    => 'no',
        'data'      => array(
            'message'   => ''
        )
    );
    
    if(!current_user_can('manage_options')){
        $result['data']['message'] = __('You do not have permission to access...', 'hm-digital-shop');
        wp_send_json($result);
    }
    
    if( !wp_verify_nonce($_POST['hmds_wpnonce'], 'hmds_save_link') ){
        $result['data']['message'] = __('Nonce is not correct!', 'hm-digital-shop');
        wp_send_json($result);
    }
    
    if ( trim($_POST['title']) == '' || trim($_POST['price']) == '' || trim($_POST['link']) == '' ) {
        $result['data']['message'] = __('Please complete information', 'hm-digital-shop');
        wp_send_json( $result );
    }
    
    global $wpdb;
    $table = $wpdb->prefix  . 'digital_product';
    
    if(absint($_POST['file_id']) == 0) {
        //inserting file
        
        $insert = $wpdb->insert(
                $table,
                array(
                    'title'         => sanitize_text_field($_POST['title']),
                    'price'         => absint( $_POST['price'] ),
                    'download_link' => esc_url_raw( $_POST['link'] ),
                    'create_time' => current_time( 'mysql' )
                ),
                array( '%s', '%d', '%s', '%s' )
                );
        
        if($insert){
            $result['result'] = 'ok';
            $result['data']['message'] = __('Data added successfully', 'hm-digital-shop');
            wp_send_json( $result );
        }else{
            $result['data']['message'] = __('Error in saving data', 'hm-digital-shop');
            wp_send_json( $result );
        }
        
    }else{
        //updating file
        $update = $wpdb->update(
                $table,
                array(
                    'title'         => sanitize_text_field($_POST['title']),
                    'price'         => absint( $_POST['price'] ),
                    'download_link' => esc_url_raw( $_POST['link'] ),
                ),
                array('id' => absint($_POST['file_id'])),
                array( '%s', '%d', '%s' ),
                array('%d')
                );
        if ( $update ) {
            $result['result'] = 'ok';
            $result['data']['message'] = __('Data updated successfully', 'hm-digital-shop');
            wp_send_json( $result );
        }else{
            $result['data']['message'] = __('Error in updating', 'hm-digital-shop');
            wp_send_json( $result );
        }
    }
    
});

add_action('wp_ajax_hmds_full_data', function(){
    
    hmds_get_products();
    exit;
    
});

add_action('wp_ajax_hmds_delete_link', function(){
    $result = array(
        'result'    => 'no',
        'data'      => array(
            'message'   => ''
        )
    );
    
    if(!current_user_can('manage_options')){
        $result['data']['message'] = __('You do not have permission to access...', 'hm-digital-shop');
        wp_send_json($result);
    }
    
    if( !wp_verify_nonce($_POST['wpnonce_delete'], 'hmds_delete_link') ){
        $result['data']['message'] = __('Nonce is not correct!', 'hm-digital-shop');
        wp_send_json($result);
    }
    
    global $wpdb;
    $table = $wpdb->prefix  . 'digital_product';
    $table2 = $wpdb->prefix  . 'digital_product_transaction';
    $product_id = absint($_POST['product_id']);
    
    if( $wpdb->delete($table, array('id' => $product_id), array('%d')) ) {
        $wpdb->delete($table2, array('file' => $product_id), array('%d'));
        $result['result'] = 'ok';
        $result['data']['message'] = __('File save successfully', 'hm-digital-shop');
        wp_send_json( $result );
    }else{
        $result['data']['message'] = __('Error in deleteing data', 'hm-digital-shop');
        wp_send_json($result);
    }
    
});