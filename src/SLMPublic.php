<?php

namespace TenPixls\SurveyLockMe;

class SLMPublic {

	const SURVEY_ENDPOINT = 'https://d2m3eq6qzl4xib.cloudfront.net/slme.js';

	/**
	 * SLMPublic constructor.
	 */
	public function __construct() {
		$this->_initHooks();
	}

	private function _initHooks() {
		add_action( 'wp_enqueue_scripts', [ $this, 'initAssets' ] );
	}

	private function _isDebugMode() {
		return defined('SRVLM_DEBUG_MODE') ? SRVLM_DEBUG_MODE : 0;
	}

	private function _getPublisherKey() {
		return srvlm_get_option( 'publisher_key' );
	}

	private function _getIsTesting() {
		return intval( srvlm_get_option( 'is_testing', false ) );
	}

	public function initAssets() {
		$prefix  = $this->_isDebugMode() ? '' : 'dist/';
		$postfix = $this->_isDebugMode() ? '' : '.min';

		wp_enqueue_script( 'slm-main-public', plugin_dir_url( srvlm_get_plugin_basename() ) . "assets/js/{$prefix}main{$postfix}.js", [ 'jquery' ], SRVLM_PLUGIN_VERSION, true );
		wp_localize_script( 'slm-main-public',
			'slmConfig',
			[
				'endpoint'    => self::SURVEY_ENDPOINT,
				'publisher'   => $this->_getPublisherKey(),
				'isDebugMode' => $this->_isDebugMode(),
				'testing'     => $this->_getIsTesting(),
			] );

		wp_enqueue_style( 'slm-main-public', plugin_dir_url( srvlm_get_plugin_basename() ) . "assets/css/main.css", [], SRVLM_PLUGIN_VERSION );
	}


}