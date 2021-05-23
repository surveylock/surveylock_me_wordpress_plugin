<div data-slm-custom-value-setting class="<?php echo srvlm_get_option( 'snax.is_custom_explainer', 0 ) ? 'active' : ''; ?>">
    <label>
        <input type="hidden" name="slm_options[snax][is_custom_explainer]" value="0">
        <input type="checkbox" name="slm_options[snax][is_custom_explainer]" value="1" <?php checked( srvlm_get_option( 'snax.is_custom_explainer', 0 ), '1' ) ?>>
        Custom Explainer Text
    </label>
    <input type="text" class="widefat" placeholder="Enter Survey Explainer" id="slm_options_snax_explainer" name="slm_options[snax][explainer]" value="<?php echo srvlm_get_option( 'snax.explainer' ); ?>">
</div>