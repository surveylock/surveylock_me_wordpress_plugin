<?php

namespace TenPixls\SurveyLockMe\Providers\Content;

use TenPixls\SurveyLockMe\Providers\SLMProviderAbstract;
use TenPixls\SurveyLockMe\Providers\SLMProviderInterface;

class SLMContentProvider extends SLMProviderAbstract {
	const SHORTCODE = 'slm_content_lock';

	private static $_showFrontendContent = false;


	public function isEnabled() {
		return true;
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
		add_shortcode( self::SHORTCODE, [ $this, 'initShortcode' ] );
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return array|mixed|null
	 */
	private function _isOptionEnabled( $post = null ) {
		$commonConfigValue = srvlm_get_option( 'content.is_option_enabled', true );

		return $post
			? srvlm_get_post_config( $post->ID, 'content.is_enabled', $commonConfigValue )
			: $commonConfigValue;
	}

	/**
	 * @param \WP_Post $post
	 *
	 * @return array|mixed|null
	 */
	private function _isInPopup( $post = null ) {
		$commonConfigValue = srvlm_get_option( 'content.is_in_popup', true );

		$value = $post
			? srvlm_get_post_config( $post->ID, 'content.is_in_popup', $commonConfigValue )
			: $commonConfigValue;

		return intval( $value );
	}

	protected function _getLoaderText() {
		return srvlm_get_option( 'content.is_custom_loader_text', 0 )
			? srvlm_get_option( 'content.loader_text' )
			: parent::_getLoaderText();
	}

	protected function _getLoaderColor() {
		return srvlm_get_option( 'content.is_custom_loader_color', 0 )
			? srvlm_get_option( 'content.loader_color' )
			: parent::_getLoaderColor();
	}

	protected function _getCTAText() {
		return srvlm_get_option( 'content.is_custom_cta_text', 0 )
			? srvlm_get_option( 'content.cta_text' )
			: parent::_getCTAText();
	}

	protected function _getCTAButtonText() {
		return srvlm_get_option( 'content.is_custom_cta_button_text', 0 )
			? srvlm_get_option( 'content.cta_button_text' )
			: parent::_getCTAButtonText();
	}

	protected function _getCTAButtonColor() {
		return srvlm_get_option( 'content.is_custom_cta_button_color', 0 )
			? srvlm_get_option( 'content.cta_button_color' )
			: parent::_getCTAButtonColor();
	}

	protected function _getBrand() {
		return srvlm_get_option( 'content.is_custom_brand', 0 )
			? srvlm_get_option( 'content.brand' )
			: parent::_getBrand();
	}

	protected function _getExplainer() {
		return srvlm_get_option( 'content.is_custom_explainer', 0 )
			? srvlm_get_option( 'content.explainer' )
			: parent::_getExplainer();
	}

	protected function _getInfoBoxText() {
		return srvlm_get_option( 'content.info_box_text', "Please fill out a quick, anonymous brand survey to gain access to this hidden content.\nNo personal or private information required!" );
	}

	protected function _getMaxHeight() {
		return intval( srvlm_get_option( 'content.max_block_height' ) );
	}

	private function _showFrontendAssets() {
		if ( ! self::$_showFrontendContent ) {
			$post = get_post();
			
			self::$_showFrontendContent = has_shortcode( $post->post_content, self::SHORTCODE ) && $this->_isOptionEnabled( $post );
		}
		
		return self::$_showFrontendContent;
	}

	public function initFrontendAssets() {
		if ( $this->_showFrontendAssets() ) {
			$prefix  = $this->_isDebugMode() ? '' : 'dist/';
			$postfix = $this->_isDebugMode() ? '' : '.min';

			wp_enqueue_script( 'slm-content-public', plugin_dir_url( srvlm_get_plugin_basename() ) . "assets/js/{$prefix}content{$postfix}.js", [ 'jquery', 'slm-main-public' ], SRVLM_PLUGIN_VERSION, true );
			wp_localize_script( 'slm-content-public',
				'slmContentConfig',
				[
					'in_popup'  => $this->_isInPopup(),
					'brand'     => $this->_getBrand(),
					'explainer' => $this->_getExplainer(),
				] );

			wp_enqueue_style( 'slm-content-public', plugin_dir_url( srvlm_get_plugin_basename() ) . "assets/css/content.css", [], SRVLM_PLUGIN_VERSION );
		}
	}

	public function renderHtml() {
		if ( $this->_showFrontendAssets() ) {
			srvlm_render_partial( 'loader', 'public', [ 'loader' => $this->_getLoader(), 'text' => $this->_getLoaderText(), 'color' => $this->_getLoaderColor(), 'provider' => 'content' ] );
			srvlm_render_partial( 'cta', 'public', [ 'buttonText' => $this->_getCTAButtonText(), 'text' => $this->_getCTAText(), 'color' => $this->_getCTAButtonColor(), 'provider' => 'content' ] );
		}
	}

	public function initAdminCommonSettings() {
		add_settings_section(
			'slm_section_content',
			'Settings for Content Lock Integration',
			[ $this, 'adminSectionContentHelper' ],
			'survey-lock-me'
		);

		add_settings_field(
			'is_option_enabled',
			'Enable Content Integration',
			[ $this, 'adminEnabledSetting' ],
			'survey-lock-me',
			'slm_section_content'
		);

		add_settings_field(
			'content_brand',
			'Survey Brand',
			[ $this, 'brandSetting' ],
			'survey-lock-me',
			'slm_section_content'
		);

		add_settings_field(
			'content_explainer',
			'Survey Explainer',
			[ $this, 'explainerSetting' ],
			'survey-lock-me',
			'slm_section_content'
		);

		add_settings_field(
			'content_loader_text',
			'Loader Text',
			[ $this, 'loaderTextSetting' ],
			'survey-lock-me',
			'slm_section_content'
		);

		add_settings_field(
			'loader_color',
			'Loader Color',
			[ $this, 'loaderColorSetting' ],
			'survey-lock-me',
			'slm_section_content'
		);

		add_settings_field(
			'cta_text',
			'Call To Action Title',
			[ $this, 'ctaTextSetting' ],
			'survey-lock-me',
			'slm_section_content'
		);

		add_settings_field(
			'cta_button_text',
			'Call To Action Button Label',
			[ $this, 'ctaButtonTextSetting' ],
			'survey-lock-me',
			'slm_section_content'
		);

		add_settings_field(
			'cta_button_color',
			'Call To Action Button Color',
			[ $this, 'ctaButtonColorSetting' ],
			'survey-lock-me',
			'slm_section_content'
		);

		add_settings_field(
			'info_box_text',
			'Info Box Text',
			[ $this, 'infoBoxTextSetting' ],
			'survey-lock-me',
			'slm_section_content'
		);

		add_settings_field(
			'max_block_height',
			'Maximum Block Height (px)',
			[ $this, 'maxBlockHeightSetting' ],
			'survey-lock-me',
			'slm_section_content'
		);

		add_settings_field(
			'is_in_popup',
			'Show Survey in Pop-up',
			[ $this, 'adminPopupSetting' ],
			'survey-lock-me',
			'slm_section_content'
		);
	}

	public function adminEnabledSetting() {
		srvlm_render_partial( 'sections/content/is_enabled', 'admin' );
	}

	public function brandSetting() {
		srvlm_render_partial( 'sections/content/brand', 'admin' );
	}

	public function explainerSetting() {
		srvlm_render_partial( 'sections/content/explainer', 'admin' );
	}

	public function loaderTextSetting() {
		srvlm_render_partial( 'sections/content/loader_text', 'admin' );
	}

	public function loaderColorSetting() {
		srvlm_render_partial( 'sections/content/loader_color', 'admin' );
	}

	public function adminPopupSetting() {
		srvlm_render_partial( 'sections/content/is_popup', 'admin' );
	}

	public function ctaTextSetting() {
		srvlm_render_partial( 'sections/content/cta_text', 'admin' );
	}

	public function ctaButtonTextSetting() {
		srvlm_render_partial( 'sections/content/cta_button_text', 'admin' );
	}

	public function infoBoxTextSetting() {
		srvlm_render_partial( 'sections/content/info_box_text', 'admin' );
	}

	public function maxBlockHeightSetting() {
		srvlm_render_partial( 'sections/content/max_block_height', 'admin' );
	}

	public function ctaButtonColorSetting() {
		srvlm_render_partial( 'sections/content/cta_button_color', 'admin' );
	}

	public function initAdminMetaBox() {
		if ( get_post_type() != 'snax_quiz' ) {
			add_meta_box(
				'slm_quiz_content_config',
				SRVLM_PLUGIN_NAME . ': Content Lock Addon',
				[ $this, 'renderAdminMetaBox' ]
			);
		}
	}

	public function renderAdminMetaBox( $post ) {
		srvlm_render_partial( 'meta_boxes/content', 'admin', compact( 'post' ) );
	}

	public function initShortcode( $attrs, $content = null ) {
		$infoBoxText = $this->_getInfoBoxText();
		$maxHeight   = $this->_getMaxHeight();

		return srvlm_render_partial( 'shortcode', 'public', compact( 'attrs', 'content', 'infoBoxText', 'maxHeight' ), true );
	}

	public function adminSectionContentHelper() {
		srvlm_render_partial( 'sections/content/helper', 'admin', [ 'shortcode' => self::SHORTCODE ] );
	}
}