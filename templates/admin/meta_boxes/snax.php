<label>
    Enable Integration:&nbsp;
</label>
<label>
    <input type="radio" name="slm_config[snax][is_enabled]" value="1" <?php checked(srvlm_get_post_config($post->ID,  'snax.is_enabled' , 1), '1') ?>>
    Yes
</label>
&nbsp;
<label>
    <input type="radio" name="slm_config[snax][is_enabled]" value="0" <?php checked(srvlm_get_post_config($post->ID,  'snax.is_enabled' , 1), '0') ?>>
    No
</label>