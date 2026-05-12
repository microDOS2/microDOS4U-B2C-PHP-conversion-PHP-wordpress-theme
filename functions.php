<?php
/**
 * microDOS4U functions and definitions
 *
 * @package microDOS4U
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Theme version
if (!defined('MICRODOS_VERSION')) {
    define('MICRODOS_VERSION', '1.1.0');
}

// ============================================
// THEME SETUP
// ============================================

function microdos4u_setup() {
    add_theme_support('automatic-feed-links');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', array(
        'search-form',
        'comment-form',
        'comment-list',
        'gallery',
        'caption',
        'style',
        'script',
    ));
    add_theme_support('responsive-embeds');
    add_theme_support('editor-styles');
    add_editor_style('style.css');

    // Full WooCommerce support
    add_theme_support('woocommerce', array(
        'thumbnail_image_width' => 400,
        'single_image_width'    => 600,
        'product_grid'          => array(
            'default_columns' => 4,
            'min_columns'     => 1,
            'max_columns'     => 4,
        ),
    ));
    add_theme_support('wc-product-gallery-zoom');
    add_theme_support('wc-product-gallery-lightbox');
    add_theme_support('wc-product-gallery-slider');

    // Register menus
    register_nav_menus(array(
        'primary' => __('Primary Menu', 'microdos4u'),
        'footer'  => __('Footer Menu', 'microdos4u'),
    ));

    // Register WooCommerce widget areas
    register_sidebar(array(
        'name'          => __('WooCommerce Sidebar', 'microdos4u'),
        'id'            => 'woocommerce-sidebar',
        'description'   => __('Widgets for WooCommerce shop and product pages.', 'microdos4u'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('after_setup_theme', 'microdos4u_setup');

// ============================================
// ENQUEUE SCRIPTS AND STYLES
// ============================================

function microdos4u_scripts() {
    wp_enqueue_style(
        'microdos4u-style',
        get_stylesheet_uri(),
        array(),
        MICRODOS_VERSION
    );

    wp_enqueue_style(
        'microdos4u-fonts',
        'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap',
        array(),
        null
    );

    wp_enqueue_script(
        'tailwind-cdn',
        'https://cdn.tailwindcss.com',
        array(),
        null,
        false
    );

    wp_enqueue_script(
        'imask',
        'https://unpkg.com/imask',
        array(),
        null,
        true
    );

    // WooCommerce AJAX cart support
    if (class_exists('WooCommerce')) {
        wp_enqueue_script('wc-add-to-cart');
        wp_enqueue_script('wc-cart-fragments');
    }

    wp_enqueue_script(
        'microdos4u-scripts',
        get_template_directory_uri() . '/js/main.js',
        array('imask'),
        MICRODOS_VERSION,
        true
    );

    // Pass config to JS
    wp_localize_script('microdos4u-scripts', 'microdos4uConfig', array(
        'ajaxUrl'   => admin_url('admin-ajax.php'),
        'themeUrl'  => get_template_directory_uri(),
        'siteUrl'   => home_url(),
        'wcActive'  => class_exists('WooCommerce'),
        'colors'    => array(
            'bgDark'      => get_theme_mod('microdos_bg_dark', '#0a0514'),
            'bgCard'      => get_theme_mod('microdos_bg_card', '#150f24'),
            'brandMicro'  => get_theme_mod('microdos_brand_micro', '#44f80c'),
            'brandDos'    => get_theme_mod('microdos_brand_dos', '#9a02d0'),
            'brandTwo'    => get_theme_mod('microdos_brand_two', '#ff66c4'),
        ),
    ));
}
add_action('wp_enqueue_scripts', 'microdos4u_scripts');

// ============================================
// CUSTOMIZER: COLOR SETTINGS
// ============================================

function microdos4u_customize_register($wp_customize) {
    $wp_customize->add_panel('microdos_colors_panel', array(
        'title'       => __('microDOS4U Colors', 'microdos4u'),
        'description' => __('Customize the color scheme for your site.', 'microdos4u'),
        'priority'    => 30,
    ));

    $wp_customize->add_section('microdos_bg_section', array(
        'title'    => __('Background Colors', 'microdos4u'),
        'panel'    => 'microdos_colors_panel',
        'priority' => 10,
    ));

    $wp_customize->add_setting('microdos_bg_dark', array(
        'default'           => '#0a0514',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_bg_dark', array(
        'label'   => __('Page Background', 'microdos4u'),
        'section' => 'microdos_bg_section',
    )));

    $wp_customize->add_setting('microdos_bg_card', array(
        'default'           => '#150f24',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_bg_card', array(
        'label'   => __('Card / Box Background', 'microdos4u'),
        'section' => 'microdos_bg_section',
    )));

    $wp_customize->add_section('microdos_brand_section', array(
        'title'    => __('Brand Colors', 'microdos4u'),
        'panel'    => 'microdos_colors_panel',
        'priority' => 20,
    ));

    $wp_customize->add_setting('microdos_brand_micro', array(
        'default'           => '#44f80c',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_brand_micro', array(
        'label'   => __('"micro" Color (Green)', 'microdos4u'),
        'section' => 'microdos_brand_section',
    )));

    $wp_customize->add_setting('microdos_brand_dos', array(
        'default'           => '#9a02d0',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_brand_dos', array(
        'label'   => __('"DOS" Color (Purple)', 'microdos4u'),
        'section' => 'microdos_brand_section',
    )));

    $wp_customize->add_setting('microdos_brand_two', array(
        'default'           => '#ff66c4',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_brand_two', array(
        'label'   => __('"(2)" Color (Pink)', 'microdos4u'),
        'section' => 'microdos_brand_section',
    )));

    $wp_customize->add_section('microdos_text_section', array(
        'title'    => __('Text Colors', 'microdos4u'),
        'panel'    => 'microdos_colors_panel',
        'priority' => 30,
    ));

    $wp_customize->add_setting('microdos_text_primary', array(
        'default'           => '#d1d5db',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_text_primary', array(
        'label'   => __('Body Text Color', 'microdos4u'),
        'section' => 'microdos_text_section',
    )));

    $wp_customize->add_setting('microdos_text_heading', array(
        'default'           => '#ffffff',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_text_heading', array(
        'label'   => __('Heading Color', 'microdos4u'),
        'section' => 'microdos_text_section',
    )));

    $wp_customize->add_setting('microdos_text_muted', array(
        'default'           => '#94a3b8',
        'sanitize_callback' => 'sanitize_hex_color',
        'transport'         => 'refresh',
    ));
    $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, 'microdos_text_muted', array(
        'label'   => __('Muted / Secondary Text', 'microdos4u'),
        'section' => 'microdos_text_section',
    )));
}
add_action('customize_register', 'microdos4u_customize_register');

// ============================================
// CUSTOMIZER: LIVE CSS OUTPUT
// ============================================

function microdos4u_customizer_css() {
    $bg_dark      = get_theme_mod('microdos_bg_dark', '#0a0514');
    $bg_card      = get_theme_mod('microdos_bg_card', '#150f24');
    $brand_micro  = get_theme_mod('microdos_brand_micro', '#44f80c');
    $brand_dos    = get_theme_mod('microdos_brand_dos', '#9a02d0');
    $brand_two    = get_theme_mod('microdos_brand_two', '#ff66c4');
    $text_primary = get_theme_mod('microdos_text_primary', '#d1d5db');
    $text_heading = get_theme_mod('microdos_text_heading', '#ffffff');
    $text_muted   = get_theme_mod('microdos_text_muted', '#94a3b8');
    ?>
    <style type="text/css">
        :root {
            --bg-dark: <?php echo esc_attr($bg_dark); ?>;
            --bg-card: <?php echo esc_attr($bg_card); ?>;
            --brand-micro: <?php echo esc_attr($brand_micro); ?>;
            --brand-dos: <?php echo esc_attr($brand_dos); ?>;
            --brand-two: <?php echo esc_attr($brand_two); ?>;
            --text-primary: <?php echo esc_attr($text_primary); ?>;
            --text-heading: <?php echo esc_attr($text_heading); ?>;
            --text-muted: <?php echo esc_attr($text_muted); ?>;
        }
    </style>
    <?php
}
add_action('wp_head', 'microdos4u_customizer_css');

// ============================================
// WIDGET AREAS
// ============================================

function microdos4u_widgets_init() {
    register_sidebar(array(
        'name'          => __('Footer Widget Area', 'microdos4u'),
        'id'            => 'footer-1',
        'description'   => __('Add widgets here to appear in your footer.', 'microdos4u'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));

    register_sidebar(array(
        'name'          => __('WooCommerce Sidebar', 'microdos4u'),
        'id'            => 'woocommerce-sidebar',
        'description'   => __('Widgets for WooCommerce pages.', 'microdos4u'),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<h4 class="widget-title">',
        'after_title'   => '</h4>',
    ));
}
add_action('widgets_init', 'microdos4u_widgets_init');

// ============================================
// WOOCOMMERCE INTEGRATION
// ============================================

// Cart fragments for AJAX cart count
function microdos4u_cart_fragments($fragments) {
    if (function_exists('WC') && WC()->cart) {
        $fragments['span.cart-count'] = '<span class="cart-count">' . WC()->cart->get_cart_contents_count() . '</span>';
    }
    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', 'microdos4u_cart_fragments');

// Loop columns
function microdos4u_loop_columns() {
    return 4;
}
add_filter('loop_shop_columns', 'microdos4u_loop_columns', 20);

// Products per page
function microdos4u_products_per_page() {
    return 12;
}
add_filter('loop_shop_per_page', 'microdos4u_products_per_page', 20);

// Disable WooCommerce default CSS (we'll style everything)
add_filter('woocommerce_enqueue_styles', '__return_empty_array');

// Add "Add to Cart" AJAX class to buttons on homepage
function microdos4u_woo_ajax_add_to_cart() {
    if (!class_exists('WooCommerce')) return;
    wp_enqueue_script('wc-add-to-cart');
}
add_action('wp_enqueue_scripts', 'microdos4u_woo_ajax_add_to_cart');

// ============================================
// WOOCOMMERCE CHECKOUT PAGE SETUP
// ============================================

// Force checkout page to use full-width template (no sidebar)
function microdos4u_checkout_page_template($template) {
    if (is_checkout() || is_cart()) {
        // Remove sidebar on cart/checkout
        remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);
    }
    return $template;
}
add_filter('template_include', 'microdos4u_checkout_page_template');

// ============================================
// HELPER FUNCTIONS
// ============================================

function microdos4u_brand_name($context = 'product') {
    $micro = '<span style="color: var(--brand-micro);">micro</span>';
    $dos   = '<span style="color: var(--brand-dos);">DOS</span>';
    $two   = '<span style="color: var(--brand-two);">(2)</span>';
    if ($context === 'site') {
        return 'microDOS4U';
    }
    return $micro . $dos . $two;
}

function microdos4u_site_brand() {
    return 'microDOS4U';
}

function microdos4u_product_brand() {
    return microdos4u_brand_name('product');
}

// ============================================
// ADMIN DASHBOARD BRANDING
// ============================================

function microdos4u_admin_footer_text($text) {
    return 'Powered by microDOS4U Theme.';
}
add_filter('admin_footer_text', 'microdos4u_admin_footer_text');

// ============================================
// SECURITY / PERFORMANCE
// ============================================

remove_action('wp_head', 'wp_generator');
add_filter('xmlrpc_enabled', '__return_false');
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

// ============================================
// CHECKOUT LEGAL ACKNOWLEDGMENT CHECKBOX
// ============================================

/**
 * Add mandatory legal acknowledgment checkbox to checkout
 * Positioned before the Place Order button
 */
