<div data-slm-custom-value-setting class="<?php echo srvlm_get_option( 'content.is_custom_cta_button_text', 0 ) ? 'active' : ''; ?>">
    <label>
        <input type="hidden" name="slm_options[content][is_custom_cta_button_text]" value="0">
        <input type="checkbox" name="slm_options[content][is_custom_cta_button_text]" value="1" <?php checked( srvlm_get_option( 'content.is_custom_cta_button_text', 0 ), '1' ) ?>>
        Custom CTA Button Text
    </label>
    <input type="text" class="widefat" placeholder="Enter CTA Button Text" id="slm_options_content_cta_button_text" name="slm_options[content][cta_button_text]" value="<?php echo srvlm_get_option( 'content.cta_button_text' ); ?>">
</div>