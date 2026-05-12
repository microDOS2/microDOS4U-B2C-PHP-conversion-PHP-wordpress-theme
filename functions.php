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

// ============================================
// AUTO-CREATE CUSTOMER ACCOUNT ON CHECKOUT
// ============================================

/**
 * Auto-create WordPress account from checkout billing data.
 * Runs silently after order creation — no extra fields for the customer.
 */
add_action('woocommerce_checkout_order_created', 'microdos4u_auto_create_account');

function microdos4u_auto_create_account($order) {
    // Guard: WooCommerce must be active and order is valid
    if (!function_exists('WC') || !is_object($order) || !($order instanceof WC_Order)) {
        return;
    }

    $order_id = $order->get_id();

    $billing_email = $order->get_billing_email();
    $billing_first = $order->get_billing_first_name();
    $billing_last  = $order->get_billing_last_name();

    // Validate email
    if (empty($billing_email) || !is_email($billing_email)) {
        return;
    }

    // If user is logged in, only bail out if their email matches the billing email
    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        if ($current_user && $current_user->user_email === $billing_email) {
            // Logged-in user matches billing email — just link order
            $order->set_customer_id($current_user->ID);
            $order->save();
            return;
        }
        // Logged in but email doesn't match (deleted account + stale session)
        // Continue to create new account
    }

    // Check if user already exists by email
    $existing_user = get_user_by('email', $billing_email);

    if ($existing_user) {
        // Associate order with existing account
        $order->set_customer_id($existing_user->ID);
        $order->save();

        // If account was just created (within last 2 minutes), show notice on thank-you page
        // This handles cases where WooCommerce's "Create an account?" checkbox created the account
        $user_registered = strtotime($existing_user->user_registered);
        if ((time() - $user_registered) < 120) {
            set_transient('microdos_new_account_' . $order_id, array(
                'email'    => $billing_email,
                'password' => '', // User chose their own password
                'custom_password' => true,
            ), 15 * MINUTE_IN_SECONDS);

            if (WC()->session) {
                WC()->session->set('microdos_new_account_created', true);
                WC()->session->set('microdos_new_account_email', $billing_email);
                WC()->session->set('microdos_new_account_custom', true);
            }
        }

        return;
    }

    // Create new customer account
    $username = sanitize_user(current(explode('@', $billing_email)), true);
    // Ensure unique username
    $original_username = $username;
    $counter = 1;
    while (username_exists($username)) {
        $username = $original_username . $counter;
        $counter++;
    }

    // Generate a readable password (alphanumeric, no special chars)
    $password = wp_generate_password(12, false, false);

    $user_data = array(
        'user_login'   => $username,
        'user_email'   => sanitize_email($billing_email),
        'user_pass'    => $password,
        'first_name'   => sanitize_text_field($billing_first),
        'last_name'    => sanitize_text_field($billing_last),
        'display_name' => sanitize_text_field(trim($billing_first . ' ' . $billing_last)),
        'role'         => 'customer',
    );

    $user_id = wp_insert_user($user_data);

    if (is_wp_error($user_id)) {
        // Silently log error without breaking checkout
        if (defined('WP_DEBUG') && WP_DEBUG) {
            error_log('microDOS4U: Failed to auto-create account for ' . $billing_email . ' - ' . $user_id->get_error_message());
        }
        return;
    }

    // Save billing/shipping address to user meta
    update_user_meta($user_id, 'billing_first_name', sanitize_text_field($billing_first));
    update_user_meta($user_id, 'billing_last_name', sanitize_text_field($billing_last));
    update_user_meta($user_id, 'billing_email', sanitize_email($billing_email));
    update_user_meta($user_id, 'shipping_first_name', sanitize_text_field($billing_first));
    update_user_meta($user_id, 'shipping_last_name', sanitize_text_field($billing_last));

    // Associate order with new user
    $order->set_customer_id($user_id);
    $order->save();

    // Send welcome email with credentials
    microdos4u_send_welcome_email($user_id, $billing_email, $password);

    // Store credentials in transient for thank-you page display (15-minute expiry)
    set_transient('microdos_new_account_' . $order_id, array(
        'email'    => $billing_email,
        'password' => $password,
    ), 15 * MINUTE_IN_SECONDS);

    // Also store in session as fallback
    if (WC()->session) {
        WC()->session->set('microdos_new_account_created', true);
        WC()->session->set('microdos_new_account_email', $billing_email);
        WC()->session->set('microdos_new_account_password', $password);
    }
}

/**
 * Send welcome email with login credentials and password reset link
 */
