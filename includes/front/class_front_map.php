<?php
/**
 * front end map
 * @author Hidden Brains
 * @version 1.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if ( !class_exists('ClassFrontEndUI') ) {
	
	/**
	 * Front end of the plugin
	 * create UI for the google map front
	 * @since 1.0
	 *
	 * @author  Hidden Brains
	 */
	class ClassFrontEndUI
	{
		
		function __construct()
		{
			if (!is_admin()) :
				add_shortcode( 'custom-map', array( $this, 'custom_map_shortcode' ) );
				add_action( 'wp_enqueue_scripts', array( $this, 'load_map_style_script' ) );
			endif;
		}

		/**
		 * shortcode to add map on front
		 * @date   2016-05-19
		 * @param  array     $args shortcode arguments
		 * @return map
		 */
		public function custom_map_shortcode($args)
		{
			$post_ID = $args['id'];
			$height = get_post_meta( $post_ID, MAP_PREFIX .'height', true );
			$height = $height?$height:'300';
			?><div id="map" style="height: <?php echo $height;?>px"></div><?php
			require_once MAP_PLUGIN_INCLUDES_PATH . '/front/custom-map-js.php';
		}

		/**
		 * load style and scripts for map
		 * @date   2016-05-19
		 * @return null
		 */
		public function load_map_style_script()
		{
			/**
			 * google map style
			 */
			wp_enqueue_style( 
				'google-map',
				MAP_PLUGIN_ASSETS_URL . '/css/maps.css',
				array(),
				'1.0',
				'all' 
			);

			/**
			 * google map script
			 */
			wp_enqueue_script( 
				'google-map',
				'https://maps.googleapis.com/maps/api/js?key=AIzaSyChiFHjE4hxEgIdT1kxqT-S7Cq2F9f3hPE&callback=initMap',
				array(),
				'1.0',
				true 
			);

			/**
			 * load google map
			 */
			wp_enqueue_script( 
				'load-map',
				MAP_PLUGIN_ASSETS_URL . '/js/maps.js',
				array('jquery'),
				'1.0',
				false 
			);

		}

	}// class ends

}// if ends

$frontmap = new ClassFrontEndUI();