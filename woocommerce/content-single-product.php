<?php
/**
 * The Template for displaying product archives, including the main shop page.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We recommend you do not modify the actual template file
 * in core WooCommerce, but override it in your theme.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

get_header();

if ( ! class_exists( 'WooCommerce' ) ) {
    echo '<p style="text-align: center; color: red; font-weight: bold;">WooCommerce is not active. Please activate WooCommerce to use this template.</p>';
    get_footer();
    return;
}
?>

<style>
    /*
     * Custom styles for product cards, merged directly from one.css,
     * with adjusted grid columns and font sizes for responsiveness.
     * CSS variables are used as they appear in one.css and are expected
     * to be defined by another stylesheet in your theme.
     */

    html {
        box-sizing: border-box;
    }

    *, *::before, *::after {
        box-sizing: inherit;
    }

    body {
        font-family: 'Arial', sans-serif;
        line-height: 1.6;
        color: #333;
        margin: 0;
        padding: 0;
        background-color: #f8f8f8;
        direction: rtl;
        text-align: right;
        font-size: 0.875rem; /* Base font size reduced to ~14px */
    }

    a {
        color: var(--primary-color);
        text-decoration: none;
        transition: color 0.3s ease;
    }

    a:hover {
        color: var(--buy-bouton-hover-color);
    }

    .showcase-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
    }

    .section-title {
        text-align: center;
        font-size: 1.8em; /* Reduced for desktop */
        color: #333;
        margin-bottom: 40px;
        position: relative;
        padding-bottom: 10px;
    }

    .section-title::after {
        content: '';
        position: absolute;
        left: 50%;
        transform: translateX(-50%);
        bottom: 0;
        width: 60px;
        height: 3px;
        background-color: var(--primary-color);
        border-radius: 2px;
    }

    .hero-section { /* Included as it's in one.css, though not directly part of product archive display */
        color: #fff;
        text-align: center;
        padding: 100px 20px;
        position: relative;
        overflow: hidden;
        background-size: cover;
        background-position: center center;
        background-image: linear-gradient(to right, #4CAF50, #8BC34A); /* Default gradient */
    }

    .hero-section h1 {
        font-size: 2.5em; /* Reduced for desktop */
        margin-bottom: 20px;
        font-weight: 700;
        line-height: 1.2;
    }

    .hero-section p {
        font-size: 1.1em; /* Reduced for desktop */
        max-width: 700px;
        margin: 0 auto 40px;
    }

    .hero-section .hero-button {
        display: inline-block;
        font-size: 0.9em; /* Reduced for desktop */
        font-weight: bold;
        text-transform: uppercase;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
        margin-top: 20px;
        border: none;
        padding: 20px 40px;
        border-radius: 50px;
    }

    .hero-icon {
        position: absolute;
        font-size: 8em; /* Kept large as is an icon */
        color: rgba(255, 255, 255, 0.1);
        animation: float 6s ease-in-out infinite;
    }

    .icon1 { top: 10%; right: 10%; animation-delay: 0s; }
    .icon2 { bottom: 15%; left: 15%; animation-delay: 2s; }
    .icon3 { top: 20%; left: 10%; animation-delay: 4s; }
    .icon4 { bottom: 10%; right: 10%; animation-delay: 1s; }

    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-20px); }
        100% { transform: translateY(0px); }
    }

    /* Product Grid - Column adjustments */
    .product-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr); /* Desktop: 4 products per row */
        gap: 30px;
        padding: 20px 0;
    }

    .product-card {
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.12);
    }

    .product-card-image-wrapper {
        position: relative;
        width: 100%;
        padding-top: 100%;
        overflow: hidden;
        background-color: #eee;
    }

    .product-card-image {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-card-image {
        transform: scale(1.05);
    }

    .product-card-badges {
        position: absolute;
        top: 10px;
        left: 10px;
        display: flex;
        flex-direction: column;
        gap: 5px;
        z-index: 10;
    }

    .product-card-badge {
        background-color: #28a745;
        color: #fff;
        padding: 5px 10px;
        border-radius: 4px;
        font-size: 0.8em; /* Kept small */
        font-weight: bold;
        text-transform: uppercase;
    }

    .product-card-badge.sale {
        background-color: var(--sale-badge-background);
    }

    .product-card-badge.new {
        background-color: var(--new-badge-color);
        color: #333;
    }

    .product-card-content {
        padding: 15px;
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        justify-content: space-between;
    }

    .product-card-title {
        font-size: 1.1em; /* Reduced for desktop */
        margin-bottom: 10px;
        min-height: 2.6em;
        line-height: 1.3em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        text-overflow: ellipsis;
    }

    .product-card-title a {
        color: #333;
        transition: color 0.3s ease;
    }

    .product-card-title a:hover {
        color: var(--primary-color);
    }

    .product-card-stars {
        color: #ffc107;
        margin-bottom: 10px;
        font-size: 1.1em; /* No change, seems appropriate */
    }

    .product-card-price {
        font-size: 1.2em; /* Reduced for desktop */
        font-weight: bold;
        margin-top: auto;
        margin-bottom: 15px;
        display: flex;
        align-items: baseline;
        gap: 10px;
        /* Ensure prices stay on one line */
        flex-wrap: nowrap;
    }

    .product-card-price .old-price {
        text-decoration: line-through;
        color: #888;
        font-size: 0.9em; /* Relative to product-card-price */
        font-weight: normal;
        white-space: nowrap;
    }

    .product-card-price .sale-price {
        color: var(--sale-price-color);
        white-space: nowrap;
    }

    .product-card-button-wrapper {
        text-align: center;
    }

    .product-card-button {
        display: inline-block;
        background-color: var(--buy-bouton-color);
        color: #fff;
        padding: 12px 20px;
        border-radius: 5px;
        font-weight: bold;
        text-transform: uppercase;
        transition: background-color 0.3s ease, transform 0.2s ease;
        width: 100%;
        font-size: 0.9em; /* Added for desktop */
    }

    .product-card-button:hover {
        background-color: var(--buy-bouton-hover);
        transform: translateY(-2px);
    }

    /* Responsive adjustments */
    @media (max-width: 992px) { /* Tablet breakpoint */
        .product-grid {
            grid-template-columns: repeat(3, 1fr); /* Tablet: 3 per row */
            gap: 25px;
        }
        .section-title {
            font-size: 1.5em; /* Adjusted for tablet */
            margin-bottom: 30px;
        }
        .hero-section h1 {
            font-size: 2em; /* Adjusted for tablet */
        }
        .hero-section p {
            font-size: 0.9em; /* Adjusted for tablet */
        }
        .hero-section .hero-button {
            font-size: 0.85em; /* Adjusted for tablet */
        }
        .hero-icon {
            font-size: 6em;
        }
    }

    @media (max-width: 767px) { /* Mobile breakpoint */
        .product-grid {
            grid-template-columns: repeat(2, 1fr); /* Mobile: 2 per row */
            gap: 20px;
        }
        .section-title {
            font-size: 1.3em; /* Adjusted for mobile */
        }
        .product-card-content {
            padding: 10px;
        }
        .product-card-title {
            font-size: 0.9em; /* Adjusted for mobile */
            min-height: unset;
            -webkit-line-clamp: unset;
            white-space: normal;
        }
        .product-card-price {
            font-size: 1em; /* Adjusted for mobile */
            margin-bottom: 10px;
        }
        .product-card-button {
            padding: 10px 15px;
            font-size: 0.8em; /* Adjusted for mobile */
        }

        .hero-section {
            padding: 80px 15px;
        }
        .hero-section h1 {
            font-size: 1.6em; /* Adjusted for mobile */
        }
        .hero-section p {
            font-size: 0.8em; /* Adjusted for mobile */
        }
        .hero-section .hero-button {
            font-size: 0.75em; /* Adjusted for mobile */
        }
        .hero-icon {
            font-size: 5em;
        }
        .icon1 { top: 5%; right: 5%; }
        .icon2 { bottom: 10%; left: 5%; }
        .icon3 { top: 15%; left: 5%; }
        .icon4 { bottom: 5%; right: 5%; }

        .showcase-container {
            padding: 15px;
        }
    }

    /* Keeping 2 columns even for very small mobile screens, as requested */
    @media (max-width: 480px) {
        .hero-section h1 {
            font-size: 1.4em; /* Adjusted for very small mobile */
        }
        .hero-section p {
            font-size: 0.75em; /* Adjusted for very small mobile */
        }
        .hero-icon {
            font-size: 4em;
        }
        /* Product grid remains 2 columns from 767px breakpoint */
    }
</style>

<div class="showcase-container" id="product-grid">
    <h2 class="section-title"><?php echo esc_html__( 'منتجاتنا', 'your-text-domain' ); ?></h2>

    <div class="product-grid">
        <?php
        // Arguments to get ALL products, regardless of current archive query
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1, // Get all products
            'post_status'    => 'publish',
            // Explicitly remove any default taxonomy or meta queries from WooCommerce archive
            'tax_query'      => array(),
            'meta_query'     => array(),
            'orderby'        => 'date', // Order by date, latest first
            'order'          => 'DESC',
        ];

        $products = new WP_Query( $args );

        if ( $products->have_posts() ) :
            while ( $products->have_posts() ) : $products->the_post();
                global $product;

                // Ensure $product is a valid WC_Product object
                if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
                    continue; // Skip if $product is not valid
                }

                $product_id        = $product->get_id();
                $product_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'full' );
                $product_image_url = $product_image_url ? $product_image_url[0] : wc_placeholder_img_url();
                $product_title     = $product->get_name();
                $product_permalink = get_permalink( $product_id );
                $is_on_sale        = $product->is_on_sale();
                $date_created_timestamp = strtotime( $product->get_date_created() );
                // Check if product is new (within the last 30 days, 2592000 seconds)
                $is_new                 = ( time() - $date_created_timestamp ) < 2592000;
                ?>

                <div class="product-card">
                    <a href="<?php echo esc_url( $product_permalink ); ?>" style="text-decoration: none; color: inherit;">
                        <div class="product-card-image-wrapper">
                            <img src="<?php echo esc_url( $product_image_url ); ?>" alt="<?php echo esc_attr( $product_title ); ?>" class="product-card-image">
                            <div class="product-card-badges">
                                <?php if ( $is_on_sale ) : ?>
                                    <span class="product-card-badge sale"><?php echo esc_html__( 'تخفيض', 'your-text-domain' ); ?></span>
                                <?php endif; ?>
                                <?php if ( $is_new ) : ?>
                                    <span class="product-card-badge new"><?php echo esc_html__( 'جديد', 'your-text-domain' ); ?></span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>
                    <div class="product-card-content">
                        <h3 class="product-card-title"><a href="<?php echo esc_url( $product_permalink ); ?>"><?php echo esc_html( $product_title ); ?></a></h3>
                        <div class="product-card-stars">
                            <?php echo str_repeat('&#9733;', 5); // Hardcoded 5 stars as per original template ?>
                        </div>
                        <div class="product-card-price">
                            <?php if ( $is_on_sale && $product->get_regular_price() ) : ?>
                                <span class="old-price"><?php echo wc_price( $product->get_regular_price() ); ?></span>
                                <span class="sale-price"><?php echo wc_price( $product->get_sale_price() ); ?></span>
                            <?php else : ?>
                                <?php echo wc_price( $product->get_price() ); ?>
                            <?php endif; ?>
                        </div>
                        <div class="product-card-button-wrapper">
                            <a href="<?php echo esc_url( $product_permalink ); ?>" class="product-card-button"><?php echo esc_html__( 'اشتري الآن', 'your-text-domain' ); ?></a>
                        </div>
                    </div>
                </div>

            <?php
            endwhile;
            wp_reset_postdata(); // Restore original post data
        else :
            echo '<p style="text-align: center; width: 100%;">'. esc_html__( 'لم يتم العثور على منتجات.', 'your-text-domain' ) . '</p>';
        endif;
        ?>
    </div>
</div>

<?php
get_footer();
?>