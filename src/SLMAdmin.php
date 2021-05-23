<?php

namespace TenPixls\SurveyLockMe;

class SLMAdmin {

	/**
	 * SLMAdmin constructor.
	 */
	public function __construct() {
		$this->_setHooks();
	}

	private function _setHooks() {
		add_action( 'admin_menu', [ $this, 'registerSettings' ] );
		add_filter( 'plugin_action_links', [ $this, 'addPluginSettingsLink' ], 10, 2 );
		add_action( 'admin_init', [ $this, 'initSettings' ] );
		add_action( 'save_post', [ $this, 'updateAdminMetaValues' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'initAssets' ] );
	}

	public function initSettings() {
		register_setting( 'survey-lock-me', 'slm_options' );

		$this->_initCommonSection();

		do_action( 'slm_admin_sections' );
	}

	private function _initCommonSection() {
		add_settings_section(
			'slm_section_common',
			'Common Settings',
			'__return_empty_string',
			'survey-lock-me'
		);

		add_settings_field(
			'publisher_key',
			'Publisher Key',
			[ $this, 'sectionCommonPublisherKey' ],
			'survey-lock-me',
			'slm_section_common'
		);

		add_settings_field(
			'brand',
			'Survey Brand',
			[ $this, 'sectionCommonBrand' ],
			'survey-lock-me',
			'slm_section_common'
		);

		add_settings_field(
			'explainer',
			'Survey Explainer',
			[ $this, 'sectionCommonExplainer' ],
			'survey-lock-me',
			'slm_section_common'
		);

		add_settings_field(
			'loader_text',
			'Loader Text',
			[ $this, 'sectionCommonLoaderText' ],
			'survey-lock-me',
			'slm_section_common'
		);

		add_settings_field(
			'loader_color',
			'Loader Color',
			[ $this, 'commonLoaderColorSetting' ],
			'survey-lock-me',
			'slm_section_common'
		);

		add_settings_field(
			'cta_text',
			'Call To Action Title',
			[ $this, 'commonCTATextSetting' ],
			'survey-lock-me',
			'slm_section_common'
		);

		add_settings_field(
			'cta_button_text',
			'Call To Action Button Label',
			[ $this, 'commonCTAButtonTextSetting' ],
			'survey-lock-me',
			'slm_section_common'
		);

		add_settings_field(
			'cta_button_color',
			'Call To Action Button Color',
			[ $this, 'commonCTAButtonColorSetting' ],
			'survey-lock-me',
			'slm_section_common'
		);

		add_settings_field(
			'is_testing',
			'Enable Testing Mode',
			[ $this, 'sectionCommonTestingMode' ],
			'survey-lock-me',
			'slm_section_common'
		);
	}

	public function registerSettings() {
		$icon = file_get_contents( SRVLM_PLUGIN_DIR . '/assets/img/lock_s.svg' );

		add_menu_page(
			SRVLM_PLUGIN_NAME . ' Settings',
			SRVLM_PLUGIN_NAME,
			'manage_options',
			'survey-lock-me',
			[ $this, 'renderSettingsPage' ],
			'data:image/svg+xml;base64,' . base64_encode( $icon )
		);
	}

	public function addPluginSettingsLink( $links, $file ) {
		$basename = srvlm_get_plugin_basename();

		if ( is_plugin_active( $basename ) && $basename === $file ) {

			$links[] = '<a href="' . esc_url( srvlm_admin_url( add_query_arg( [ 'page' => 'survey-lock-me' ], 'admin.php' ) ) ) . '">' . esc_html__( 'Settings', 'slm' ) . '</a>';
		}

		return $links;
	}

	public function renderSettingsPage() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error( 'survey-lock-me_messages', 'survey-lock-me_message', 'Settings Saved', 'updated' );
		}

		srvlm_render_partial( 'settings', 'admin' );
	}

	public function sectionCommonPublisherKey() {
		srvlm_render_partial( 'sections/common/publisher_key', 'admin' );
	}

	public function sectionCommonBrand() {
		srvlm_render_partial( 'sections/common/brand', 'admin' );
	}

	public function sectionCommonExplainer() {
		srvlm_render_partial( 'sections/common/explainer', 'admin' );
	}

	public function sectionCommonLoaderText() {
		srvlm_render_partial( 'sections/common/loader_text', 'admin' );
	}

	public function commonLoaderColorSetting() {
		srvlm_render_partial( 'sections/common/loader_color', 'admin' );
	}

	public function sectionCommonTestingMode() {
		srvlm_render_partial( 'sections/common/testing_mode', 'admin' );
	}

	public function commonCTATextSetting() {
		srvlm_render_partial( 'sections/common/cta_text', 'admin' );
	}

	public function commonCTAButtonTextSetting() {
		srvlm_render_partial( 'sections/common/cta_button_text', 'admin' );
	}

	public function commonCTAButtonColorSetting() {
		srvlm_render_partial( 'sections/common/cta_button_color', 'admin' );
	}

	public function updateAdminMetaValues( $postId ) {
		foreach ( (array) srvlm_array_get( $_POST, 'slm_config', [] ) as $key => $value ) {
			srvlm_update_post_config( $postId, $key, $value );
		}
	}

	private function _isDebugMode() {
		return defined( 'SRVLM_DEBUG_MODE' ) ? SRVLM_DEBUG_MODE : 0;
	}

	public function initAssets() {
		$prefix  = $this->_isDebugMode() ? '' : 'dist/';
		$postfix = $this->_isDebugMode() ? '' : '.min';

		wp_enqueue_script( 'slm-main-admin', plugin_dir_url( srvlm_get_plugin_basename() ) . "assets/js/{$prefix}admin/main{$postfix}.js", [ 'jquery', 'wp-color-picker' ], SRVLM_PLUGIN_VERSION, true );


		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_style( 'slm-main-admin', plugin_dir_url( srvlm_get_plugin_basename() ) . "assets/css/admin/main.css", [], SRVLM_PLUGIN_VERSION );
	}
}