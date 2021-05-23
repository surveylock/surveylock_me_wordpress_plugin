<?php

namespace TenPixls\SurveyLockMe;

class SLMView {
	public static function getPartial( $view, $domain = 'public', $variables = [] ) {
		return self::_partial( self::_partialPath( $view, $domain ), $variables );
	}

	private static function _partial( $path, $variables = [] ) {
		extract( $variables );

		ob_start();
		require( $path );

		return ob_get_clean();
	}

	private static function _partialPath( $view, $domain = 'public' ) {
		$path = "/templates/{$domain}/{$view}.php";

		return SRVLM_PLUGIN_DIR . $path;
	}
}