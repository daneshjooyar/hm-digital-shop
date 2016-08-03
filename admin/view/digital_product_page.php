<div class="wrap">
    <h2><?php esc_html_e('Download Links', 'hm-digital-shop');?></h2>
    
    <div id="hmds_insert_file">
        <form action="" method="post">
            <p>
                <label for="hmds_filename"><?php _e('File Name');?></label>
                <input type="text" class="widefat" id="hmds_filename" name="hmds_filename"/>
            </p>
            <p>
                <label for="hmds_fileprice"><?php _e('Price');?></label>
                <input type="text" class="widefat ltr left-align" pattern="\d{3,}" id="hmds_fileprice" name="hmds_fileprice"/>
            </p>
            <p>
                <label for="hmds_fileurl"><?php _e('File Link', 'hm-digital-shop');?></label>
                <input type="url" class="widefat ltr left-align" id="hmds_fileurl" name="hmds_fileurl"/>
                <input type="button" id="hmds_select_url" class="button button-primary" value="<?php esc_attr_e('Select File', 'hm-digital-shop');?>"/>
            </p>
            <input type="hidden" name="hmds_file_id" id="hmds_file_id" value="0"/>
            <div id="hmds_status"><?php _e('Please wait...', 'hm-digital-shop');?></div>
            <input type="submit" value="<?php esc_attr_e('Save', 'hm-digital-shop');?>" id="hmds_save_link" class="button button-secondary"/>
        </form>
    </div>
    <input type="button" class="button button-primary" id="hmds_show_insert_link" value="<?php esc_attr_e('Add link', 'hm-digital-shop'); ?>">
    <hr>
    <table class="widefat" id="hmds_data_table">
        <thead>
            <tr>
                <th><?php _e('Link ID', 'hm-digital-shop');?></th>
                <th><?php _e('Link Name', 'hm-digital-shop');?></th>
                <th><?php _e('Link Price', 'hm-digital-shop');?></th>
                <th><?php _e('Download address', 'hm-digital-shop');?></th>
                <th><?php _e('Download Link', 'hm-digital-shop');?></th>
                <th><?php _e('Created Time', 'hm-digital-shop');?></th>
                <th><?php _e('Shortcode', 'hm-digital-shop');?></th>
                <th><?php _e('Operation', 'hm-digital-shop');?></th>
            </tr>
        </thead>
        <tfoot>
            <tr>
                <th><?php _e('Link ID', 'hm-digital-shop');?></th>
                <th><?php _e('Link Name', 'hm-digital-shop');?></th>
                <th><?php _e('Link Price', 'hm-digital-shop');?></th>
                <th><?php _e('Download address', 'hm-digital-shop');?></th>
                <th><?php _e('Download Link', 'hm-digital-shop');?></th>
                <th><?php _e('Created Time', 'hm-digital-shop');?></th>
                <th><?php _e('Shortcode', 'hm-digital-shop');?></th>
                <th><?php _e('Operation', 'hm-digital-shop');?></th>
            </tr>
        </tfoot>
        <tbody>
            <?php hmds_get_products();?>
        </tbody>
    </table>
</div>