add_action('woocommerce_review_order_before_submit', 'microdos4u_legal_acknowledgment_checkbox', 10);

function microdos4u_legal_acknowledgment_checkbox() {
    $terms_page = get_page_by_path('legal-disclaimer');
    $terms_url = $terms_page ? esc_url(get_permalink($terms_page)) : '#';
    echo '<div class="legal-acknowledgment-wrap" style="margin: 20px 0; padding: 16px; background: #150f24; border: 1px solid #9a02d0; border-radius: 8px;">';
    echo '<label for="legal_acknowledgment" style="display: flex; align-items: flex-start; cursor: pointer;">';
    echo '<input type="checkbox" name="legal_acknowledgment" id="legal_acknowledgment" style="margin-right: 12px; margin-top: 4px; min-width: 18px; min-height: 18px; cursor: pointer;" required />';
    echo '<span style="color: #94a3b8; font-size: 14px; line-height: 1.6;">';
    echo '<strong style="color: #fff;">Check out Acknowledgement:</strong> I certify that I am at least 21 years old and that I am purchasing products from Unique Pharming solely for lawful research, novelty, or collector purposes. I understand that all products are Research Use Only, Not for Human Consumption, not approved for human or animal use, and not intended for medical, therapeutic, dietary, recreational, or diagnostic purposes. I agree to the <a href="' . $terms_url . '" target="_blank" style="color: #38bdf8; text-decoration: underline;">Terms and Conditions</a> and understand that all sales are final.';
    echo '</span>';
    echo '</label>';
    echo '</div>';
}

