<?php

function turn_off_comments_load_textdomain() {

    load_plugin_textdomain( 'turn-off-comments', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}

function turn_off_comments_no_wp_comments() {

    wp_die( 'No comments' );
}

function turn_off_comments_admin_menu() {

    remove_menu_page( 'edit-comments.php' );
}

function turn_off_comments_dashboard() {

    remove_meta_box( 'dashboard_recent_comments', 'dashboard', 'normal' );
}

function turn_off_comments_status() {

    return false;
}

function turn_off_comments_hide_existing_comments( $comments ) {

    $comments = array();
    return $comments;
}

function turn_off_comments_admin_bar_render() {

    global $wp_admin_bar;
    $wp_admin_bar->remove_menu( 'comments' );
}

function turn_off_comments_admin_menu_redirect() {

    global $pagenow;

    if ($pagenow === 'edit-comments.php') {

        wp_redirect( admin_url() );
        exit;
    }
}

function turn_off_comments_post_types_support() {

    $post_types = get_post_types();

    foreach ( $post_types as $post_type ) {

        if ( post_type_supports( $post_type, 'comments' ) ) {

            remove_post_type_support( $post_type, 'comments' );
            remove_post_type_support( $post_type, 'trackbacks' );
        }
    }
}

function disable_comment_theme_support() {
    ?>
        <style>
            #comments {
                display: none;
            }
            .nocomments,
            .no-comments,
            .has-comments,
            .post-comments,
            .comments-link,
            .comments-area,
            .comment-respond,
            .comments-closed,
            .comments-wrapper,
            .wp-block-comments,
            .comments-area__wrapper,
            .wp-block-post-comments,
            .wp-block-comments-title,
            .wp-block-comment-template,
            .wp-block-comments-query-loop {
                display: none;
            }
            /** Blocksy **/
            li.meta-comments {
                display: none;
            }
        </style>
    <?php
}

function turn_off_comments_activation_hook() {

    set_transient( 'turn-off-comments-notification', true, 5 );
}

function turn_off_comments_activation_notification() {

    if( get_transient( 'turn-off-comments-notification' ) ) {

        ?>
        <div class="updated notice is-dismissible">
            <p><?php esc_attr_e( 'Thank you for installing Turn Off Comments!', 'turn-off-comments' ); ?></p>
        </div>
        <?php
        delete_transient( 'turn-off-comments-notification' );
    }
}













/**
 * Save installation datetime on plugin activation
 */
function turn_off_comments_activate() {
    // Check if the installation time is already saved
    $installed = get_option('turn_off_comments_installed');
    
    if (!$installed) {
        // Save current datetime if not already set
        update_option('turn_off_comments_installed', current_time('mysql'));
    }
}
register_activation_hook(__FILE__, 'turn_off_comments_activate');

/**
 * Clean up options on plugin uninstallation
 */
function turn_off_comments_uninstall() {
    delete_option('turn_off_comments_installed');
}
register_uninstall_hook(__FILE__, 'turn_off_comments_uninstall');

/**
 * Show migration notice for installations before May 15, 2025
 */
function comments_show_migration_notice() {
    // Only show if new plugin is not active
    if (is_plugin_active('daisy-comments/daisy-comments.php')) {
        return;
    }
    
    // Get installation date
    $install_date = get_option('turn_off_comments_installed');
    
    // Only show notice if:
    // 1. There is NO install date (new installation) OR
    // 2. Installation date is BEFORE May 15, 2025
    if ($install_date && strtotime($install_date) >= strtotime('2025-05-15')) {
        return;
    }
    
    // Get install/activate URLs
    $install_url = wp_nonce_url(
        add_query_arg([
            'action' => 'install-plugin',
            'plugin' => 'daisy-comments'
        ], admin_url('update.php')),
        'install-plugin_daisy-comments'
    );
    
    $activate_url = '';
    if (file_exists(WP_PLUGIN_DIR . '/daisy-comments/daisy-comments.php')) {
        $activate_url = wp_nonce_url(
            add_query_arg([
                'action' => 'activate',
                'plugin' => 'daisy-comments/daisy-comments.php'
            ], admin_url('plugins.php')),
            'activate-plugin_daisy-comments/daisy-comments.php'
        );
    }
    ?>
    <div class="notice notice-error">
        <h4><?php esc_html_e('Important Notice About Turn Off Comments', 'turn-off-comments'); ?></h4>
        <p>
            <?php _e('This plugin is no longer maintained. Please migrate to our new improved plugin <b style="color: blue;">"Daisy Comments"</b> for continued support, new features, and future updates.', 'turn-off-comments'); ?>
        </p>
        <p>
            <?php if ($activate_url) : ?>
                <a href="<?php echo esc_url($activate_url); ?>" class="button button-primary">
                    <?php esc_html_e('Activate Daisy Comments Now', 'turn-off-comments'); ?>
                </a>
            <?php else : ?>
                <a href="<?php echo esc_url($install_url); ?>" class="button button-primary">
                    <?php esc_html_e('Migrate to Daisy Comments Now', 'turn-off-comments'); ?>
                </a>
            <?php endif; ?>
        </p>
    </div>
    <?php
}
add_action('admin_notices', 'comments_show_migration_notice');