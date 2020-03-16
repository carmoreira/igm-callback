<?php
/**
 * Interactive Geo Maps Callback
 *
 * @wordpress-plugin
 * Plugin Name:       Interactive Geo Maps Callback
 * Plugin URI:        https://interactivegeomaps.com
 * Description:       Adds option to run custom javascript on selected maps
 * Version:           1.0.0
 * Author:            Carlos Moreira
 * Author URI:        https://cmoreira.net/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       igm-callback
 * Domain Path:       /languages
 */

// add pro meta options
add_filter( 'igm_model', 'igm_callback_model' );
add_filter( 'igm_map_after', 'igm_callback_render', 2, 99 );

function igm_callback_model( $model ) {

	$model['meta']['map_info']['sections']['general']['fields']['use_callback'] = [
		'type'    => 'switcher',
		'title'   => __( 'Use Custom JS Callback', 'igm-callback' ),
		'desc'    => __( 'Use custom callback javascript function written in plugin settings.', 'igm-callback' ),
		'default' => false,
	];

	$model['settings']['interactive-maps']['sections']['callback'] = [
		'title'  => __( 'JS Callback', 'igm-callback' ),
		'icon'   => 'fa fa-code',
		'fields' => [
			'igm_callback' => [
				'type'  => 'code_editor',
				'title' => __( 'Custom Javascript', 'igm-callback' ),
				'desc'  => __( 'Use the variables <code>id</code> and <code>data</code> in your code and a <code>return data</code> at the end. This code will be enclosed in a function.', 'igm-callback' ),
			],
		],
	];

	return $model;
}

function igm_callback_render( $content, $id ) {

	$map_info = get_post_meta( $id, 'map_info', true );

	if ( isset( $map_info['use_callback'] ) && $map_info['use_callback'] ) {
			$opts     = get_option( 'interactive-maps' );
			$js       = $opts['igm_callback'];
			$content .= sprintf(
				'<script type="text/javascript">
			function igm_custom_filter_%1$s(data) {
				var id = %1$s;
				%2$s
			}
			</script>',
				$id,
				$js
			);
	}

	return $content;

}



