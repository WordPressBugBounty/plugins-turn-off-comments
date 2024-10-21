<?php

/*
 * Plugin Name:       Turn Off Comments
 * Plugin URI:        https://wordpress.org/plugins/turn-off-comments/
 * Description:       Turn Off Comments from your WordPress Website.
 * Version:           1.6.16
 * Tested Up to:      6.6
 * Requires at least: 4.4
 * Requires PHP:      7.0
 * Author:            Mehraz Morshed
 * Author URI:        https://profiles.wordpress.org/mehrazmorshed/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       turn-off-comments
 * Domain Path:       /languages
 */

/**
 * Turn Off Comments is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * 
 * Turn Off Comments is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

defined( 'ABSPATH' ) or die( 'Access denied.' );

require_once plugin_dir_path( __FILE__ ) . 'functions.php';

add_action( 'init', 'turn_off_comments_load_textdomain' );

add_action( 'pre_comment_on_post', 'turn_off_comments_no_wp_comments' ); 

add_action( 'admin_menu', 'turn_off_comments_admin_menu' );

add_action( 'admin_init', 'turn_off_comments_dashboard' ); 

add_filter( 'comments_open', 'turn_off_comments_status', 20, 2 ); 

add_filter( 'pings_open', 'turn_off_comments_status', 20, 2 );

add_filter( 'comments_array', 'turn_off_comments_hide_existing_comments', 10, 2 ); 

add_action('wp_before_admin_bar_render', 'turn_off_comments_admin_bar_render');  

add_action('admin_init', 'turn_off_comments_admin_menu_redirect'); 

add_action( 'admin_init', 'turn_off_comments_post_types_support' ); 

add_action( 'wp_head', 'disable_comment_theme_support' );

register_activation_hook( __FILE__, 'turn_off_comments_activation_hook' );

add_action( 'admin_notices', 'turn_off_comments_activation_notification' );

