<?php

namespace TenPixls\SurveyLockMe\Providers\Snax;

use TenPixls\SurveyLockMe\Providers\SLMProviderAbstract;

class SLMSnaxProvider extends SLMProviderAbstract {

	public function isEnabled() {
		return $this->_snaxPluginIsActive();
	}

	private function _snaxPluginIsActive() {
		return function_exists( 'snax_get_plugin_basename' ) && is_plugin_active( snax_get_plugin_basename() );
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return array|mixed|null
	 */
	private function _isOptionEnabled( $post = null ) {
		$commonConfigValue = srvlm_get_option( 'snax.is_option_enabled', true );

		return $post
			? srvlm_get_post_config( $post->ID, 'snax.is_enabled', $commonConfigValue )
			: $commonConfigValue;
	}

	protected function _getLoaderText() {
		return srvlm_get_option( 'snax.is_custom_loader_text', 0 )
			? srvlm_get_option( 'snax.loader_text' )
			: parent::_getLoaderText();
	}

	protected function _getLoaderColor() {
		return srvlm_get_option( 'snax.is_custom_loader_color', 0 )
			? srvlm_get_option( 'snax.loader_color' )
			: parent::_getLoaderColor();
	}

	protected function _getCTAText() {
		return srvlm_get_option( 'snax.is_custom_cta_text', 0 )
			? srvlm_get_option( 'snax.cta_text' )
			: parent::_getCTAText();
	}

	protected function _getCTAButtonText() {
		return srvlm_get_option( 'snax.is_custom_cta_button_text', 0 )
			? srvlm_get_option( 'snax.cta_button_text' )
			: parent::_getCTAButtonText();
	}

	protected function _getCTAButtonColor() {
		return srvlm_get_option( 'snax.is_custom_cta_button_color', 0 )
			? srvlm_get_option( 'snax.cta_button_color' )
			: parent::_getCTAButtonColor();
	}

	protected function _getBrand() {
		return srvlm_get_option( 'snax.is_custom_brand', 0 )
			? srvlm_get_option( 'snax.brand' )
			: parent::_getBrand();
	}

	protected function _getExplainer() {
		return srvlm_get_option( 'snax.is_custom_explainer', 0 )
			? srvlm_get_option( 'snax.explainer' )
			: parent::_getExplainer();
	}

	public function init() {
		$this->_initHooks();
	}

	private function _initHooks() {
		if ( is_admin() ) {
			add_action( 'slm_admin_sections', [ $this, 'initAdminCommonSettings' ] );
			add_action( 'add_meta_boxes', [ $this, 'initAdminMetaBox' ] );
		} else {
			add_action( 'wp_enqueue_scripts', [ $this, 'initFrontendAssets' ] );
			add_action( 'wp_footer', [ $this, 'renderHtml' ] );
		}
	}

	public function initFrontendAssets() {
		if ( get_post_type() == 'snax_quiz' && $this->_isOptionEnabled( get_post() ) ) {
			$prefix  = $this->_isDebugMode() ? '' : 'dist/';
			$postfix = $this->_isDebugMode() ? '' : '.min';

			wp_enqueue_script( 'slm-snax-public', plugin_dir_url( srvlm_get_plugin_basename() ) . "assets/js/{$prefix}snax{$postfix}.js", [ 'jquery', 'slm-main-public' ], SRVLM_PLUGIN_VERSION, true );
			wp_localize_script( 'slm-snax-public',
				'slmSnaxConfig',
				[
					'brand'     => $this->_getBrand(),
					'explainer' => $this->_getExplainer(),
				] );

			wp_enqueue_style( 'slm-snax-public', plugin_dir_url( srvlm_get_plugin_basename() ) . "assets/css/snax.css", [], SRVLM_PLUGIN_VERSION );
		}
	}

	public function renderHtml() {
		if ( get_post_type() == 'snax_quiz' ) {
			srvlm_render_partial( 'loader', 'public', [ 'loader' => $this->_getLoader(), 'text' => $this->_getLoaderText(), 'color' => $this->_getLoaderColor(), 'provider' => 'snax' ] );
			srvlm_render_partial( 'cta', 'public', [ 'buttonText' => $this->_getCTAButtonText(), 'text' => $this->_getCTAText(), 'color' => $this->_getCTAButtonColor(), 'provider' => 'snax' ] );
		}
	}

	public function initAdminCommonSettings() {
		add_settings_section(
			'slm_section_snax',
			'Settings for Snax Plugin Integration',
			'__return_empty_string',
			'survey-lock-me'
		);

		add_settings_field(
			'is_option_enabled',
			'Enable Snax Plugin Integration',
			[ $this, 'enabledSetting' ],
			'survey-lock-me',
			'slm_section_snax'
		);

		add_settings_field(
			'snax_brand',
			'Survey Brand',
			[ $this, 'brandSetting' ],
			'survey-lock-me',
			'slm_section_snax'
		);

		add_settings_field(
			'snax_explainer',
			'Survey Explainer',
			[ $this, 'explainerSetting' ],
			'survey-lock-me',
			'slm_section_snax'
		);

		add_settings_field(
			'snax_loader_text',
			'Loader Text',
			[ $this, 'loaderTextSetting' ],
			'survey-lock-me',
			'slm_section_snax'
		);
		
		add_settings_field(
			'loader_color',
			'Loader Color',
			[ $this, 'loaderColorSetting' ],
			'survey-lock-me',
			'slm_section_snax'
		);

		add_settings_field(
			'cta_text',
			'Call To Action Title',
			[ $this, 'ctaTextSetting' ],
			'survey-lock-me',
			'slm_section_snax'
		);

		add_settings_field(
			'cta_button_text',
			'Call To Action Button Label',
			[ $this, 'ctaButtonTextSetting' ],
			'survey-lock-me',
			'slm_section_snax'
		);
		
		add_settings_field(
			'cta_button_color',
			'Call To Action Button Color',
			[ $this, 'ctaButtonColorSetting' ],
			'survey-lock-me',
			'slm_section_snax'
		);
	}

	public function brandSetting() {
		srvlm_render_partial( 'sections/snax/brand', 'admin' );
	}

	public function explainerSetting() {
		srvlm_render_partial( 'sections/snax/explainer', 'admin' );
	}

	public function loaderTextSetting() {
		srvlm_render_partial( 'sections/snax/loader_text', 'admin' );
	}
	
	public function loaderColorSetting() {
		srvlm_render_partial( 'sections/snax/loader_color', 'admin' );
	}

	public function enabledSetting() {
		srvlm_render_partial( 'sections/snax/is_enabled', 'admin' );
	}

	public function ctaTextSetting() {
		srvlm_render_partial( 'sections/snax/cta_text', 'admin' );
	}

	public function ctaButtonTextSetting() {
		srvlm_render_partial( 'sections/snax/cta_button_text', 'admin' );
	}
	
	public function ctaButtonColorSetting() {
		srvlm_render_partial( 'sections/snax/cta_button_color', 'admin' );
	}

	public function initAdminMetaBox() {
		add_meta_box(
			'slm_quiz_config',
			SRVLM_PLUGIN_NAME . ': Snax Addon',
			[ $this, 'renderAdminMetaBox' ],
			'snax_quiz'
		);
	}

	public function renderAdminMetaBox( $post ) {
		srvlm_render_partial( 'meta_boxes/snax', 'admin', compact( 'post' ) );
	}
}