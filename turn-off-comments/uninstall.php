<?php
/**
 * MM Comment Manager uninstall script.
 *
 * Fired when the plugin is deleted from the WordPress admin. Removes any
 * data the plugin may have stored so that nothing is left behind.
 *
 * @package MM Comment Manager
 */

// Exit if this file is not being called during an uninstall.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Remove the one-time activation notice transient.
delete_transient( 'turn_off_comments_notification' );

// Remove legacy data from earlier versions, if present.
delete_transient( 'turn-off-comments-notification' );
delete_option( 'turn_off_comments_installed' );
