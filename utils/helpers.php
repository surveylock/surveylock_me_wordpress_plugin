<?php

function srvlm_array_get( $array, $key = null, $default = null ) {
	if ( ! is_array( $array ) ) {
		return $default;
	}

	if ( $key === null ) {
		return $array;
	}

	$keys  = explode( '.', $key );
	$value = $array;

	foreach ( $keys as $currKey ) {
		if ( ! is_array( $value ) || ! array_key_exists( $currKey, $value ) ) {
			return $default;
		}

		$value = $value[ $currKey ];
	}

	return $value;
}

function srvlm_get_option( $key = null, $default = null ) {
	return srvlm_array_get( get_option( 'slm_options' ), $key, $default );
}

function srvlm_update_option( $key, $value ) {
	$options = srvlm_get_option();

	if ( ! isset( $options ) || ! is_array( $options ) ) {
		$options = [];
	}

	update_option( 'slm_options', srvlm_array_set( $options, $key, $value ) );
}

function srvlm_array_set( $array, $key, $value ) {
	srvlm_array_modify( $array, $key, $value );

	return $array;
}

function srvlm_array_modify( &$array, $key, $value ) {
	if ( is_null( $key ) ) {
		return $array = $value;
	}
	$keys = explode( '.', $key );
	while ( count( $keys ) > 1 ) {
		$key = array_shift( $keys );
		if ( ! isset( $array[ $key ] ) || ! is_array( $array[ $key ] ) ) {
			$array[ $key ] = [];
		}
		$array =& $array[ $key ];
	}
	$array[ array_shift( $keys ) ] = $value;
}

/**
 * @param integer $postId
 */
function srvlm_get_post_config( $postId, $key = null, $default = null ) {
	$meta = get_post_meta( $postId, 'slm_config', true );

	return srvlm_array_get( $meta, $key, $default );
}

function srvlm_update_post_config( $postId, $key, $value ) {
	$value = srvlm_sanitize_post_config( $key, $value );
	update_post_meta( $postId, 'slm_config', srvlm_array_set( srvlm_get_post_config( $postId ), $key, $value ) );
}

function srvlm_sanitize_post_config( $key, $option ) {
	$values = $option;

	if ( is_array( $values ) ) {
		foreach ( $values as $k => $value ) {
			$option[ $k ] = srvlm_sanitize_post_config( $key . '.' . $k, $value );
		}
	} else {
		$sanitizer = TenPixls\SurveyLockMe\SLMCore::getMetaSanitizer( $key );

		if ( is_callable( $sanitizer ) ) {
			$option = call_user_func( $sanitizer, $values );
		} else {
			$option = sanitize_text_field( $values );
		}
	}

	return $option;
}

/**
 * Return the plugin basename
 *
 * @return string
 */
function srvlm_get_plugin_basename() {
	return plugin_basename( SRVLM_PLUGIN_FILE );
}


function srvlm_admin_url( $path = '', $scheme = 'admin' ) {
	// Links belong to network admin.
	if ( is_network_admin() ) {
		$url = network_admin_url( $path, $scheme );

		// Links belong to site admin.
	} else {
		$url = admin_url( $path, $scheme );
	}

	return $url;
}

function srvlm_render_partial( $view, $domain = 'public', $variables = [], $return = false ) {
	$result = TenPixls\SurveyLockMe\SLMView::getPartial( $view, $domain, $variables );

	if ( $return ) {
		return $result;
	} else {
		echo $result;
	}
}

function srvlm_default_color( $default = '#999999' ) {
	return srvlm_array_get( get_option( 'bimber_theme' ), 'content_cs_2_background_color', $default );
}

function srvlm_sanitize_bool( $value ) {
	return strval( intval( $value ) );
}