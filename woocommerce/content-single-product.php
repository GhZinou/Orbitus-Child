<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We recommend you do not modify the actual template file
 * in core WooCommerce, but override it in your theme.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure $product is a WC_Product object
if ( ! is_a( $product, 'WC_Product' ) ) {
    return;
}

$product_id = $product->get_id();
$product_image_id = get_post_thumbnail_id( $product_id );
$product_image_url = wp_get_attachment_image_src( $product_image_id, 'large' );
$product_image_alt = get_post_meta( $product_image_id, '_wp_attachment_image_alt', true );
if ( empty( $product_image_alt ) ) {
    $product_image_alt = $product->get_name(); // Fallback to product name
}

$product_summary = $product->get_short_description();
$product_description = $product->get_description();

// --- SALE-RELATED LOGIC ---
$is_on_sale = $product->is_on_sale();
$regular_price = (float) $product->get_regular_price();
$sale_price = (float) $product->get_sale_price();
$discount_percentage = 0;

if ( $is_on_sale && $regular_price > 0 && $sale_price < $regular_price ) {
    $discount_percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );
}

// Sale end date for countdown timer
$sale_end_date = null;
if ( $is_on_sale ) {
    $date_on_sale_to = $product->get_date_on_sale_to();
    if ( $date_on_sale_to ) {
        // Ensure it's a DateTime object and not in the past
        if ( $date_on_sale_to instanceof WC_DateTime && $date_on_sale_to->getTimestamp() > current_time( 'timestamp' ) ) {
            $sale_end_date = $date_on_sale_to->getTimestamp();
        } elseif ( is_string( $date_on_sale_to ) ) {
            // Fallback for string dates, though WC_DateTime is expected
            $timestamp = strtotime( $date_on_sale_to );
            if ( $timestamp && $timestamp > current_time( 'timestamp' ) ) {
                $sale_end_date = $timestamp;
            }
        }
    }
}
// --- END SALE-RELATED LOGIC ---

?>

<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'modern-single-product-wrapper' ); ?> role="main"
    style="
        display: flex;
        flex-direction: column;
        max-width: 1200px;
        margin: 40px auto;
        background-color: #ffffff;
        border-radius: 18px; /* Slightly more rounded */
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1); /* Elevated shadow */
        overflow: hidden;
        font-family: 'Inter', 'Poppins', sans-serif; /* Prioritize Inter for modern feel, fallback to Poppins */
        color: #2c3e50; /* Improved contrast for main text */
        direction: rtl; /* Set direction to RTL for the entire wrapper */
    "