/**
 * Validate the legal acknowledgment checkbox on checkout
 */
add_action('woocommerce_checkout_process', 'microdos4u_validate_legal_acknowledgment');

function microdos4u_validate_legal_acknowledgment() {
    if (!isset($_POST['legal_acknowledgment']) || empty($_POST['legal_acknowledgment'])) {
        wc_add_notice(__('You must acknowledge the Terms and Conditions and certify that you are purchasing products for lawful research, novelty, or collector purposes.'), 'error');
    }
}

/**
 * Add inline JavaScript to enforce checkbox validation
 */
add_action('wp_footer', 'microdos4u_checkout_checkbox_validation');

function microdos4u_checkout_checkbox_validation() {
    if (!is_checkout()) return;
    $script = "
    document.addEventListener('DOMContentLoaded', function() {
        var checkbox = document.getElementById('legal_acknowledgment');
        var form = document.querySelector('form.woocommerce-checkout');
        if (checkbox && form) {
            form.addEventListener('submit', function(e) {
                if (!checkbox.checked) {
                    e.preventDefault();
                    e.stopPropagation();
                    alert('You must check the legal acknowledgment box to proceed with your order.');
                    checkbox.focus();
                    checkbox.parentElement.style.border = '2px solid #ff4444';
                    checkbox.parentElement.style.borderRadius = '8px';
                    checkbox.parentElement.style.padding = '14px';
                    return false;
                }
            });
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    this.parentElement.style.border = '';
                    this.parentElement.style.padding = '';
                }
            });
        }
    });
    ";
    wp_add_inline_script('jquery', $script);
}

