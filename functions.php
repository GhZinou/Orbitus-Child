<?php
/**
 * Enqueue parent theme styles
 */
function orbitus_child_enqueue_styles() {
    wp_enqueue_style( 'orbitus-parent-style', get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'orbitus-child-style', get_stylesheet_directory_uri() . '/style.css', array('orbitus-parent-style') );
}
add_action( 'wp_enqueue_scripts', 'orbitus_child_enqueue_styles' );

/**
 * Require functions.php from template-one folder
 */
require_once get_stylesheet_directory() . '/template-one/functions.php';
?>