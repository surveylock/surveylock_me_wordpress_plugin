<?php

namespace TenPixls\SurveyLockMe;

use TenPixls\SurveyLockMe\Providers\SLMProviderAbstract;

class SLMCore {

	public static $metaSanitizers = [
		'snax'    => [
			'is_enabled' => 'srvlm_sanitize_bool',
		],
		'content' => [
			'is_enabled' => 'srvlm_sanitize_bool',
		],
	];

	/**
	 * Core constructor.
	 * @throws \Exception
	 */
	public function __construct() {
		$this->_initProviders();
		$this->_initPublic();
		$this->_initAdmin();
	}

	private function _initAdmin() {
		if ( is_admin() ) {
			new SLMAdmin();
		}
	}

	private function _initPublic() {
		if ( ! is_admin() ) {
			new SLMPublic();
		}
	}

	/**
	 * @throws \Exception
	 */
	private function _initProviders() {
		$providers = apply_filters( 'slm_providers_list', srvlm_get_providers() );

		foreach ( $providers as $provider ) {
			if ( is_a( $provider, SLMProviderAbstract::class, true ) ) {
				$instance = new $provider();

				if ( $instance->isEnabled() ) {
					$instance->init();
				}
			} else {
				throw new \Exception( SRVLM_PLUGIN_NAME . ' Provider should be an instance of SLMProviderAbstract class' );
			}
		}
	}

	public static function getMetaSanitizer( $key ) {
		return srvlm_array_get( self::$metaSanitizers, $key, 'sanitize_text_field' );
	}

	public static function install() {
		self::_setDefaultOptions();
	}

	private static function _setDefaultOptions() {
		foreach ( self::getDefaultOptions() as $key => $value ) {
			self::_setDefaultOption( $key, $value );
		}
	}

	private static function _setDefaultOption( $key, $value ) {
		if ( is_array( $value ) ) {
			foreach ( $value as $_key => $_value ) {
				self::_setDefaultOption( $key . '.' . $_key, $_value );
			}
		} elseif ( srvlm_get_option( $key ) === null ) {
			srvlm_update_option( $key, $value );
		}
	}

	public static function getDefaultOptions() {
		return [
			'publisher_key'    => '91ca51ed-6e3e-4acd-ad58-91d643f5a3b8',
			'brand'            => 'Almost Done. Answer these questions to reveal your content!',
			'explainer'        => 'This short anonymous opinion survey helps keep this website free!',
			'is_testing'       => '0',
			'loader'           => 'bars_loader',
			'loader_text'      => 'Loading your content...',
			'loader_color'     => srvlm_default_color(),
			'cta_text'         => 'This content is hidden',
			'cta_button_text'  => 'Click to reveal',
			'cta_button_color' => srvlm_default_color( '#5cb85c' ),
			'snax'             => [
				'is_option_enabled'         => '1',
				'is_custom_brand'           => '1',
				'brand'                     => 'Almost Done. Answer these questions to reveal your result!',
				'is_custom_loader_text'     => '1',
				'loader_text'               => 'Calculating Result...',
				'is_custom_cta_text'        => '1',
				'cta_text'                  => 'Click to Get Your Result',
				'is_custom_cta_button_text' => '1',
				'cta_button_text'           => 'Get Result!',
			],
			'content'          => [
				'is_option_enabled' => '1',
				'is_in_popup'       => '0',
				'info_box_text'     => 'Please fill out our quick, anonymous brand survey to gain access to this hidden content. No personal or private information required!',
			],
		];
	}
}