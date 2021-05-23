<div data-slm-custom-value-setting class="<?php echo srvlm_get_option( 'content.is_custom_loader_text', 0 ) ? 'active' : ''; ?>">
    <label>
        <input type="hidden" name="slm_options[content][is_custom_loader_text]" value="0">
        <input type="checkbox" name="slm_options[content][is_custom_loader_text]" value="1" <?php checked( srvlm_get_option( 'content.is_custom_loader_text', 0 ), '1' ) ?>>
        Custom Loader Text
    </label>
    <input type="text" class="widefat" placeholder="Enter Loader Text" id="slm_options_content_loader_text" name="slm_options[content][loader_text]" value="<?php echo srvlm_get_option( 'content.loader_text' ); ?>">
</div>