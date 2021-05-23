<label>
    Enable Locking Content with Shortcode:&nbsp;
</label>
<label>
    <input type="radio" name="slm_config[content][is_enabled]" value="1" <?php checked(srvlm_get_post_config($post->ID,  'content.is_enabled' , 1), '1') ?>>
    Yes
</label>
&nbsp;
<label>
    <input type="radio" name="slm_config[content][is_enabled]" value="0" <?php checked(srvlm_get_post_config($post->ID,  'content.is_enabled' , 1), '0') ?>>
    No
</label>