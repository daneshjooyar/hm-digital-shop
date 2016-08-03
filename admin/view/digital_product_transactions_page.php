<?php
global $wpdb;
$table = $wpdb->prefix . 'digital_product_transaction';

$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = 10; // number of rows in page
$offset = ( $pagenum - 1 ) * $limit;
$total = $wpdb->get_var( "SELECT COUNT(`id`) FROM $table" );
$num_of_pages = ceil( $total / $limit );

$numrow = ($pagenum - 1) * $offset + 1;

$transactions = $wpdb->get_results( "SELECT * FROM $table LIMIT $offset, $limit" );
?>
<div class="wrap">
    <h2><?php esc_html_e('Transactions', 'hm-digital-shop');?></h2>
    <table class="widefat">
        <thead>
            <tr>
                <th><?php _e('Row', 'hm-digital-shop');?></th>
                <th><?php _e('Paymenter', 'hm-digital-shop');?></th>
                <th><?php _e('Price', 'hm-digital-shop');?></th>
                <th><?php _e('Payment title', 'hm-digital-shop');?></th>
                <th><?php _e('Reserve Number', 'hm-digital-shop');?></th>
                <th><?php _e('Recieve Number', 'hm-digital-shop');?></th>
                <th><?php _e('Mobile Number', 'hm-digital-shop');?></th>
                <th><?php _e('Email', 'hm-digital-shop');?></th>
                <th><?php _e('Date', 'hm-digital-shop');?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th><?php _e('Row', 'hm-digital-shop');?></th>
                <th><?php _e('Paymenter', 'hm-digital-shop');?></th>
                <th><?php _e('Price', 'hm-digital-shop');?></th>
                <th><?php _e('Payment title', 'hm-digital-shop');?></th>
                <th><?php _e('Reserve Number', 'hm-digital-shop');?></th>
                <th><?php _e('Recieve Number', 'hm-digital-shop');?></th>
                <th><?php _e('Mobile Number', 'hm-digital-shop');?></th>
                <th><?php _e('Email', 'hm-digital-shop');?></th>
                <th><?php _e('Date', 'hm-digital-shop');?></th>
            </tr>
        </tfoot>
        <tbody>
            <?php if ( empty( $transactions ) ): ?>
            <tr><td colspan="9"><?php _e('Not transaction found...');?></td></tr>
            <?php else:?>
                <?php foreach( $transactions as $t ):?>
            <tr>
                <td><?php echo $numrow;$numrow++?></td>
                <td><?php echo $t->paymenter;?></td>
                <td><?php echo $t->price;?></td>
                <td><?php echo $t->description;?></td>
                <td><?php echo $t->res_number;?></td>
                <td><?php echo $t->ref_number;?></td>
                <td><?php echo $t->mobile;?></td>
                <td><?php echo $t->email;?></td>
                <td><?php echo $t->create_time;?></td>
            </tr>
                <?php endforeach;?>
            <?php endif;?>
        </tbody>
    </table>
    <?php
        $page_links = paginate_links( array(
            'base' => add_query_arg( 'pagenum', '%#%' ),
            'format' => '',
            'prev_text' => __( '&laquo;', 'text-domain' ),
            'next_text' => __( '&raquo;', 'text-domain' ),
            'total' => $num_of_pages,
            'current' => $pagenum
        ) );

        if ( $page_links ) {
            echo '<div class="tablenav"><div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div></div>';
        }
    ?>
</div>
