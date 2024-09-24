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

