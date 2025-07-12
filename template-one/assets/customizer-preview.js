(function ($) {
    wp.customize.bind('ready', function () {

        // Hero Section Visibility
        wp.customize('template_one_hero_hide', function (value) {
            value.bind(function (new_val) {
                if (new_val) {
                    $('.hero-section').hide();
                } else {
                    $('.hero-section').show();
                }
            });
        });

        // Hero Heading Text
        wp.customize('template_one_hero_heading_text', function (value) {
            value.bind(function (new_val) {
                $('.hero-section h1').text(new_val);
            });
        });

        // Hero Paragraph Text
        wp.customize('template_one_hero_paragraph_text', function (value) {
            value.bind(function (new_val) {
                $('.hero-section p').text(new_val);
            });
        });

        // Hero Button Text
        wp.customize('template_one_hero_button_text', function (value) {
            value.bind(function (new_val) {
                $('.hero-section .hero-button').text(new_val);
            });
        });

        // Hero Button Background Color
        wp.customize('template_one_hero_button_bg_color', function (value) {
            value.bind(function (new_val) {
                $('.hero-section .hero-button').css('background-color', new_val);
            });
        });

        // Hero Button Text Color
        wp.customize('template_one_hero_button_text_color', function (value) {
            value.bind(function (new_val) {
                $('.hero-section .hero-button').css('color', new_val);
            });
        });

        // Hero Background Primary Color (for gradient)
        wp.customize('template_one_hero_bg_primary_color', function (value) {
            value.bind(function (new_val) {
                var secondaryColor = wp.customize('template_one_hero_bg_secondary_color').get();
                $('.hero-section').css('background-image', 'linear-gradient(to right, ' + new_val + ', ' + secondaryColor + ')');
            });
        });

        // Hero Background Secondary Color (for gradient)
        wp.customize('template_one_hero_bg_secondary_color', function (value) {
            value.bind(function (new_val) {
                var primaryColor = wp.customize('template_one_hero_bg_primary_color').get();
                $('.hero-section').css('background-image', 'linear-gradient(to right, ' + primaryColor + ', ' + new_val + ')');
            });
        });

        // Hero Button Hover Background Color
        wp.customize('template_one_hero_button_hover_bg_color', function (value) {
            value.bind(function (new_val) {
                var style = $('style#template_one_hero_button_hover_style');
                if (!style.length) {
                    style = $('<style id="template_one_hero_button_hover_style"></style>').appendTo('head');
                }
                var currentTextColor = wp.customize('template_one_hero_button_hover_text_color').get();
                style.html('.hero-section .hero-button:hover { background-color: ' + new_val + ' !important; color: ' + currentTextColor + ' !important; }');
            });
        });

        // Hero Button Hover Text Color
        wp.customize('template_one_hero_button_hover_text_color', function (value) {
            value.bind(function (new_val) {
                var style = $('style#template_one_hero_button_hover_style');
                if (!style.length) {
                    style = $('<style id="template_one_hero_button_hover_style"></style>').appendTo('head');
                }
                var currentBgColor = wp.customize('template_one_hero_button_hover_bg_color').get();
                style.html('.hero-section .hero-button:hover { background-color: ' + currentBgColor + ' !important; color: ' + new_val + ' !important; }');
            });
        });
    });
})(jQuery);