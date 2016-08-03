<?php

add_action('init', function(){
    add_shortcode('digital_product' , 'hmds_show_product_shortcode');
});

function hmds_show_product_shortcode($atts, $content = null ){
    
    
    wp_enqueue_style('product-style', HMDS_CSS . 'product-style.css');
    wp_enqueue_script('product-script', HMDS_JS . 'product-script.js', array('jquery'));
    
    if ( !is_user_logged_in() ) {
        //User Must Login
        $login = '<div id="hmdp_must_login">';
        $login.=  __('You must login, please ','hm-digital-shop') ;
        $login.= '<a href="' . wp_login_url(get_permalink()) . '">' . __('SignIn','hm-digital-shop') . '</a>';
        $login.= ' ' . __('Or','hm-digital-shop') . ' <a href="'.wp_registration_url().'">' . __('SignUp','hm-digital-shop') . '</a>';
        $login.= '</div>';
        return $login;
    }
    
    extract(shortcode_atts(array(
        'id' => 0
    ), $atts));
    
    $product = hmds_get_product( $id );
    
    if( !$product ){
        return '<div id="hmdp_product_not_found">'. __('File not found','hm-digital-shop') .'</div>';
    }
    
    $user = wp_get_current_user();
    
    $MerchantID = get_option('hmds_mid','');
    $Password   = get_option('hmds_pass','');
    
    ///////Buy
    if ( isset( $_POST['hmds_buy_product'] ) ) {
        
        $Price = absint($product->price); //Price By Toman
        $ReturnPath = get_the_permalink();

        $ResNumber = uniqid() ;// Order Id In Your System
        $Description = urlencode($product->title);
        $Paymenter = sanitize_text_field($_POST['hmds_buy_paymenter']);
        $Email = sanitize_text_field($_POST['hmds_buy_email']);
        $Mobile = sanitize_text_field($_POST['hmds_buy_paymenter_mobile']);
        
        $client = new SoapClient('http://merchant.parspal.com/WebService.asmx?wsdl');

        $res = $client->RequestPayment(array(
            "MerchantID"    =>  $MerchantID ,
            "Password"      =>  $Password ,
            "Price"         =>  $Price,
            "ReturnPath"    =>  $ReturnPath,
            "ResNumber"     =>  $ResNumber,
            "Description"   =>  $Description,
            "Paymenter"     =>  $Paymenter,
            "Email"         =>  $Email,
            "Mobile"        =>  $Mobile));
        
        $PayPath = $res->RequestPaymentResult->PaymentPath;
        $Status = $res->RequestPaymentResult->ResultStatus;
        
        if($Status == 'Succeed') {
            hmds_create_factor($user->ID, $product->id, $product->price, $ResNumber, $product->title, $Paymenter, $Email, $Mobile);
return "<html><head><title>".__('Connecting...', 'hm-digital-shop')."</title><head><body onload=\"javascript:window.location='$PayPath'\"style=\"font-family:tahoma; text-align:center;font-waight:bold;direction:rtl\">".__('Please wait...', 'hm-digital-shop')."</body></html>";
        }else {
            return '<div id="hmdp_product_not_found">'. __('You can not buy this link, Please contact with Admin', 'hm-digital-shop') .'</div>';
        }
    }
    
    if(isset( $_POST['status'] ) && $_POST['status'] == 100) {
        //
        $Status     = sanitize_text_field($_POST['status']);
        $Refnumber  = sanitize_text_field($_POST['refnumber']);
        $Resnumber  = sanitize_text_field($_POST['resnumber']);
        
        $client = new SoapClient('http://merchant.parspal.com/WebService.asmx?wsdl');
        $res = $client->VerifyPayment(array(
            "MerchantID"    => $MerchantID ,
            "Password"      => $Password ,
            "Price"         => absint($product->price),
            "RefNum"        => $Refnumber ));
        
        $Status     = $res->verifyPaymentResult->ResultStatus;
        $PayPrice   = $res->verifyPaymentResult->PayementedPrice;
        
        if($Status == 'success')// Your Peyment Code Only This Event
        { 
            
            hmds_pay_factor($Resnumber, $Refnumber);
            
            sendEmail($product->title,$Refnumber,$Resnumber,  esc_url_raw($product->download_link), $user->user_email);
            
        }else{
            return '<div id="hmdp_product_not_found">' . __('You can not buy this link, Please contact with Admin', 'hm-digital-shop').'</div>';
        } 
        
    }
    
    if( hmds_current_user_buyed_product($product->id, $user->ID) ){
        return '<a class="hmds_download_link" href="' . $product->download_link . '" target="_blank" title="' . $product->title . '">' . __('Download','hm-digital-shop') . '</a>';
    }else{
        if( $MerchantID == '' || $Password   == '' ){
            return '<div id="hmdp_product_not_found">' . __('You can not buy this link, Please contact with Admin', 'hm-digital-shop') . '</div>';
        }
    ob_start();
        ?>
<div id="hmds_buy_container">
    <p style="display: inline;"><?php _e('For download you must buy download link', 'hm-digital-shop')?></p>
    <a href="#" class="hmdp_buy_show"><?php _e('Buy', 'hm-digital-shop');?></a>
    <form action="<?php echo esc_url( $_SERVER['REQUEST_URI'] );?>" method="post">
        <p>
            <label for="hmds_buy_title"><?php _e('Link Title', 'hm-digital-shop');?></label>
            <input type="text" id="hmds_buy_title" disabled value="<?php echo esc_attr($product->title);?>"/>
        </p>
        <p>
            <label for="hmds_buy_price"><?php _e('Price', 'hm-digital-shop');?></label>
            <input type="text" id="hmds_buy_price" disabled value="<?php echo esc_attr($product->price);?>" />
        </p>
        <p>
            <label for="hmds_buy_email"><?php _e('Email', 'hm-digital-shop');?></label>
            <input type="text" id="hmds_buy_email" disabled value="<?php echo esc_attr($user->user_email);?>" />
            <input type="hidden" name="hmds_buy_email" value="<?php echo esc_attr($user->user_email);?>"/>
        </p>
        <p>
            <label for="hmds_buy_paymenter"><?php _e('Paymenter Name', 'hm-digital-shop');?></label>
            <input type="text" id="hmds_buy_paymenter" name="hmds_buy_paymenter" value="<?php echo $user->display_name;?>" />
        </p>
        <p>
            <label for="hmds_buy_paymenter_mobile"><?php _e('Mobile Number', 'hm-digital-shop');?></label>
            <input type="text" id="hmds_buy_paymenter_mobile" name="hmds_buy_paymenter_mobile" value=""/>
        </p>
        <p>
            <input type="submit" value="<?php esc_attr_e('Buy', 'hm-digital-shop');?>" name="hmds_buy_product" class="hmds_buy_btn" />
        </p>
    </form>
</div>
        <?php
        return ob_get_clean();
    }
}