// Force account creation on checkout (required for subscription management)
add_filter('woocommerce_checkout_registration_required', '__return_true');

// Disable WooCommerce's default 'Your account has been created' email
// Our custom welcome_email below handles it with the password included.
add_filter('woocommerce_email_enabled_customer_new_account', '__return_false');

// ============================================
// WELCOME EMAIL FOR NEW ACCOUNTS
// WooCommerce handles account creation natively.
// We just capture the password and send a branded welcome email.
// ============================================

add_action('woocommerce_created_customer', 'microdos4u_welcome_email', 10, 3);

function microdos4u_welcome_email($customer_id, $new_customer_data, $password_generated) {
    $user = get_user_by('id', $customer_id);
    if (!$user || !in_array('customer', (array) $user->roles, true)) {
        return;
    }

    $email = $user->user_email;
    $login_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url();
    $site_name = get_bloginfo('name');

    // Capture the password the user chose during checkout
    $plain_password = '';
    if (!empty($_POST['account_password'])) {
        $plain_password = sanitize_text_field(wp_unslash($_POST['account_password']));
    }

    $subject = sprintf(__('Your %s account is ready', 'microdos4u'), $site_name);

    $message  = sprintf(__('Hi %s,', 'microdos4u'), esc_html($user->display_name)) . "\n\n";
    $message .= sprintf(__('Thank you for your order! We\'ve created an account for you at %s.', 'microdos4u'), $site_name) . "\n\n";
    $message .= __('Your login details:', 'microdos4u') . "\n";
    $message .= __('Email:', 'microdos4u') . ' ' . $email . "\n";

    if (!empty($plain_password)) {
        $message .= __('Password:', 'microdos4u') . ' ' . $plain_password . "\n\n";
        $message .= __('You can change your password anytime in your account settings.', 'microdos4u') . "\n\n";
    } else {
        $reset_key = get_password_reset_key($user);
        if (!is_wp_error($reset_key) && !empty($reset_key)) {
            $reset_url = network_site_url("wp-login.php?action=rp&key=" . rawurlencode($reset_key) . "&login=" . rawurlencode($user->user_login), 'login');
            $message .= __('Set your password here:', 'microdos4u') . "\n";
            $message .= $reset_url . "\n\n";
        }
    }

    $message .= __('Log in anytime at:', 'microdos4u') . "\n";
    $message .= $login_url . "\n\n";
    $message .= __('With your account you can:', 'microdos4u') . "\n";
    $message .= __('- View your order history', 'microdos4u') . "\n";
    $message .= __('- Track your orders', 'microdos4u') . "\n";
    $message .= __('- Manage your subscriptions', 'microdos4u') . "\n";
    $message .= __('- Update your account details', 'microdos4u') . "\n\n";
    $message .= __('If you have any questions, simply reply to this email.', 'microdos4u') . "\n\n";
    $message .= sprintf(__('Thanks,%sThe %s Team', 'microdos4u'), "\n", $site_name);

    $headers = array('Content-Type: text/plain; charset=UTF-8');
    wp_mail($email, $subject, $message, $headers);
}

