<?php settings_errors( 'survey-lock-me_messages' ); ?>
<div class="wrap slm-admin-wrap">
    <h1 class="slm-admin-title"><?php echo esc_html( get_admin_page_title() ); ?></h1>
    <div class="slm-admin-welcome">
        Welcome! Please visit our <a href="https://surveylock.me/plugin-setup" target="_blank">publisher setup guide</a> for detailed information on using this plugin.
    </div>
    <form action="options.php" method="post">
		<?php settings_fields( 'survey-lock-me' ); ?>
		<?php do_settings_sections( 'survey-lock-me' ); ?>
		<?php submit_button( 'Save Settings' );; ?>
        <div class="slm-admin-help">
            <a href="https://surveylock.me" class="slm-admin-full-logo">
                <img src="<?php echo plugin_dir_url( srvlm_get_plugin_basename() ); ?>assets/img/logos.svg" alt="">
            </a>
            Questions? Please contact <a href="mailto:publishers@surveylock.me">publishers@surveylock.me</a>
        </div>
    </form>
</div>