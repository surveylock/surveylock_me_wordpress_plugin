<div data-slm-custom-value-setting class="<?php echo srvlm_get_option( 'snax.is_custom_cta_text', 0 ) ? 'active' : ''; ?>">
    <label>
        <input type="hidden" name="slm_options[snax][is_custom_cta_text]" value="0">
        <input type="checkbox" name="slm_options[snax][is_custom_cta_text]" value="1" <?php checked( srvlm_get_option( 'snax.is_custom_cta_text', 0 ), '1' ) ?>>
        Custom CTA Title
    </label>
    <input type="text" class="widefat" placeholder="Enter CTA Title" id="slm_options_snax_cta_text" name="slm_options[snax][cta_text]" value="<?php echo srvlm_get_option( 'snax.cta_text' ); ?>">
</div>