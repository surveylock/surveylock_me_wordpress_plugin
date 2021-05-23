<div data-slm-custom-value-setting class="<?php echo srvlm_get_option( 'snax.is_custom_cta_button_text', 0 ) ? 'active' : ''; ?>">
    <label>
        <input type="hidden" name="slm_options[snax][is_custom_cta_button_text]" value="0">
        <input type="checkbox" name="slm_options[snax][is_custom_cta_button_text]" value="1" <?php checked( srvlm_get_option( 'snax.is_custom_cta_button_text', 0 ), '1' ) ?>>
        Custom CTA Button Text
    </label>
    <input type="text" class="widefat" placeholder="Enter CTA Button Text" id="slm_options_snax_cta_button_text" name="slm_options[snax][cta_button_text]" value="<?php echo srvlm_get_option( 'snax.cta_button_text' ); ?>">
</div>