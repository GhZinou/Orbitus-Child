<?php

/**
 * Enqueue scripts and styles for Template 01.
 */
function enqueue_template_one_assets() {
    wp_enqueue_style( 'google-fonts-cairo', 'https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap', [], null );
    wp_enqueue_style( 'font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css', [], '6.5.0' );
    wp_enqueue_style( 'template-one-style', get_stylesheet_directory_uri() . '/template-one/assets/one.css', [], '1.0.0' );
    wp_enqueue_script( 'template-one-script', get_stylesheet_directory_uri() . '/template-one/assets/one.js', [], '1.0.0', true );
}
add_action( 'wp_enqueue_scripts', 'enqueue_template_one_assets' );

/**
 * Add Customizer settings for Template 01.
 * @param WP_Customize_Manager $wp_customize
 */
function template_one_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'template_one_hero_section', [
        'title'    => __( 'Template 01 Hero Section', 'your-text-domain' ),
        'priority' => 30,
    ] );

    // Hide Hero Section
    $wp_customize->add_setting( 'template_one_hero_hide', [
        'default'           => false,
        'sanitize_callback' => 'wp_validate_boolean',
    ] );
    $wp_customize->add_control( 'template_one_hero_hide_control', [
        'label'    => __( 'Hide Hero Section?', 'your-text-domain' ),
        'section'  => 'template_one_hero_section',
        'type'     => 'checkbox',
        'settings' => 'template_one_hero_hide',
    ] );

    // Hero Heading Text
    $wp_customize->add_setting( 'template_one_hero_heading_text', [
        'default'           => __( 'اكتشف عروضنا الحصرية', 'your-text-domain' ),
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'template_one_hero_heading_text_control', [
        'label'    => __( 'Hero Heading Text', 'your-text-domain' ),
        'section'  => 'template_one_hero_section',
        'type'     => 'text',
        'settings' => 'template_one_hero_heading_text',
    ] );

    // Hero Paragraph Text
    $wp_customize->add_setting( 'template_one_hero_paragraph_text', [
        'default'           => __( 'تسوق أفضل المنتجات بأفضل قيمة وخدمة لا مثيل لها.', 'your-text-domain' ),
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'template_one_hero_paragraph_text_control', [
        'label'    => __( 'Hero Paragraph Text', 'your-text-domain' ),
        'section'  => 'template_one_hero_section',
        'type'     => 'textarea',
        'settings' => 'template_one_hero_paragraph_text',
    ] );

    // Hero Button Text
    $wp_customize->add_setting( 'template_one_hero_button_text', [
        'default'           => __( 'تسوق الآن', 'your-text-domain' ),
        'sanitize_callback' => 'sanitize_text_field',
    ] );
    $wp_customize->add_control( 'template_one_hero_button_text_control', [
        'label'    => __( 'Hero Button Text', 'your-text-domain' ),
        'section'  => 'template_one_hero_section',
        'type'     => 'text',
        'settings' => 'template_one_hero_button_text',
    ] );

    // Hero Button Background Color
    $wp_customize->add_setting( 'template_one_hero_button_bg_color', [
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'template_one_hero_button_bg_color_control', [
        'label'    => __( 'Hero Button Background Color', 'your-text-domain' ),
        'section'  => 'template_one_hero_section',
        'settings' => 'template_one_hero_button_bg_color',
    ] ) );

    // Hero Button Text Color
    $wp_customize->add_setting( 'template_one_hero_button_text_color', [
        'default'           => '#000000',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'template_one_hero_button_text_color_control', [
        'label'    => __( 'Hero Button Text Color', 'your-text-domain' ),
        'section'  => 'template_one_hero_section',
        'settings' => 'template_one_hero_button_text_color',
    ] ) );

    // Hero Button Hover Background Color
    $wp_customize->add_setting( 'template_one_hero_button_hover_bg_color', [
        'default'           => '#eeeeee',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'template_one_hero_button_hover_bg_color_control', [
        'label'    => __( 'Hero Button Hover Background Color', 'your-text-domain' ),
        'section'  => 'template_one_hero_section',
        'settings' => 'template_one_hero_button_hover_bg_color',
    ] ) );

    // Hero Button Hover Text Color
    $wp_customize->add_setting( 'template_one_hero_button_hover_text_color', [
        'default'           => '#333333',
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'template_one_hero_button_hover_text_color_control', [
        'label'    => __( 'Hero Button Hover Text Color', 'your-text-domain' ),
        'section'  => 'template_one_hero_section',
        'settings' => 'template_one_hero_button_hover_text_color',
    ] ) );

    // Hero Background Primary Color (for gradient)
    $wp_customize->add_setting( 'template_one_hero_bg_primary_color', [
        'default'           => '#4CAF50', // A shade of green
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'template_one_hero_bg_primary_color_control', [
        'label'    => __( 'Hero Background Primary Color', 'your-text-domain' ),
        'section'  => 'template_one_hero_section',
        'settings' => 'template_one_hero_bg_primary_color',
    ] ) );

    // Hero Background Secondary Color (for gradient)
    $wp_customize->add_setting( 'template_one_hero_bg_secondary_color', [
        'default'           => '#8BC34A', // Another shade of green/lime
        'sanitize_callback' => 'sanitize_hex_color',
    ] );
    $wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'template_one_hero_bg_secondary_color_control', [
        'label'    => __( 'Hero Background Secondary Color', 'your-text-domain' ),
        'section'  => 'template_one_hero_section',
        'settings' => 'template_one_hero_bg_secondary_color',
    ] ) );
}
add_action( 'customize_register', 'template_one_customize_register' );

/**
 * Output dynamic CSS for Customizer settings.
 */
function template_one_dynamic_css() {
    $button_bg_color         = get_theme_mod( 'template_one_hero_button_bg_color', '#ffffff' );
    $button_text_color       = get_theme_mod( 'template_one_hero_button_text_color', '#000000' );
    $button_hover_bg_color   = get_theme_mod( 'template_one_hero_button_hover_bg_color', '#eeeeee' );
    $button_hover_text_color = get_theme_mod( 'template_one_hero_button_hover_text_color', '#333333' );
    $hero_bg_primary_color   = get_theme_mod( 'template_one_hero_bg_primary_color', '#4CAF50' );
    $hero_bg_secondary_color = get_theme_mod( 'template_one_hero_bg_secondary_color', '#8BC34A' );

    $css = "
        .hero-section {
            background-image: linear-gradient(to right, {$hero_bg_primary_color}, {$hero_bg_secondary_color});
            background-color: transparent; /* Ensure no static background-color overrides gradient */
        }
        .hero-section .hero-button {
            background-color: {$button_bg_color};
            color: {$button_text_color};
        }
        .hero-section .hero-button:hover {
            background-color: {$button_hover_bg_color};
            color: {$button_hover_text_color};
        }
    ";
    wp_add_inline_style( 'template-one-style', $css );
}
add_action( 'wp_enqueue_scripts', 'template_one_dynamic_css' );

/**
 * Customizer Live Preview (JavaScript)
 */
function template_one_customizer_live_preview() {
    wp_enqueue_script(
        'template-one-customizer',
        get_stylesheet_directory_uri() . '/template-one/assets/customizer-preview.js',
        ['jquery', 'customize-preview'],
        '1.0.0',
        true
    );
}
add_action( 'customize_preview_init', 'template_one_customizer_live_preview' );