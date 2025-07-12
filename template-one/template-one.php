<?php
/**
 * Template Name: Template 01
 * Template Post Type: page
 */

get_header();

// Ensure WooCommerce functions are available
if ( ! class_exists( 'WooCommerce' ) ) {
    echo '<p style="text-align: center; color: red; font-weight: bold;">WooCommerce is not active. Please activate WooCommerce to use this template.</p>';
    get_footer();
    return;
}
?>

<div class="hero-section">
    <i class="fas fa-shopping-bag hero-icon icon1"></i>
    <i class="fas fa-box hero-icon icon2"></i>
    <i class="fas fa-credit-card hero-icon icon3"></i>
    <i class="fas fa-receipt hero-icon icon4"></i>

    <h1><?php echo esc_html__( 'اكتشف عروضنا الحصرية', 'your-text-domain' ); ?></h1>
    <p><?php echo esc_html__( 'تسوق أفضل المنتجات بأفضل قيمة وخدمة لا مثيل لها.', 'your-text-domain' ); ?></p>
</div>

<div class="showcase-container" id="product-grid">
    <h2 class="section-title"><?php echo esc_html__( 'منتجاتنا', 'your-text-domain' ); ?></h2>

    <div class="product-grid">
        <?php
        $args = [
            'post_type'      => 'product',
            'posts_per_page' => -1, // Get all products
            'post_status'    => 'publish',
        ];

        $products = new WP_Query( $args );

        if ( $products->have_posts() ) :
            while ( $products->have_posts() ) : $products->the_post();
                global $product;

                if ( ! $product || ! is_a( $product, 'WC_Product' ) ) {
                    continue;
                }

                $product_id        = $product->get_id();
                $product_image_url = wp_get_attachment_image_src( get_post_thumbnail_id( $product_id ), 'full' );
                $product_image_url = $product_image_url ? $product_image_url[0] : wc_placeholder_img_url();
                $product_title     = $product->get_name();
                $product_permalink = get_permalink( $product_id );
                $is_on_sale        = $product->is_on_sale();
                $date_created_timestamp = strtotime( $product->get_date_created() );
                $is_new                 = ( time() - $date_created_timestamp ) < 2592000; // 30 days
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
                            <?php echo str_repeat('&#9733;', 5); ?>
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
            wp_reset_postdata();
        else :
            echo '<p style="text-align: center; width: 100%;">' . esc_html__( 'لم يتم العثور على منتجات.', 'your-text-domain' ) . '</p>';
        endif;
        ?>
    </div>
</div>

<div class="brands-section">
    <div class="showcase-container">
        <h2 class="section-title"><?php echo esc_html__( 'العلامات التجارية', 'your-text-domain' ); ?></h2>
        <?php
        if ( class_exists( 'WC_Brands' ) ) :
            $brands = get_terms( [
                'taxonomy'   => 'product_brand',
                'hide_empty' => false,
            ] );

            if ( ! empty( $brands ) && ! is_wp_error( $brands ) ) :
                ?>
                <div class="brands-grid">
                    <?php foreach ( $brands as $brand ) :
                        $brand_link = get_term_link( $brand->term_id, 'product_brand' );
                        $thumbnail_id = get_term_meta( $brand->term_id, 'thumbnail_id', true );
                        $brand_image_url = $thumbnail_id ? wp_get_attachment_image_url( $thumbnail_id, 'full' ) : 'https://placehold.co/80x80/cccccc/333333?text=Brand';
                        ?>
                        <a href="<?php echo esc_url( $brand_link ); ?>" class="brand-item">
                            <img src="<?php echo esc_url( $brand_image_url ); ?>" alt="<?php echo esc_attr( $brand->name ); ?>" class="brand-icon">
                        </a>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p style="text-align: center;"><?php echo esc_html__( 'لم يتم العثور على علامات تجارية.', 'your-text-domain' ); ?></p>
            <?php endif;
        endif;
        ?>
    </div>
</div>

<div class="services-section">
    <div class="showcase-container">
        <h2 class="section-title"><?php echo esc_html__( 'خدماتنا', 'your-text-domain' ); ?></h2>
        <div class="services-grid">
            <div class="service-item">
                <span class="icon"><i class="fas fa-shipping-fast"></i></span>
                <h3><?php echo esc_html__( 'شحن سريع', 'your-text-domain' ); ?></h3>
                <p><?php echo esc_html__( 'تمتع بتوصيل سريع وموثوق إلى باب منزلك.', 'your-text-domain' ); ?></p>
            </div>
            <div class="service-item">
                <span class="icon"><i class="fas fa-headset"></i></span>
                <h3><?php echo esc_html__( 'دعم 24/7', 'your-text-domain' ); ?></h3>
                <p><?php echo esc_html__( 'فريقنا متوفر دائمًا لمساعدتك ليلًا ونهارًا.', 'your-text-domain' ); ?></p>
            </div>
            <div class="service-item">
                <span class="icon"><i class="fas fa-shield-alt"></i></span>
                <h3><?php echo esc_html__( 'جودة مضمونة', 'your-text-domain' ); ?></h3>
                <p><?php echo esc_html__( 'نضمن جودة منتجاتنا لضمان رضاك التام.', 'your-text-domain' ); ?></p>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();
?>