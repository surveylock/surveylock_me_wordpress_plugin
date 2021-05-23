<div data-slm-custom-value-setting class="<?php echo srvlm_get_option( 'content.is_custom_cta_button_color', 0 ) ? 'active' : ''; ?>">
    <label>
        <input type="hidden" name="slm_options[content][is_custom_cta_button_color]" value="0">
        <input type="checkbox" name="slm_options[content][is_custom_cta_button_color]" value="1" <?php checked( srvlm_get_option( 'content.is_custom_cta_button_color', 0 ), '1' ) ?>>
        Custom CTA Button Color
    </label>
    <input type="text" class="regular-text" data-slm-colorpicker placeholder="Select CTA Button Color" id="slm_options_content_cta_button_color" name="slm_options[content][cta_button_color]" value="<?php echo srvlm_get_option( 'content.cta_button_color', srvlm_default_color('#5cb85c') ); ?>">
</div>