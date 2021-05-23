<div data-slm-custom-value-setting class="<?php echo srvlm_get_option( 'content.is_custom_explainer', 0 ) ? 'active' : ''; ?>">
    <label>
        <input type="hidden" name="slm_options[content][is_custom_explainer]" value="0">
        <input type="checkbox" name="slm_options[content][is_custom_explainer]" value="1" <?php checked( srvlm_get_option( 'content.is_custom_explainer', 0 ), '1' ) ?>>
        Custom Explainer Text
    </label>
    <input type="text" class="widefat" placeholder="Enter Survey Explainer" id="slm_options_content_explainer" name="slm_options[content][explainer]" value="<?php echo srvlm_get_option( 'content.explainer' ); ?>">
</div>