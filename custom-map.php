<?php
/*
Plugin Name: Custom Google Map
Description: Simplest and Easiest way to display Google Maps in Website Pages & its Blog Posts in seconds.
Author: Hidden Brains
Author URI: http://hiddenbrains.com
Version: 1.0
License: GPL2
Text Domain: custom-map
*/

/*

    Copyright (C) 2016  Hidden Brains

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/


/**
 * Define constants
 */
define( 'MAP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'MAP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
define( 'MAP_PLUGIN_INCLUDES_URL', plugin_dir_url( __FILE__ ) . 'includes' );
define( 'MAP_PLUGIN_INCLUDES_PATH', plugin_dir_path( __FILE__ ) . 'includes' );
define( 'MAP_PLUGIN_ASSETS_URL', plugin_dir_url( __FILE__ ) . 'assets' );
define( 'MAP_PLUGIN_ASSETS_PATH', plugin_dir_path( __FILE__ ) . 'assets' );
define( 'MAP_PREFIX', 'mapitem_');
define( 'MAP_POST_TYPE', 'custom-maps');


require_once MAP_PLUGIN_PATH . 'includes/core/class_custom_map.php';
require_once MAP_PLUGIN_PATH . 'includes/core/metaboxes/meta_box.php';
require_once MAP_PLUGIN_PATH . 'includes/front/class_front_map.php';