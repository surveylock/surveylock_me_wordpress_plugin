<?php

namespace TenPixls\SurveyLockMe\Providers;

use TenPixls\SurveyLockMe\SLMCore;

abstract class SLMProviderAbstract implements SLMProviderInterface {

	protected function _setup() {

	}

	protected function _getDefaultOption( $key ) {
		return srvlm_array_get( SLMCore::getDefaultOptions(), $key );
	}

	protected function _isDebugMode() {
		return defined( 'SRVLM_DEBUG_MODE' ) ? SRVLM_DEBUG_MODE : 0;
	}

	protected function _getLoader() {
		return srvlm_get_option( 'loader', $this->_getDefaultOption( 'loader' ) );
	}

	protected function _getLoaderText() {
		return srvlm_get_option( 'loader_text', $this->_getDefaultOption( 'loader_text' ) );
	}

	protected function _getLoaderColor() {
		return srvlm_get_option( 'loader_color', $this->_getDefaultOption( 'loader_color' ) );
	}

	protected function _getCTAText() {
		return srvlm_get_option( 'cta_text', $this->_getDefaultOption( 'cta_text' ) );
	}

	protected function _getCTAButtonText() {
		return srvlm_get_option( 'cta_button_text', $this->_getDefaultOption( 'cta_button_text' ) );
	}

	protected function _getCTAButtonColor() {
		return srvlm_get_option( 'cta_button_color', $this->_getDefaultOption( 'cta_button_color' ) );
	}

	protected function _getBrand() {
		return srvlm_get_option( 'brand', $this->_getDefaultOption( 'brand' ) );
	}

	protected function _getExplainer() {
		return srvlm_get_option( 'explainer', $this->_getDefaultOption( 'explainer' ) );
	}
}