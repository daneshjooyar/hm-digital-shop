<?php
function hmds_get_products(){
    
    global $wpdb;
    $table = $wpdb->prefix . 'digital_product';
    
    $query = "SELECT * FROM $table ORDER BY id DESC";
    $result = $wpdb->get_results($query, OBJECT);
    
    if(!empty($result)) {
        foreach( $result as $product ){
            ?>
<tr>
    <td><?php echo $product->id;?></td>
    <td><?php echo $product->title;?></td>
    <td><?php echo $product->price;?></td>
    <td class="ltr left-align"><?php echo esc_url($product->download_link);?></td>
    <td><a href="<?php echo esc_url($product->download_link);?>" target="_blank"><?php _e('Download', 'hm-digital-shop');?></a></td>
    <td><?php echo $product->create_time;?></td>
    <td>[digital_product id=<?php echo absint($product->id) ;?>]</td>
    <td>
        <?php echo '<a href="#" class="hmds_delete" data-product_id="' . esc_attr($product->id) . '">' . __('Delete', 'hm-digital-shop') . '</a>';?> | 
        <?php echo '<a href="#" class="hmds_edit" data-product_id="' . esc_attr($product->id) . '">' . __('Edit', 'hm-digital-shop') . '</a>';?>
    </td>
</tr>
            <?php
        }
    }else{
        echo '<tr><td colspan="8">' . __('No Download link found...', 'hm-digital-shop') . '</td></tr>';
    }
    
}

function hmds_get_product( $product_id ) {
    global $wpdb;
    $table = $wpdb->prefix . 'digital_product';
    return $wpdb->get_row($wpdb->prepare("SELECT * FROM $table WHERE id = %d", $product_id), OBJECT);
}

function hmds_current_user_buyed_product( $prodcut_id, $user_id ) {
    global $wpdb;
    $table = $wpdb->prefix . 'digital_product_transaction';
    return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM $table WHERE file = %d AND user = %d AND ref_number <> 'null'", $prodcut_id, $user_id));
}

function hmds_create_factor($user_id, $file_id, $price, $ResNumber, $description, $paymenter, $email, $mobile){
    global $wpdb;
    $table = $wpdb->prefix . 'digital_product_transaction';
    $insertResult = $wpdb->insert($table, array(
        'user'          => $user_id,
        'file'          => $file_id,
        'price'         => $price,
        'res_number'    => $ResNumber,
        'ref_number'    => 'null',
        'description'   => $description,
        'paymenter'     => $paymenter,
        'email'         => $email,
        'mobile'        => $mobile,
        'create_time'   => current_time( 'mysql' ))
        );
    return $insertResult;
}

function hmds_pay_factor( $ResNumber, $RefNumber ){
    global $wpdb;
    $table = $wpdb->prefix . 'digital_product_transaction';
    return $wpdb->update($table, array('ref_number' => $RefNumber), array('res_number' => $ResNumber));
}

function sendEmail($name,$ref,$res,$link, $email){
ob_start();
include HMDS_INC . 'email_template.php';
$html=ob_get_contents();
ob_end_clean();
$html=  str_replace('{name}',$name, $html);
$html=  str_replace('{res_number}',$res, $html);
$html=  str_replace('{ref_number}',$ref, $html);
$html=  str_replace('{link}',$link,$html);
$headers  = 'From: no-reply@domain.com'. "\r\n" .
	'MIME-Version: 1.0' . "\r\n" .
    'Content-type: text/html; charset=utf-8' . "\r\n" .
	'X-Mailer: PHP/' . phpversion();
	
 return mail($email,__('Payment information', 'hm-digital-shop'), $html, $headers);	
}