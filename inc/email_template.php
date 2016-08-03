<body style="background-color: #EEE;width:100%;height:100%">
    <div style="font-family: tahoma;font-size: 100%;direction: <?php echo is_rtl() ? 'rtl' : 'ltr' ?>;border:1px solid #CCC;width:500px; margin:50px auto;background-color: #FFF;border-radius: 3px;
         -webkit-border-radius: 3px;
         -o-border-radius: 3px;
         -moz-border-radius: 3px;
         -ms-border-radius: 3px;">
        <div style="width:100%;border-bottom: 2px solid #CCC;text-align: center;font-size: 10pt;padding: 20px 0;background-color:#DDD;"><?php _e('Payment information', 'hm-digital-shop');?></div>
        <div style="font-size: 9pt;padding: 20px;line-height: 150%;">
            <h4 style=" text-align: <?php echo is_rtl() ? 'right' : 'left';?>;margin-bottom: 20px;color:#009;"><?php _e('Your payment operation successfully finished, please save payment information', 'hm-digital-shop'); ?></h4>
          <p><span><?php _e('Download Link Name', 'hm-digital-shop');?>: </span>{name}</p>
            <p><span><?php _e('Reserve Number', 'hm-digital-shop');?>: </span> {res_number}</p>
            <p><span><?php _e('Recieve Number', 'hm-digital-shop');?>: </span> {ref_number}</p>
            <p><span><?php _e('Download Link', 'hm-digital-shop');?>: </span>{link}</p>
        </div>
    </div>
</body>