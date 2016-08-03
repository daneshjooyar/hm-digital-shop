<div class="wrap">
    <form method="post" action="options.php">
        <?php
        settings_fields("hmds_settings_options");
        do_settings_sections("hmds_setting_parspal");
        submit_button();
        ?>          
    </form>
</div>