>

    <style>
        :root {
            --primary-green: #27ae60;
            --primary-green-hover: #2ecc71;
            --text-dark: #2c3e50;
            --text-medium: #444;
            --text-light: #666;
            --background-light: #fcfcfc;
            --border-color: #eee;
            --sale-badge-bg: #e74c3c; /* Red for discount */
            --sale-countdown-bg: #3498db; /* Blue for urgency */
        }

        /* Responsive layout for header section */
        .product-header-section {
            display: flex;
            flex-direction: column; /* Mobile-first: column */
            padding: 25px; /* Mobile padding */
            gap: 25px; /* Mobile gap */
            align-items: flex-start;
            position: relative;
        }
        @media (min-width: 768px) {
            .product-header-section {
                flex-direction: row-reverse; /* Desktop: row-reverse for RTL */
                padding: 40px; /* Desktop padding */
                gap: 40px; /* Desktop gap */
            }
        }

        /* Typography sizing with clamp() */
        .modern-single-product-wrapper h1.product-title {
            font-size: clamp(1.8rem, 5vw, 2.8rem); /* Scales from 1.8rem to 2.8rem */
            color: var(--text-dark);
            margin-bottom: 15px;
            line-height: 1.2;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.03);
            text-align: center; /* Center on mobile */
            display: flex; /* Allow badge beside it */
            align-items: center;
            justify-content: center; /* Center the title and badge together on mobile */
            flex-wrap: wrap; /* Allow wrapping for long titles + badge */
        }
        @media (min-width: 768px) {
            .modern-single-product-wrapper h1.product-title {
                text-align: right; /* Right align on desktop for RTL */
                justify-content: flex-end; /* Right align on desktop for RTL */
            }
        }

        .modern-single-product-wrapper .product-price {
            font-size: clamp(1.6rem, 4vw, 2.2rem); /* Scales from 1.6rem to 2.2rem */
            font-weight: 700;
            margin-bottom: 25px;
            letter-spacing: -0.5px;
            color: #e74c3c; /* Default red */
            text-align: center; /* Center on mobile */
        }
        @media (min-width: 768px) {
            .modern-single-product-wrapper .product-price {
                text-align: right; /* Right align on desktop for RTL */
            }
        }

        .modern-single-product-wrapper .product-short-description {
            font-size: clamp(1rem, 2.5vw, 1.15em); /* Scales from 1rem to 1.15em */
            line-height: 1.7;
            color: var(--text-medium);
            margin-bottom: 30px;
            max-width: 600px;
            text-align: right; /* Align text to the right for RTL */
        }
        .modern-single-product-wrapper .product-long-description {
            font-size: clamp(1rem, 2.5vw, 1.1em); /* Scales from 1rem to 1.1em */
            line-height: 1.8;
            color: var(--text-medium);
            text-align: right; /* Align text to the right for RTL */
        }

        /* Add to Cart Section - Mobile Full Width, Loading Spinner */
        .product-action-area {
            display: flex;
            flex-direction: column; /* Mobile: stack quantity and button */
            align-items: stretch; /* Stretch items to full width */
            gap: 15px; /* Smaller gap on mobile */
            margin-top: 20px;
        }
        @media (min-width: 768px) {
            .product-action-area {
                flex-direction: row-reverse; /* Desktop: side-by-side, reversed for RTL */
                align-items: center;
                gap: 20px;
            }
        }

        .product-action-area .quantity .input-text.qty {
            width: 100%; /* Full width on mobile */
            max-width: 120px; /* Constrain on desktop */
            padding: 12px 15px; /* Larger touch area */
            border: 1px solid #ddd;
            border-radius: 10px;
            text-align: center;
            font-size: 1.1em;
            color: var(--text-dark);
            transition: border-color 0.3s ease, box-shadow 0.3s ease;
            box-sizing: border-box; /* Include padding in width */
            -webkit-appearance: none; /* Remove default spinner for number input */
            -moz-appearance: textfield;
            touch-action: manipulation; /* Improves responsiveness for touch */
            cursor: pointer;
        }
        .product-action-area .quantity .input-text.qty:focus {
            border-color: var(--primary-green);
            box-shadow: 0 0 0 3px rgba(39, 174, 96, 0.2);
            outline: none;
        }

        .product-action-area .single_add_to_cart_button.button.alt {
            background-color: var(--primary-green);
            color: #ffffff;
            border: none;
            padding: 15px 30px;
            font-size: 1.1em;
            font-weight: 600;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease, transform 0.2s ease, box-shadow 0.3s ease;
            box-shadow: 0 4px 15px rgba(39, 174, 96, 0.3);
            display: flex; /* Use flex for potential spinner */
            align-items: center;
            justify-content: center;
            width: 100%; /* Full width on mobile */
            min-height: 50px; /* Ensure a minimum height for touch */
        }
        @media (min-width: 768px) {
            .product-action-area .single_add_to_cart_button.button.alt {
                width: auto; /* Revert to auto width on desktop */
            }
        }
        .product-action-area .single_add_to_cart_button.button.alt:hover {
            background-color: var(--primary-green-hover);
            transform: translateY(-3px) scale(1.01); /* Enhanced hover effect */
            box-shadow: 0 6px 20px rgba(39, 174, 96, 0.4);
        }
        .product-action-area .single_add_to_cart_button.button.alt:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.2);
        }
        .product-action-area .single_add_to_cart_button.button.alt:focus-visible {
            outline: 2px solid var(--primary-green-hover);
            outline-offset: 3px;
        }

        /* Loading spinner (hidden by default) */
        .single_add_to_cart_button.button.alt::before {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            border: 3px solid #fff;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: none; /* Hidden by default */
            animation: spin 1s linear infinite;
        }
        .single_add_to_cart_button.button.alt.loading {
            pointer-events: none; /* Disable clicks during loading */
            opacity: 0.8;
        }
        .single_add_to_cart_button.button.alt.loading::before {
            display: block; /* Show spinner */
        }
        .single_add_to_cart_button.button.alt.loading span {
            visibility: hidden; /* Hide text during loading */
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Product Image Placeholder */
        .product-image-container .placeholder-image {
            width: 100%;
            height: 300px;
            background-color: #f0f0f0;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ccc; /* Lighter color */
            font-size: 3em; /* Larger icon */
            text-align: center;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.05);
        }

        /* Price highlighting for sale */
        .product-price.on-sale {
            color: var(--primary-green); /* Green for sale price */
            display: flex; /* To allow alignment of original price */
            align-items: baseline;
            justify-content: center; /* Center on mobile */
            gap: 10px;
        }
        @media (min-width: 768px) {
            .product-price.on-sale {
                justify-content: flex-end; /* Right align on desktop for RTL */
            }
        }
        .product-price.on-sale del {
            color: var(--text-light); /* Lighter color for original price */
            font-size: 0.7em; /* Smaller relative size */
            opacity: 0.8;
            text-decoration: line-through;
        }
        .product-price.on-sale ins {
            text-decoration: none; /* Remove underline from sale price */
        }

        /* Section dividers */
        .product-description-section {
            border-top: none; /* Remove hard line */
            position: relative;
        }
        .product-description-section::before {
            content: '';
            position: absolute;
            top: 0;
            right: 0; /* Change left to right for RTL */
            width: 100%;
            height: 5px; /* Height of the subtle divider */
            background: linear-gradient(to left, rgba(0,0,0,0.05), transparent); /* Subtle gradient for RTL */
            opacity: 0.7;
        }

        /* NEW: Discount Percentage Badge */
        .discount-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background-color: var(--sale-badge-bg);
            color: #ffffff;
            font-size: clamp(0.8rem, 2.5vw, 1.1rem); /* Responsive font size */
            font-weight: 700;
            padding: 5px 12px;
            border-radius: 8px;
            margin-right: 15px; /* Change margin-left to margin-right for RTL */
            margin-left: 0; /* Ensure left margin is zero */
            line-height: 1; /* Prevent extra height */
            white-space: nowrap; /* Keep text on one line */
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
            transform: rotate(-3deg); /* Slight playful tilt, reversed for RTL */
            transition: transform 0.2s ease-in-out;
            cursor: default; /* Not clickable */
        }
        .discount-badge:hover {
            transform: rotate(0deg) scale(1.05); /* Straighten and slightly grow on hover */
        }
        @media (max-width: 767px) {
            .discount-badge {
                margin-right: 0; /* No margin on small screens when wrapping */
                margin-top: 10px; /* Space below title if wrapped */
            }
        }

        /* NEW: Countdown Timer Hero */
        .sale-countdown-hero {
            background: linear-gradient(90deg, #3498db, #2980b9); /* Blue gradient */
            color: #ffffff;
            text-align: center;
            padding: 15px 20px; /* Smaller padding on mobile */
            font-size: clamp(1rem, 3vw, 1.4rem);
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 15px;
            border-top-left-radius: 18px;
            border-top-right-radius: 18px;
            margin-bottom: 25px; /* Space before product content */
            box-shadow: inset 0 -5px 10px rgba(0,0,0,0.1);
            flex-direction: row-reverse; /* Reverse order for countdown segments */
        }
        @media (min-width: 768px) {
            .sale-countdown-hero {
                padding: 20px 30px; /* Larger padding on desktop */
            }
        }
        .countdown-segment {
            display: flex;
            flex-direction: column;
            align-items: center;
            line-height: 1.2;
        }
        .countdown-value {
            font-size: clamp(1.8rem, 6vw, 2.5rem);
            font-weight: 800;
            letter-spacing: -1px;
        }
        .countdown-label {
            font-size: clamp(0.7rem, 2vw, 0.9rem);
            text-transform: uppercase;
            opacity: 0.8;
        }
        .countdown-separator {
            font-size: clamp(1.8rem, 6vw, 2.5rem);
            font-weight: 800;
            color: rgba(255,255,255,0.7);
        }
        /* Hide timer if no sale or date */
        .sale-countdown-hero:empty {
            display: none;
        }
    </style>

    <?php if ( $sale_end_date ) : // Only show if there's a timed sale ?>
        <div class="sale-countdown-hero" id="sale-countdown" data-sale-end="<?php echo esc_attr( $sale_end_date ); ?>">
            </div>
    <?php endif; ?>

    <div class="product-header-section">
        <div style="
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(240, 248, 255, 0.8) 0%, rgba(255, 255, 255, 0.9) 100%);
            opacity: 0.7;
            z-index: 0;
            border-radius: 18px; /* Match wrapper radius */
        "></div>

        <div class="product-image-container" aria-label="Product Image"
            style="
                flex: 1;
                min-width: 280px; /* Slightly smaller min-width for mobile */
                max-width: 500px;
                position: relative;
                z-index: 1;
                margin: 0 auto; /* Center image on mobile */
            "
        >
            <?php if ( $product_image_url && $product_image_url[0] ) : ?>
                <img
                    src="<?php echo esc_url( $product_image_url[0] ); ?>"
                    alt="<?php echo esc_attr( $product_image_alt ); ?>"
                    class="product-main-image"
                    style="
                        width: 100%;
                        height: auto;
                        border-radius: 12px;
                        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
                        transition: transform 0.3s ease-in-out;
                    "
                    onmouseover="this.style.transform='scale(1.02)'"
                    onmouseout="this.style.transform='scale(1)'"
                />
            <?php else : ?>
                <div class="placeholder-image" aria-hidden="true" role="img" aria-label="No image available for this product">
                    <svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M6.002 5.5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0"/>
                        <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5v-9A1.5 1.5 0 0 0 14.5 2zM14 13.5a.5.5 0 0 1-.5.5h-13a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h13a.5.5 0 0 1 .5.5z"/>
                        <path d="M2 6a1 1 0 0 1 1-1h10a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1zm1-1v6.5l3-3 3 3 5-5.5V5z"/>
                    </svg>
                </div>
            <?php endif; ?>
        </div>

        <div class="product-summary-content"
            style="
                flex: 1.5;
                position: relative;
                z-index: 1;
                display: flex;
                flex-direction: column;
                justify-content: center;
            "
        >
            <h1 class="product-title">
                <?php the_title(); ?>
                <?php if ( $is_on_sale && $discount_percentage > 0 ) : ?>
                    <span class="discount-badge">
                        <?php echo esc_html( $discount_percentage ); ?>% خصم </span>
                <?php endif; ?>
            </h1>

            <div class="product-price <?php echo $is_on_sale ? 'on-sale' : ''; ?>">
                <?php echo $product->get_price_html(); ?>
            </div>

            <div class="product-short-description"
                style="
                    max-width: 600px;
                    margin-right: auto; /* Push to the right for RTL */
                    margin-left: 0; /* Ensure no left margin */
                "
            >
                <?php echo apply_filters( 'woocommerce_short_description', $product_summary ); ?>
            </div>

            <div class="product-action-area" aria-live="polite">
                <?php
                if ( $product->is_type( 'simple' ) ) {
                    woocommerce_simple_add_to_cart();
                }
                // Add logic for other product types here if needed (e.g., variable.php)
                ?>
            </div>
        </div>
    </div>

    <div class="product-description-section"
        style="
            padding: 40px;
            background-color: var(--background-light);
            border-bottom-left-radius: 18px; /* Match wrapper radius */
            border-bottom-right-radius: 18px; /* Match wrapper radius */
        "
    >
        <h2 class="section-title"
            style="
                font-size: 2.2em;
                color: var(--text-dark);
                margin-bottom: 25px;
                text-align: center;
                position: relative;
                padding-bottom: 10px;
            "
        >
            تفاصيل المنتج <span style="
                content: '';
                position: absolute;
                bottom: 0;
                left: 50%;
                transform: translateX(-50%);
                width: 80px;
                height: 4px;
                background-color: var(--primary-green);
                border-radius: 2px;
            "></span>
        </h2>
        <div class="product-long-description"
            style="
                max-width: 900px;
                margin: 0 auto;
            "
        >
            <?php echo apply_filters( 'the_content', $product_description ); ?>
        </div>
    </div>

