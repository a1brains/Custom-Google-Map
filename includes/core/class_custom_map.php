<?php
/**
 * front end map
 * @author Hidden Brains
 * @version 1.0
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

if( !class_exists( 'ClassCustomMap' ) ){

	/**
	 * Automatic plugin installation and activation library.
	 * create UI for the google map admin
	 * @since 1.0
	 *
	 * @author  Hidden Brains
	 */
	class ClassCustomMap
	{
		
		function __construct()
		{
			if (is_admin()) :
				add_action( 'plugins_loaded', array( $this, 'map_load_textdomain' ) );
				add_action( 'admin_enqueue_scripts', array( $this, 'load_wp_media_files' ) );
				/* Hook into the 'init' action so that the function
				* Containing our post type registration is not
				* unnecessarily executed.
				*/
				add_action( 'init', array( $this, 'custom_post_type' ), 0 );
				add_action( 'crete_map_meta_box', array( $this, 'add_metaox_map' ), 0 );
				add_filter('manage_posts_columns', array( $this, 'map_columns_head' ) );
				add_action('manage_posts_custom_column', array( $this, 'map_columns_content' ), 10, 2);
				add_filter( 'post_row_actions', array( $this, 'remove_row_actions' ), 10, 1 );
				add_action( 'admin_head', array( $this, 'customize_publish_box' ) );
			endif;
		}

		/**
		 * Load plugin textdomain.
		 * @since 1.0.0
		 */
		function map_load_textdomain() {
		  load_plugin_textdomain('custom-map', false, basename( dirname( __FILE__ ) ) . '/languages' ); 
		}

		/**
		 * Load media files needed for Uploader
		 */
		function load_wp_media_files() {
		  wp_enqueue_media();
		}

		// ADD NEW COLUMN
		function map_columns_head($defaults) {
			if( !is_admin() )
	            return;

			unset($defaults['date']);
		    $defaults['shortcode'] = 'Shortcode';
		    return $defaults;
		}
		 
		// SHOW THE FEATURED IMAGE
		function map_columns_content($column_name, $post_ID) {
			if( !is_admin() )
	            return;

		    switch ($column_name) {
		    	case 'shortcode':
		    		echo '[custom-map id=' . $post_ID . ']';
		    		break;

		    	default:
		    		break;
		    }
		}

		/**
		 * remove view link from admin
		 * @date   2016-05-19
		 * @param  array     $actions
		 * @return array
		 */
		function remove_row_actions( $actions )
		{	
			if( !is_admin() )
	            return;

		    if( get_post_type() === MAP_POST_TYPE )
		        unset( $actions['view'] );
		    	unset( $actions['inline hide-if-no-js'] );
		    return $actions;
		}

		function customize_publish_box() {
	        if( !is_admin() )
	            return;

	        $style = '';
	        $style .= '<style type="text/css">';
	        $style .= '#edit-slug-box, #minor-publishing-actions, #visibility, .num-revisions, .curtime';
	        $style .= '{display: none; }';
	        $style .= '</style>';

	        echo $style;
	    }

		/*
		* Creating a function to create our maps post
		*/

		function custom_post_type() {

			// Set UI labels for Custom Post Type
			$labels = array(
				'name'                => _x( 'Maps', 'Your Maps', 'custom-map' ),
				'singular_name'       => _x( 'Map', 'Your Map', 'custom-map' ),
				'menu_name'           => __( 'Maps', 'custom-map' ),
				'parent_item_colon'   => __( 'Parent Map', 'custom-map' ),
				'all_items'           => __( 'All Maps', 'custom-map' ),
				'view_item'           => __( 'View Map', 'custom-map' ),
				'add_new_item'        => __( 'Add New Map', 'custom-map' ),
				'add_new'             => __( 'Add New', 'custom-map' ),
				'edit_item'           => __( 'Edit Map', 'custom-map' ),
				'update_item'         => __( 'Update Map', 'custom-map' ),
				'search_items'        => __( 'Search Map', 'custom-map' ),
				'not_found'           => __( 'Not Found', 'custom-map' ),
				'not_found_in_trash'  => __( 'Not found in Trash', 'custom-map' ),
			);
			
			// Set other options for Custom Post Type
			
			$args = array(
				'label'               => __( 'maps', 'custom-map' ),
				'labels'              => $labels,
				// Features this CPT supports in Post Editor
				'supports'            => array( 'title' ),
				// You can associate this CPT with a taxonomy or custom taxonomy. 
				'taxonomies'          => array( 'genres' ),
				/* A hierarchical CPT is like Pages and can have
				* Parent and child items. A non-hierarchical CPT
				* is like Posts.
				*/	
				'hierarchical'        => false,
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_nav_menus'   => true,
				'show_in_admin_bar'   => true,
				'menu_position'       => 5,
				'can_export'          => true,
				'has_archive'         => true,
				'exclude_from_search' => false,
				'publicly_queryable'  => true,
				'capability_type'     => 'page',
				'menu_icon'           => 'dashicons-pressthis',
				'rewrite' => false,
			);
			
			// Registering your Custom Post Type
			register_post_type( MAP_POST_TYPE, $args );
			do_action( 'crete_map_meta_box' );
		}

		public function add_metaox_map()
		{	
			if( !is_admin() )
	            return;

			$fields = array(
			  array( // Repeatable & Sortable Text inputs
			    'label' => 'Address', // <label>
			    'desc'  => 'map addresses', // description
			    'id'  => MAP_PREFIX . 'multi_address', // field id and name
			    'type'  => 'repeatable', // type of field
			    'sanitizer' => array( // array of sanitizers with matching kets to next array
			      'address' => 'sanitize_text_field',
			      'latitude' => 'sanitize_text_field',
			      'longitude' => 'sanitize_text_field',
			    ),
			    'repeatable_fields' => array ( // array of fields to be repeated
			      'address' => array(
			        'label' => 'Address',
			        'id' => MAP_PREFIX . 'address',
			        'type' => 'text'
			      ),
			      'latitude' => array(
			        'label' => 'Latitude',
			        'id' => MAP_PREFIX . 'latitude',
			        'type' => 'text'
			      ),
			      'longitude' => array(
			        'label' => 'Longitude',
			        'id' => MAP_PREFIX . 'longitude',
			        'type' => 'text'
			      ),
			    )
			  ),
			  array( // Text inputs
			    'label' => 'Map Center Latitude', // <label>
			    'desc'  => 'enter latitude', // description
			    'id'  => MAP_PREFIX .'center_latitude', // field id and name
			    'type'  => 'text', // type of field
			  ),
			  array( // Text inputs
			    'label' => 'Map Center Longitude', // <label>
			    'desc'  => 'enter longitude', // description
			    'id'  => MAP_PREFIX .'center_longitude', // field id and name
			    'type'  => 'text', // type of field
			  ),
			  array( // Text inputs
			    'label' => 'Map Marker', // <label>
			    'desc'  => 'upload marker icon', // description
			    'id'  => MAP_PREFIX .'marker', // field id and name
			    'type'  => 'image', // type of field
			  ),
			  array( // Text inputs
			    'label' => 'Map Height', // <label>
			    'desc'  => 'default: 300px', // description
			    'id'  => MAP_PREFIX .'height', // field id and name
			    'type'  => 'number', // type of field
			  ),
			  array( // Text inputs
			    'label' => 'Map Zoom Level', // <label>
			    'desc'  => 'default: 10', // description
			    'id'  => MAP_PREFIX .'zoom_level', // field id and name
			    'type'  => 'number', // type of field
			  ),
			  array (
			    'label' => 'Draggable',
			    'desc'  => 'enable/disable draggable (default: Enable)',
			    'id'    => MAP_PREFIX . 'draggable',
			    'type'  => 'radio',
			    'options' => array (
			        'one' => array (
			            'label' => 'Enable',
			            'value' => 1
			        ),
			        'two' => array (
			            'label' => 'Disable',
			            'value' => 0
			        ),
			    )
			  ),
			  array (
			    'label' => 'Scroll Wheel',
			    'desc'  => 'enable/disable scrollwheel (default: Enable)',
			    'id'    => MAP_PREFIX . 'scrollwheel',
			    'type'  => 'radio',
			    'options' => array (
			        'one' => array (
			            'label' => 'Enable',
			            'value' => 1
			        ),
			        'two' => array (
			            'label' => 'Disable',
			            'value' => 0
			        ),
			    )
			  ),
			  array (
			    'label' => 'Zoom Control',
			    'desc'  => 'enable/disable zoom control (default: Enable)',
			    'id'    => MAP_PREFIX . 'zoomcontrol',
			    'type'  => 'radio',
			    'options' => array (
			        'one' => array (
			            'label' => 'Enable',
			            'value' => 1
			        ),
			        'two' => array (
			            'label' => 'Disable',
			            'value' => 0
			        ),
			    )
			  ),
			  array (
			    'label' => 'Disable Double Click Zoom',
			    'desc'  => 'enable/disable double click zoom (default: No)',
			    'id'    => MAP_PREFIX . 'disabledoubleclickzoom',
			    'type'  => 'radio',
			    'options' => array (
			        'one' => array (
			            'label' => 'Yes',
			            'value' => 1
			        ),
			        'two' => array (
			            'label' => 'No',
			            'value' => 0
			        ),
			    )
			  ),
			);

			/**
			 * Instantiate the class with all variables to create a meta box
			 * var $id string meta box id
			 * var $title string title
			 * var $fields array fields
			 * var $page string|array post type to add meta box to
			 * var $js bool including javascript or not
			 */
			$sample_box = new custom_add_meta_box( 'map_field_values', 'Map Fields', $fields, MAP_POST_TYPE, true );	
		}
	
	}//class ends

}// if ends

$map = new ClassCustomMap();