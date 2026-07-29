<?php
/**
 * MM Comment Manager core functions.
 *
 * @package MM Comment Manager
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Block direct comment submissions.
 *
 * @return void
 */
function turn_off_comments_no_wp_comments() {
	wp_die(
		esc_html__( 'Comments are turned off on this website.', 'turn-off-comments' ),
		esc_html__( 'Comments Disabled', 'turn-off-comments' ),
		array( 'response' => 403 )
	);
}

/**
 * Remove the Comments item from the admin menu.
 *
 * @return void
 */
function turn_off_comments_admin_menu() {
	remove_menu_page( 'edit-comments.php' );
}

/**
 * Remove the "Recent Comments" dashboard widget.
 *
 * @return void
 */
function turn_off_comments_dashboard() {
	remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
}

/**
 * Force comments and pings to be closed.
 *
 * @param bool $open Whether the item is currently open for comments/pings.
 * @return bool Always false.
 */
function turn_off_comments_status( $open ) {
	return false;
}

/**
 * Hide any existing comments on the front end.
 *
 * @param array $comments Array of comments for the current post.
 * @return array Empty array.
 */
function turn_off_comments_hide_existing_comments( $comments ) {
	return array();
}

/**
 * Remove the Comments node from the admin bar.
 *
 * @return void
 */
function turn_off_comments_admin_bar_render() {
	global $wp_admin_bar;

	if ( is_object( $wp_admin_bar ) ) {
		$wp_admin_bar->remove_node( 'comments' );
	}
}

/**
 * Redirect any request to the comments admin screen back to the dashboard.
 *
 * @return void
 */
function turn_off_comments_admin_menu_redirect() {
	global $pagenow;

	if ( 'edit-comments.php' === $pagenow ) {
		wp_safe_redirect( admin_url() );
		exit;
	}
}

/**
 * Remove comment and trackback support from all registered post types.
 *
 * @return void
 */
function turn_off_comments_post_types_support() {
	$post_types = get_post_types();

	foreach ( $post_types as $post_type ) {
		if ( post_type_supports( $post_type, 'comments' ) ) {
			remove_post_type_support( $post_type, 'comments' );
			remove_post_type_support( $post_type, 'trackbacks' );
		}
	}
}

/**
 * Enqueue inline CSS that hides theme comment markup on the front end.
 *
 * @return void
 */
function turn_off_comments_hide_comment_styles() {
	$css = '#comments,.nocomments,.no-comments,.has-comments,.post-comments,.comments-link,.comments-area,.comment-respond,.comments-closed,.comments-wrapper,.wp-block-comments,.comments-area__wrapper,.wp-block-post-comments,.wp-block-comments-title,.wp-block-comment-template,.wp-block-comments-query-loop,li.meta-comments{display:none !important;}';

	wp_register_style( 'turn-off-comments', false, array(), '1.8.1' );
	wp_enqueue_style( 'turn-off-comments' );
	wp_add_inline_style( 'turn-off-comments', $css );
}

/**
 * Set a transient so the activation notice is shown once.
 *
 * @return void
 */
function turn_off_comments_activation_hook() {
	set_transient( 'turn_off_comments_notification', true, 60 );
}

/**
 * Display a one-time thank-you notice after activation.
 *
 * @return void
 */
function turn_off_comments_activation_notification() {
	if ( get_transient( 'turn_off_comments_notification' ) ) {
		?>
		<div class="notice notice-success is-dismissible">
			<p><?php esc_html_e( 'Thank you for installing Turn Off Comments!', 'turn-off-comments' ); ?></p>
		</div>
		<?php
		delete_transient( 'turn_off_comments_notification' );
	}
}