</div>

<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
        // --- Add to Cart Loading Spinner Feedback ---
        const addToCartButton = document.querySelector('.single_add_to_cart_button.button.alt');
        if (addToCartButton) {
            addToCartButton.addEventListener('click', function() {
                if (!addToCartButton.classList.contains('loading')) {
                    addToCartButton.classList.add('loading');
                }
            });

            jQuery(document.body).on('added_to_cart', function() {
                if (addToCartButton.classList.contains('loading')) {
                    addToCartButton.classList.remove('loading');
                }
            });
            jQuery(document.body).on('wc_ajax_fragments_refreshed', function() {
                 if (addToCartButton.classList.contains('loading')) {
                    addToCartButton.classList.remove('loading');
                }
            });
            jQuery(document.body).on('wc_cart_emptied', function() {
                 if (addToCartButton.classList.contains('loading')) {
                    addToCartButton.classList.remove('loading');
                }
            });
            jQuery(document.body).on('wc_cart_button_updated', function() { // Catches updates for simple products
                if (addToCartButton.classList.contains('loading')) {
                    addToCartButton.classList.remove('loading');
                }
            });
            jQuery(document.body).on('click', '.single_add_to_cart_button.button.alt', function() { // Re-apply if user clicks again quickly
                if (!jQuery(this).hasClass('loading')) {
                    jQuery(this).addClass('loading');
                }
            });
        }

        // --- Disable hover zoom on mobile ---
        const productImage = document.querySelector('.product-main-image');
        if (productImage) {
            function toggleHoverZoom() {
                if (window.innerWidth < 768) {
                    productImage.onmouseover = null;
                    productImage.onmouseout = null;
                    productImage.style.transform = 'scale(1)'; // Reset any zoom
                } else {
                    productImage.onmouseover = function() { this.style.transform = 'scale(1.02)'; };
                    productImage.onmouseout = function() { this.style.transform = 'scale(1)'; };
                }
            }
            toggleHoverZoom(); // Initial check
            window.addEventListener('resize', toggleHoverZoom); // Adjust on resize
        }

        // --- Countdown Timer Logic ---
        const countdownElement = document.getElementById('sale-countdown');
        if (countdownElement) {
            const saleEndTime = parseInt(countdownElement.dataset.saleEnd) * 1000; // Convert to milliseconds
            let countdownInterval;

            function updateCountdown() {
                const now = new Date().getTime();
                const distance = saleEndTime - now;

                if (distance < 0) {
                    clearInterval(countdownInterval);
                    countdownElement.innerHTML = 'انتهى العرض!'; // Arabic for "Sale Ended!"
                    countdownElement.style.background = '#888'; // Grey out expired timer
                    return;
                }

                const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                let countdownHTML = '';
                // The order of segments needs to be reversed for RTL visual flow
                countdownHTML += `
                    <div class="countdown-segment"><span class="countdown-value">${String(seconds).padStart(2, '0')}</span><span class="countdown-label">ثانية</span></div> <span class="countdown-separator">:</span>
                    <div class="countdown-segment"><span class="countdown-value">${String(minutes).padStart(2, '0')}</span><span class="countdown-label">دقيقة</span></div> <span class="countdown-separator">:</span>
                    <div class="countdown-segment"><span class="countdown-value">${String(hours).padStart(2, '0')}</span><span class="countdown-label">ساعة</span></div> `;
                if (days > 0) {
                    countdownHTML += `<span class="countdown-separator">:</span><div class="countdown-segment"><span class="countdown-value">${days}</span><span class="countdown-label">أيام</span></div>`; // Days (if > 0)
                }
                // Reverse the order of elements if days exist to keep it logical for RTL
                if (days > 0) {
                    countdownElement.innerHTML = `<span class="countdown-separator">:</span><div class="countdown-segment"><span class="countdown-value">${days}</span><span class="countdown-label">أيام</span></div>` + countdownHTML;
                } else {
                    countdownElement.innerHTML = countdownHTML;
                }
            }

            // Initial call and set interval
            updateCountdown();
            countdownInterval = setInterval(updateCountdown, 1000);
        }
    });
</script>

<?php
/**
 * Hook for WooCommerce after single product content.
 * @hooked woocommerce_output_product_data_tabs - 10 (removed as per request)
 * @hooked woocommerce_upsell_display - 15 (removed as per request)
 * @hooked woocommerce_output_related_products - 20 (removed as per request)
 */
do_action( 'woocommerce_after_single_product' );
?>