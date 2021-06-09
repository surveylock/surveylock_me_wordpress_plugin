<?php
/**
 * Plugin Name: SurveyLock.me
 * Description: Monetize WordPress content by locking it behind a short visitor survey.
 * Author: SurveyLock.me
 * Author URI: https://surveylock.me
 * Version: 1.0.3
 * Text Domain: slm
 * Domain Path: languages
 * Network:	false
 */

// Prevent direct script access.
if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct script access allowed' );
}

if(!defined('SRVLM_PLUGIN_VERSION')) {
	define('SRVLM_PLUGIN_VERSION', '1.0.3');
}
if(!defined('SRVLM_PLUGIN_NAME')) {
	define('SRVLM_PLUGIN_NAME', 'SurveyLock.me');
}
if(!defined('SRVLM_PLUGIN_FILE')) {
	define('SRVLM_PLUGIN_FILE', __FILE__);
}
if(!defined('SRVLM_PLUGIN_DIR')) {
	define('SRVLM_PLUGIN_DIR', __DIR__);
}
if(!defined('SRVLM_DEBUG_MODE')) {
	define('SRVLM_DEBUG_MODE', 0);
}

include 'vendor/autoload.php';

add_action('plugins_loaded', function () {
    new TenPixls\SurveyLockMe\SLMCore();
});

function srvlm_activate()
{
    TenPixls\SurveyLockMe\SLMCore::install();
}

register_activation_hook(__FILE__, 'srvlm_activate' );