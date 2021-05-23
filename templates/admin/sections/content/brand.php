<div data-slm-custom-value-setting class="<?php echo srvlm_get_option( 'content.is_custom_brand', 0 ) ? 'active' : ''; ?>">
    <label>
        <input type="hidden" name="slm_options[content][is_custom_brand]" value="0">
        <input type="checkbox" name="slm_options[content][is_custom_brand]" value="1" <?php checked( srvlm_get_option( 'content.is_custom_brand', 0 ), '1' ) ?>>
        Custom Brand Text
    </label>
    <input type="text" class="widefat" placeholder="Enter Survey Brand" id="slm_options_content_brand" name="slm_options[content][brand]" value="<?php echo srvlm_get_option( 'content.brand' ); ?>">
</div>