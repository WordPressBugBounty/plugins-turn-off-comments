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


/* Since v1.6.17 */

// Hook to admin_notices to display the messages
add_action( 'admin_notices', 'blogbd2_recommended_plugins' );

function blogbd2_recommended_plugins() {
    // Include necessary plugin functions
    include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

    $plugin_file = 'about-post-author/about-post-author.php';

    // Check if the plugin is installed
    $is_installed = file_exists( WP_PLUGIN_DIR . '/' . $plugin_file );

    // Check if the plugin is active
    $is_active = is_plugin_active( $plugin_file );

    // Display "Install Now" notice if the plugin is not installed
    if ( !$is_installed ) {
        $plugin_slug = 'about-post-author';
        $install_url = wp_nonce_url(
            self_admin_url( 'update.php?action=install-plugin&plugin=' . $plugin_slug ),
            'install-plugin_' . $plugin_slug
        );
        ?>
        <div class="notice notice-info is-dismissible">
            <p>
                <?php _e( 'We recommend installing the "Author Box" plugin to enhance your blogging experience.', 'turn-off-comments' ); ?>
            </p>
            <p>
                <a href="<?php echo esc_url( $install_url ); ?>" class="button button-primary">
                    <?php _e( 'Install Now', 'turn-off-comments' ); ?>
                </a>
            </p>
        </div>
        <?php
    }

    // Display "Activate Now" notice if the plugin is installed but not activated
    elseif ( !$is_active ) {
        $activate_url = wp_nonce_url(
            self_admin_url( 'plugins.php?action=activate&plugin=' . $plugin_file ),
            'activate-plugin_' . $plugin_file
        );
        ?>
        <div class="notice notice-warning is-dismissible">
            <p>
                <?php _e( 'The "Author Box" plugin is installed but not active. Please activate it to enhance your blogging experience.', 'turn-off-comments' ); ?>
            </p>
            <p>
                <a href="<?php echo esc_url( $activate_url ); ?>" class="button button-primary">
                    <?php _e( 'Activate Now', 'turn-off-comments' ); ?>
                </a>
            </p>
        </div>
        <?php
    }
}

// Enqueue script to handle plugin installation and activation
add_action( 'admin_enqueue_scripts', 'blogbd2_plugins_enqueue_script' );
function blogbd2_plugins_enqueue_script() {
    // Include the WordPress plugin installer script
    wp_enqueue_script( 'plugin-install' );
    wp_enqueue_script( 'updates' );
}