function microdos4u_send_welcome_email($user_id, $email, $plain_password) {
    $user = get_user_by('id', $user_id);
    if (!$user) {
        return;
    }

    $reset_key = get_password_reset_key($user);
    $reset_url = !is_wp_error($reset_key) && !empty($reset_key)
        ? network_site_url("wp-login.php?action=rp&key=" . rawurlencode($reset_key) . "&login=" . rawurlencode($user->user_login), 'login')
        : '';

    $login_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url();
    $site_name = get_bloginfo('name');

    $subject = sprintf(__('Your %s account is ready', 'microdos4u'), $site_name);

    $message  = sprintf(__('Hi %s,', 'microdos4u'), esc_html($user->display_name)) . "\n\n";
    $message .= sprintf(__('Thank you for your order! We\'ve created an account for you at %s.', 'microdos4u'), $site_name) . "\n\n";
    $message .= __('Your login credentials:', 'microdos4u') . "\n";
    $message .= __('Email:', 'microdos4u') . ' ' . $email . "\n";
    $message .= __('Password:', 'microdos4u') . ' ' . $plain_password . "\n\n";

    if ($reset_url) {
        $message .= __('You can change your password anytime here:', 'microdos4u') . "\n";
        $message .= $reset_url . "\n\n";
    }

    $message .= __('Or log in anytime at:', 'microdos4u') . "\n";
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

/**
 * DEPRECATED: Account notice is now handled directly in the thankyou.php template.
 * This hook is kept empty to prevent conflicts with older code.
 * The template reads the transient and displays the notice with the actual password.
 */
add_action('woocommerce_before_thankyou', 'microdos4u_thankyou_account_notice');

function microdos4u_thankyou_account_notice($order_id) {
    // NOTICE HANDLED IN TEMPLATE: woocommerce/checkout/thankyou.php
    // This function intentionally left empty to prevent fatal errors.
    // The template reads from the transient 'microdos_new_account_{$order_id}'
    // and displays the account creation notice with credentials.
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
// FIX 1: Custom welcome email + disable defaults
// ============================================

add_filter('woocommerce_email_enabled_customer_new_account', '__return_false');
add_filter('wp_new_user_notification_email', 'microdos4u_disable_wp_notification', 10, 3);

function microdos4u_disable_wp_notification($wp_email) {
    $wp_email['to'] = '';
    return $wp_email;
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
// FIX: Hide "Create an account?" checkbox + catch WC-created accounts
// ============================================
add_filter('woocommerce_checkout_fields', 'microdos4u_hide_create_account_checkbox');
function microdos4u_hide_create_account_checkbox($fields) {
    remove_action('woocommerce_before_checkout_registration_form', 'woocommerce_checkout_registration_form', 10);
    add_filter('woocommerce_checkout_registration_enabled', '__return_false');
    return $fields;
}

add_action('woocommerce_created_customer', 'microdos4u_on_wc_created_customer', 10, 3);

function microdos4u_on_wc_created_customer($customer_id, $new_customer_data, $password_generated) {
    $user = get_user_by('id', $customer_id);
    if (!$user || !in_array('customer', (array) $user->roles, true)) {
        return;
    }

    $email = $user->user_email;
    $login_url = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url();
    $site_name = get_bloginfo('name');

    // Store in a transient keyed by email for thank-you page lookup
    set_transient('microdos_wc_created_' . md5($email), array(
        'customer_id' => $customer_id,
        'email'       => $email,
        'timestamp'   => time(),
    ), 15 * MINUTE_IN_SECONDS);

    // Send welcome email with password reset link
    $reset_key = get_password_reset_key($user);
    $reset_url = !is_wp_error($reset_key) && !empty($reset_key)
        ? network_site_url("wp-login.php?action=rp&key=" . rawurlencode($reset_key) . "&login=" . rawurlencode($user->user_login), 'login')
        : '';

    $subject = sprintf(__('Your %s account is ready', 'microdos4u'), $site_name);

    $message  = sprintf(__('Hi %s,', 'microdos4u'), esc_html($user->display_name)) . "\n\n";
    $message .= sprintf(__('Thank you for your order! We\'ve created an account for you at %s.', 'microdos4u'), $site_name) . "\n\n";
    $message .= __('Your login email:', 'microdos4u') . ' ' . $email . "\n\n";

    if ($reset_url) {
        $message .= __('You can set your password here:', 'microdos4u') . "\n";
        $message .= $reset_url . "\n\n";
    }

    $message .= __('Or log in anytime at:', 'microdos4u') . "\n";
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
// EMAIL SETUP NOTE:
// If welcome emails are not being delivered,
// install and configure the 'WP Mail SMTP' plugin.
// This ensures reliable email delivery from SiteGround hosting.
// Plugin: https://wordpress.org/plugins/wp-mail-smtp/
// ============================================