// ============================================
// PASSWORD HINT + MINIMUM LENGTH ENFORCEMENT
// ============================================

/**
 * Change password hint from "twelve characters" to "six characters"
 */
add_filter('password_hint', function($hint) {
    return str_replace('twelve', 'six', $hint);
});

/**
 * Enforce minimum 6-character password on reset and account update
 */
add_action('validate_password_reset', 'microdos4u_enforce_password_length', 10, 2);
add_action('user_profile_update_errors', 'microdos4u_enforce_password_length_update', 10, 3);

function microdos4u_enforce_password_length($errors, $user) {
    if (!empty($_POST['pass1']) && strlen($_POST['pass1']) < 6) {
        $errors->add('password_too_short', '<strong>ERROR</strong>: Password must be at least 6 characters long.');
    }
    return $errors;
}

function microdos4u_enforce_password_length_update($errors, $update, $user) {
    if (!empty($_POST['pass1']) && strlen($_POST['pass1']) < 6) {
        $errors->add('password_too_short', '<strong>ERROR</strong>: Password must be at least 6 characters long.');
    }
    return $errors;
}



// ============================================
// FIX 6: Change nav label "My Subscription" → "Subscriptions"
// ============================================
add_filter('woocommerce_account_menu_items', 'microdos4u_fix_nav_labels');
function microdos4u_fix_nav_labels($items) {
    if (isset($items['subscriptions'])) {
        $items['subscriptions'] = __('Subscriptions', 'microdos4u');
    }
    return $items;
}


// ============================================
// W-9 / TAX INFORMATION COLLECTION FOR AFFILIATES - v2
// Adds custom fields to AffiliateWP registration form
// Required for 1099 compliance when paying commissions
// ============================================

/**
 * Add W-9/Tax fields to AffiliateWP registration form
 * Uses both hooks for maximum compatibility (shortcode + block editor)
 */
