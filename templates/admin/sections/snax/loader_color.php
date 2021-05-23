<div data-slm-custom-value-setting class="<?php echo srvlm_get_option( 'snax.is_custom_loader_color', 0 ) ? 'active' : ''; ?>">
    <label>
        <input type="hidden" name="slm_options[snax][is_custom_loader_color]" value="0">
        <input type="checkbox" name="slm_options[snax][is_custom_loader_color]" value="1" <?php checked( srvlm_get_option( 'snax.is_custom_loader_color', 0 ), '1' ) ?>>
        Custom Loader Color
    </label>
    <input type="text" class="regular-text" data-slm-colorpicker placeholder="Select Loader Color" id="slm_options_snax_loader_color" name="slm_options[snax][loader_color]" value="<?php echo srvlm_get_option( 'snax.loader_color', srvlm_default_color() ); ?>">
</div>