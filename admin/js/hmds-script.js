jQuery(document).ready(function($){
    $("input#hmds_show_insert_link").click(function(e){
        $("#hmds_insert_file").lightbox_me({
            centered: true, 
            onLoad: function() { 
                var fields = $('div#hmds_insert_file input');
                $(fields).eq(0).val('');
                $(fields).eq(1).val('');
                $(fields).eq(2).val('');
                $(fields).eq(4).val(0);
                
            }
            });
        e.preventDefault();
    });
    
    $("#hmds_select_url").click(function(){
        tb_show('', 'media-upload.php?type=file&TB_iframe=true');
        
        window.send_to_editor = function(html){
            //console.log(html);
            var fileUrl = $(html).attr('href');
            console.log(fileUrl);
            $("input#hmds_fileurl").val(fileUrl);
            tb_remove();
        }
        
        return false;
    });
    $("#hmds_insert_file form").submit(function(){
        var title   = $(this).find("#hmds_filename").val();
        var price   = $(this).find("#hmds_fileprice").val();
        var link    = $(this).find("#hmds_fileurl").val();
        var file_id = $(this).find("#hmds_file_id").val();
        
        
        
        $.ajax({
            type    : 'post',
            url     : hmds_data.ajaxurl,
            data    : {
                action  : 'hmds_save_link',
                title   : title,
                price   : price,
                link    : link,
                file_id : file_id,
                hmds_wpnonce : hmds_data.hmds_wpnonce
            },
            beforeSend: function (xhr) {
                $("#hmds_status").css('visibility', 'visible');
            },
            error: function (jqXHR, textStatus, errorThrown) {
                $("#hmds_status")
                        .css({
                            'visibility': 'visible',
                            'color'     : 'red'
                }).text(hmds_data.err);
            },
            success: function (data, textStatus, jqXHR) {
                if ( data.result == 'ok' ) {
                    $("#hmds_status").css({
                            'visibility': 'visible',
                            'color'     : 'green'
                            }).text(data.data.message);
                    update_products();
                }else {
                    $("#hmds_status")
                        .css({
                            'visibility': 'visible',
                            'color'     : 'red'
                        }).text(data.data.message);
                }
            }
        });
        
        return false;
    });
    
    function update_products(){
        $.get(hmds_data.ajaxurl + '?action=hmds_full_data', function(html){
            $("table#hmds_data_table tbody").html(html);
        });
    }
    
    $(document).on('click', 'a.hmds_delete', function(){
        if(!confirm(hmds_data.sure)) return false;
        var product_id = $(this).data('product_id');
        $.ajax({
            type    : 'post',
            url     : hmds_data.ajaxurl,
            data    : {
                action  : 'hmds_delete_link',
                product_id   : product_id,
                wpnonce_delete : hmds_data.hmds_wpnonce_delete
            },
            error: function (jqXHR, textStatus, errorThrown) {
                alert(hmds_data.err)
            },success: function (data, textStatus, jqXHR) {
                if(data.result == 'ok'){
                    update_products();
                }
            }
        });
        return false;
    });
    
    $(document).on('click', 'a.hmds_edit', function(){
        var rows = $(this).closest('tr').find('td');
        var product_id = $(this).data('product_id');
        var product_name = $(rows).eq(1).text();
        var product_price = $(rows).eq(2).text();
        var product_url = $(rows).eq(3).text();
        
        $("#hmds_insert_file").lightbox_me({
            centered: true, 
            onLoad: function() { 
                var fields = $('div#hmds_insert_file input');
                $(fields).eq(0).val(product_name);
                $(fields).eq(1).val(product_price);
                $(fields).eq(2).val(product_url);
                $(fields).eq(4).val(product_id);
            }
            });
        
        return false;
    });
    
});