add_action('affwp_register_user_form', 'microdos4u_affiliate_w9_fields');
add_action('affwp_register_form_before_submit', 'microdos4u_affiliate_w9_fields');
function microdos4u_affiliate_w9_fields() {
?>

    <h4 style="color: #94a3b8; margin-top: 1.5rem; margin-bottom: 1rem; border-bottom: 1px solid #1f2b47; padding-bottom: 0.5rem;">Tax Information (Required for 1099)</h4>

    <p class="affwp-w9-note" style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 1rem;">
        The IRS requires us to collect this information to report payments of $600 or more per year. Your information is secure and confidential.
    </p>

    <!-- Full Legal Name -->
    <p>
        <label for="affwp_w9_legal_name" style="color: #94a3b8;">Full Legal Name (as shown on tax return) <span style="color: #ef4444;">*</span></label>
        <input type="text" name="affwp_w9_legal_name" id="affwp_w9_legal_name" class="input" required
               style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; margin-top: 0.25rem;">
    </p>

    <!-- Business Name -->
    <p>
        <label for="affwp_w9_business_name" style="color: #94a3b8;">Business Name (if different from above)</label>
        <input type="text" name="affwp_w9_business_name" id="affwp_w9_business_name" class="input"
               style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; margin-top: 0.25rem;">
    </p>

    <!-- Tax Classification -->
    <p>
        <label for="affwp_w9_tax_classification" style="color: #94a3b8;">Federal Tax Classification <span style="color: #ef4444;">*</span></label>
        <select name="affwp_w9_tax_classification" id="affwp_w9_tax_classification" required
                style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; margin-top: 0.25rem;">
            <option value="">-- Select One --</option>
            <option value="individual">Individual / Sole Proprietor</option>
            <option value="llc">Limited Liability Company (LLC)</option>
            <option value="ccorp">C Corporation</option>
            <option value="scorp">S Corporation</option>
            <option value="partnership">Partnership</option>
        </select>
    </p>

    <!-- Street Address -->
    <p>
        <label for="affwp_w9_address" style="color: #94a3b8;">Street Address <span style="color: #ef4444;">*</span></label>
        <input type="text" name="affwp_w9_address" id="affwp_w9_address" class="input" required
               style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; margin-top: 0.25rem;">
    </p>

    <!-- City -->
    <p>
        <label for="affwp_w9_city" style="color: #94a3b8;">City <span style="color: #ef4444;">*</span></label>
        <input type="text" name="affwp_w9_city" id="affwp_w9_city" class="input" required
               style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; margin-top: 0.25rem;">
    </p>

    <!-- State & ZIP -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
        <p>
            <label for="affwp_w9_state" style="color: #94a3b8;">State <span style="color: #ef4444;">*</span></label>
            <input type="text" name="affwp_w9_state" id="affwp_w9_state" class="input" required maxlength="2"
                   placeholder="CO"
                   style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; margin-top: 0.25rem;">
        </p>
        <p>
            <label for="affwp_w9_zip" style="color: #94a3b8;">ZIP Code <span style="color: #ef4444;">*</span></label>
            <input type="text" name="affwp_w9_zip" id="affwp_w9_zip" class="input" required maxlength="10"
                   placeholder="80004"
                   style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; margin-top: 0.25rem;">
        </p>
    </div>

    <!-- SSN or EIN -->
    <p>
        <label for="affwp_w9_tax_id" style="color: #94a3b8;">Social Security Number (SSN) or Employer Identification Number (EIN) <span style="color: #ef4444;">*</span></label>
        <input type="text" name="affwp_w9_tax_id" id="affwp_w9_tax_id" class="input" required maxlength="11"
               placeholder="123-45-6789 or 12-3456789"
               style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; margin-top: 0.25rem;">
        <span style="color: #64748b; font-size: 0.75rem; display: block; margin-top: 0.25rem;">This is required for 1099 tax reporting. Format: XXX-XX-XXXX or XX-XXXXXXX</span>
    </p>

    <!-- W-9 Certification -->
    <div style="margin-top: 1.5rem; padding: 1rem; border: 1px solid #1f2b47; border-radius: 0.5rem; background-color: #150f24;">
        <p style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 1rem;">
            <strong style="color: #e2e8f0;">Certification</strong> — Under penalties of perjury, I certify that:
        </p>
        <ol style="color: #94a3b8; font-size: 0.75rem; margin-left: 1.25rem; margin-bottom: 1rem;">
            <li>The number shown on this form is my correct taxpayer identification number (or I am waiting for a number to be issued to me), and</li>
            <li>I am not subject to backup withholding because: (a) I am exempt from backup withholding, or (b) I have not been notified by the IRS that I am subject to backup withholding, and</li>
            <li>I am a U.S. citizen or other U.S. person, and</li>
            <li>The FATCA code(s) entered on this form (if any) indicating that I am exempt from FATCA reporting is correct.</li>
        </ol>
        <p>
            <label style="color: #94a3b8; display: flex; align-items: flex-start; gap: 0.5rem; cursor: pointer;">
                <input type="checkbox" name="affwp_w9_certification" id="affwp_w9_certification" value="1" required
                       style="margin-top: 0.125rem;">
                <span>I agree to the above certification and understand this is the same as my electronic signature on an IRS Form W-9. <span style="color: #ef4444;">*</span></span>
            </label>
        </p>
    </div>

<?php
}

