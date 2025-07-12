<?php

/**
 * Enqueue scripts and styles for Template 01.
 */
function enqueue_template_one_assets() {
        
    // Enqueue Google Fonts
    wp_enqueue_style( 'google-fonts-cairo', 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap', [], null );

    // Enqueue Font Awesome
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], '6.5.0' );

    // Enqueue the main stylesheet for the template
    // Use get_stylesheet_directory_uri() for child theme assets
    wp_enqueue_style( 'template-one-style', get_stylesheet_directory_uri() . '/template-one/assets/one.css', [], '1.0.0' );

    // Enqueue the JavaScript file
    // Use get_stylesheet_directory_uri() for child theme assets
    wp_enqueue_script( 'template-one-script', get_stylesheet_directory_uri() . '/template-one/assets/one.js', [], '1.0.0', true );
    
}

add_action( 'wp_enqueue_scripts', 'enqueue_template_one_assets' );

// Add other theme functions below...

?>