/**
 * Validate W-9 fields during registration
 */
add_filter('affwp_process_register_form', 'microdos4u_validate_w9_fields', 10, 2);
function microdos4u_validate_w9_fields($errors, $sanitized_user) {
    $required_fields = array(
        'affwp_w9_legal_name'       => 'Full Legal Name',
        'affwp_w9_tax_classification' => 'Federal Tax Classification',
        'affwp_w9_address'          => 'Street Address',
        'affwp_w9_city'             => 'City',
        'affwp_w9_state'            => 'State',
        'affwp_w9_zip'              => 'ZIP Code',
        'affwp_w9_tax_id'           => 'SSN or EIN',
    );

    foreach ($required_fields as $field => $label) {
        if (empty($_POST[$field])) {
            $errors[] = $label . ' is required for tax reporting.';
        }
    }

    if (empty($_POST['affwp_w9_certification'])) {
        $errors[] = 'You must agree to the W-9 certification to register.';
    }

    return $errors;
}

/**
 * Save W-9 fields when affiliate is created
 */
add_action('affwp_insert_affiliate', 'microdos4u_save_w9_fields', 10, 2);
function microdos4u_save_w9_fields($affiliate_id, $data) {
    $w9_fields = array(
        'affwp_w9_legal_name',
        'affwp_w9_business_name',
        'affwp_w9_tax_classification',
        'affwp_w9_address',
        'affwp_w9_city',
        'affwp_w9_state',
        'affwp_w9_zip',
        'affwp_w9_tax_id',
    );

    foreach ($w9_fields as $field) {
        if (!empty($_POST[$field])) {
            update_user_meta($data['user_id'], $field, sanitize_text_field($_POST[$field]));
        }
    }

    update_user_meta($data['user_id'], 'affwp_w9_certification', '1');
}

/**
 * Show W-9 fields in admin affiliate profile
 */
add_action('affwp_affiliate_admin_profile_info', 'microdos4u_show_w9_in_admin');
function microdos4u_show_w9_in_admin($affiliate) {
    $user_id = $affiliate->user_id;
    $w9_fields = array(
        'affwp_w9_legal_name'       => 'Full Legal Name',
        'affwp_w9_business_name'    => 'Business Name',
        'affwp_w9_tax_classification' => 'Tax Classification',
        'affwp_w9_address'          => 'Street Address',
        'affwp_w9_city'             => 'City',
        'affwp_w9_state'            => 'State',
        'affwp_w9_zip'              => 'ZIP Code',
        'affwp_w9_tax_id'           => 'SSN/EIN',
    );

    echo '<div style="margin-top: 20px; padding: 15px; background: #f0f0f1; border: 1px solid #c3c4c7; border-radius: 4px;">';
    echo '<h3 style="margin-top: 0;">W-9 / Tax Information</h3>';
    echo '<table class="form-table">';

    foreach ($w9_fields as $meta_key => $label) {
        $value = get_user_meta($user_id, $meta_key, true);
        echo '<tr>';
        echo '<th style="width: 200px;">' . esc_html($label) . '</th>';
        echo '<td>' . esc_html($value) . '</td>';
        echo '</tr>';
    }

    $cert = get_user_meta($user_id, 'affwp_w9_certification', true);
    echo '<tr>';
    echo '<th>W-9 Certification</th>';
    echo '<td>' . ($cert ? '<span style="color: green;">✓ Agreed</span>' : '<span style="color: red;">✗ Not agreed</span>') . '</td>';
    echo '</tr>';

    echo '</table>';
    echo '</div>';
}


// ============================================
// EMAIL SETUP NOTE:
// If welcome emails are not being delivered,
// install and configure the 'WP Mail SMTP' plugin.
// This ensures reliable email delivery from SiteGround hosting.
// Plugin: https://wordpress.org/plugins/wp-mail-smtp/
// ============================================
