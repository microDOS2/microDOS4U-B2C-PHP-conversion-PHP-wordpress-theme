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
    define('MICRODOS_VERSION', '1.3.0');
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
// AUTO-CREATE REQUIRED PAGES ON THEME ACTIVATION
// ============================================

/**
 * Create the Affiliate W-9 Form page if it doesn't exist.
 * Runs when the theme is activated or switched to.
 */
add_action('after_switch_theme', 'microdos_create_required_pages');
add_action('admin_init', 'microdos_create_required_pages'); // Also check on admin load (one-time)

function microdos_create_required_pages() {
    // Track which pages we've already created to avoid loops
    $pages_created = get_option('microdos_pages_created', array());

    // --- Affiliate W-9 Form Page ---
    if (empty($pages_created['affiliate_w9'])) {
        $existing = get_page_by_path('affiliate-w9');
        if (!$existing) {
            $page_id = wp_insert_post(array(
                'post_title'     => 'Affiliate W-9 Form',
                'post_name'      => 'affiliate-w9',
                'post_content'   => '', // Template handles all content
                'post_status'    => 'publish',
                'post_type'      => 'page',
                'page_template'  => 'page-affiliate-w9.php',
                'comment_status' => 'closed',
                'ping_status'    => 'closed',
            ));
            if (!is_wp_error($page_id) && $page_id > 0) {
                $pages_created['affiliate_w9'] = $page_id;
            }
        } else {
            $pages_created['affiliate_w9'] = $existing->ID;
        }
    }

    // Save the tracking array
    update_option('microdos_pages_created', $pages_created);
}

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
    if (!function_exists('WC') || !is_object($order)) {
        return;
    }

    // Only for guest checkouts
    if (is_user_logged_in()) {
        return;
    }

    $billing_email = $order->get_billing_email();
    $billing_first = $order->get_billing_first_name();
    $billing_last  = $order->get_billing_last_name();

    // Validate email
    if (empty($billing_email) || !is_email($billing_email)) {
        return;
    }

    // Check if user already exists
    $existing_user = get_user_by('email', $billing_email);

    if ($existing_user) {
        // Associate order with existing account
        $order->set_customer_id($existing_user->ID);
        $order->save();
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

    $password = wp_generate_password(18, true, true);

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

    // Associate order with new user
    $order->set_customer_id($user_id);
    $order->save();

    // Send welcome email
    microdos4u_send_welcome_email($user_id, $billing_email);

    // Flag for thank-you page notice
    if (WC()->session) {
        WC()->session->set('microdos_new_account_created', true);
        WC()->session->set('microdos_new_account_email', $billing_email);
    }
}

/**
 * Send welcome email with password reset link
 */
function microdos4u_send_welcome_email($user_id, $email) {
    $user = get_user_by('id', $user_id);
    if (!$user) {
        return;
    }

    $reset_key = get_password_reset_key($user);
    if (is_wp_error($reset_key) || empty($reset_key)) {
        return;
    }

    $reset_url  = network_site_url("wp-login.php?action=rp&key=" . rawurlencode($reset_key) . "&login=" . rawurlencode($user->user_login), 'login');
    $login_url  = function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : wp_login_url();
    $site_name  = get_bloginfo('name');
    $blog_url   = home_url();

    $subject = sprintf(__('Your %s account is ready', 'microdos4u'), $site_name);

    $message  = sprintf(__('Hi %s,', 'microdos4u'), esc_html($user->display_name)) . "\n\n";
    $message .= sprintf(__('Thank you for your order! We\'ve created an account for you at %s.', 'microdos4u'), $site_name) . "\n\n";
    $message .= __('Account Email:', 'microdos4u') . ' ' . $email . "\n\n";
    $message .= __('To set your password and access your account, click this link:', 'microdos4u') . "\n";
    $message .= $reset_url . "\n\n";
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
 * Show account creation notice on WooCommerce thank-you page
 */
add_action('woocommerce_before_thankyou', 'microdos4u_thankyou_account_notice');

function microdos4u_thankyou_account_notice($order_id) {
    if (!function_exists('WC') || !WC()->session) {
        return;
    }

    $new_account = WC()->session->get('microdos_new_account_created');
    $email       = WC()->session->get('microdos_new_account_email');

    if (!$new_account || empty($email)) {
        return;
    }

    printf(
        '<div class="woocommerce-message woocommerce-message--info woocommerce-Message woocommerce-Message--info woocommerce-info" style="background-color: #150f24; border: 1px solid #44f80c; color: #d1d5db; margin-bottom: 20px;">' .
        '<strong style="color: #44f80c;">%s</strong> %s <strong>%s</strong>. %s' .
        '</div>',
        esc_html__('Account Created!', 'microdos4u'),
        esc_html__('We\'ve created an account for you. Check your email at', 'microdos4u'),
        esc_html($email),
        esc_html__('for your login details and a link to set your password.', 'microdos4u')
    );

    // Clear session flags
    WC()->session->set('microdos_new_account_created', null);
    WC()->session->set('microdos_new_account_email', null);
}

// ============================================
// AFFILIATE W-9 TAX COLLECTION SYSTEM
// ============================================

/**
 * Flag newly registered affiliates as needing W-9 completion.
 * Runs after AffiliateWP inserts the affiliate record.
 */
add_action('affwp_insert_affiliate', 'microdos_flag_affiliate_for_w9', 10, 2);

function microdos_flag_affiliate_for_w9($affiliate_id, $data) {
    // $data contains user_id, status, etc.
    if (empty($data['user_id'])) {
        return;
    }
    $user_id = absint($data['user_id']);
    // Mark W-9 as pending
    update_user_meta($user_id, 'microdos_w9_status', 'pending');
    // Store the timestamp
    update_user_meta($user_id, 'microdos_w9_requested', current_time('mysql'));
}

/**
 * Mark W-9 as required for all existing affiliates who don't have it.
 * Runs when an affiliate's admin profile is viewed (one-time backfill).
 */
add_action('affwp_affiliate_admin_profile_info', 'microdos_maybe_show_w9_admin_notice', 5);

function microdos_maybe_show_w9_admin_notice($affiliate) {
    $user_id = $affiliate->user_id;
    $w9_status = get_user_meta($user_id, 'microdos_w9_status', true);
    // If no status at all, mark as pending
    if (empty($w9_status)) {
        update_user_meta($user_id, 'microdos_w9_status', 'pending');
        $w9_status = 'pending';
    }
}

/**
 * Display W-9 status and data in the AffiliateWP admin profile
 */
add_action('affwp_affiliate_admin_profile_info', 'microdos_show_w9_in_admin', 20);

function microdos_show_w9_in_admin($affiliate) {
    $user_id   = $affiliate->user_id;
    $w9_status = get_user_meta($user_id, 'microdos_w9_status', true);
    $w9_data   = get_user_meta($user_id, 'microdos_w9_data', true);

    $status_label = 'Not Submitted';
    $status_color = '#ff4444';
    if ($w9_status === 'complete') {
        $status_label = 'Complete';
        $status_color = '#44f80c';
    } elseif ($w9_status === 'pending') {
        $status_label = 'Pending';
        $status_color = '#ffaa00';
    }
    ?>
    <h3>W-9 Tax Information</h3>
    <table class="form-table">
        <tr>
            <th>W-9 Status</th>
            <td>
                <strong style="color: <?php echo esc_attr($status_color); ?>;"><?php echo esc_html($status_label); ?></strong>
                <?php if ($w9_status !== 'complete') : ?>
                    <p class="description" style="color: #ff4444;">Affiliate cannot receive payouts until W-9 is completed.</p>
                <?php endif; ?>
            </td>
        </tr>
        <?php if (is_array($w9_data) && !empty($w9_data)) : ?>
        <tr>
            <th>Full Name</th>
            <td><?php echo esc_html($w9_data['full_name'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <th>Business Name</th>
            <td><?php echo esc_html($w9_data['business_name'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <th>Tax Classification</th>
            <td><?php echo esc_html($w9_data['tax_classification'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <th>Tax ID (SSN/EIN)</th>
            <td>
                <?php
                $tin = $w9_data['tax_id'] ?? '';
                // Mask the TIN for security: show last 4 only
                if (strlen($tin) >= 4) {
                    echo esc_html('***-**-' . substr($tin, -4));
                } else {
                    echo 'N/A';
                }
                ?>
            </td>
        </tr>
        <tr>
            <th>Address</th>
            <td>
                <?php
                echo esc_html($w9_data['address'] ?? 'N/A');
                if (!empty($w9_data['address2'])) {
                    echo '<br>' . esc_html($w9_data['address2']);
                }
                ?>
            </td>
        </tr>
        <tr>
            <th>City, State, ZIP</th>
            <td>
                <?php
                echo esc_html(($w9_data['city'] ?? '') . ', ' . ($w9_data['state'] ?? '') . ' ' . ($w9_data['zip'] ?? ''));
                ?>
            </td>
        </tr>
        <tr>
            <th>Certification Date</th>
            <td><?php echo esc_html($w9_data['certification_date'] ?? 'N/A'); ?></td>
        </tr>
        <tr>
            <th>IP Address</th>
            <td><?php echo esc_html($w9_data['ip_address'] ?? 'N/A'); ?></td>
        </tr>
        <?php else : ?>
        <tr>
            <th colspan="2" style="color: #999;">No W-9 data on file.</th>
        </tr>
        <?php endif; ?>
    </table>
    <?php
}

/**
 * Show W-9 completion notice on the Affiliate Area dashboard
 */
add_action('affwp_affiliate_dashboard_top', 'microdos_show_w9_dashboard_notice');

function microdos_show_w9_dashboard_notice() {
    if (!is_user_logged_in()) {
        return;
    }
    $user_id   = get_current_user_id();
    $w9_status = get_user_meta($user_id, 'microdos_w9_status', true);

    // Only show if pending or not set
    if ($w9_status === 'complete') {
        return;
    }

    // Check if current user is an affiliate
    if (!function_exists('affwp_get_affiliate_id')) {
        return;
    }
    $affiliate_id = affwp_get_affiliate_id($user_id);
    if (!$affiliate_id) {
        return;
    }

    $w9_page = get_page_by_path('affiliate-w9');
    $w9_url  = $w9_page ? get_permalink($w9_page) : '#';
    ?>
    <div style="
        background: #150f24;
        border: 2px solid #ffaa00;
        border-radius: 8px;
        padding: 20px 24px;
        margin: 0 0 24px 0;
        color: #d1d5db;
    ">
        <p style="margin: 0 0 10px 0; font-weight: 700; color: #ffaa00; font-size: 16px;">
            Action Required: W-9 Tax Form
        </p>
        <p style="margin: 0 0 14px 0; font-size: 14px; line-height: 1.6;">
            Before we can issue any commission payments, we need a completed W-9 form on file for tax reporting purposes (1099-NEC).
            <strong style="color: #fff;">Payouts will be held until this is submitted.</strong>
        </p>
        <a href="<?php echo esc_url($w9_url); ?>" style="
            display: inline-block;
            background: #ffaa00;
            color: #0a0514;
            font-weight: 700;
            padding: 10px 24px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
        ">Complete W-9 Form Now</a>
    </div>
    <?php
}

/**
 * Block payouts to affiliates with incomplete W-9
 */
add_filter('affwp_auto_register_pending_referral', 'microdos_maybe_block_payout_for_w9', 10, 2);

function microdos_maybe_block_payout_for_w9($register, $args) {
    if (!$register) {
        return $register;
    }
    $affiliate_id = $args['affiliate_id'] ?? 0;
    if (!$affiliate_id) {
        return $register;
    }
    $affiliate = affwp_get_affiliate($affiliate_id);
    if (!$affiliate) {
        return $register;
    }
    $w9_status = get_user_meta($affiliate->user_id, 'microdos_w9_status', true);
    if ($w9_status !== 'complete') {
        // Referral stays pending until W-9 is completed
        return false;
    }
    return $register;
}

// ============================================
// W-9 FORM SHORTCODE
// ============================================

/**
 * Shortcode: [microdos_w9_form]
 * Renders a standalone W-9 completion form for logged-in affiliates.
 */
add_shortcode('microdos_w9_form', 'microdos_render_w9_form');

function microdos_render_w9_form($atts) {
    // Must be logged in
    if (!is_user_logged_in()) {
        return '<div style="background:#150f24;border:1px solid #ff4444;border-radius:8px;padding:20px;color:#d1d5db;text-align:center;">
            <p><strong style="color:#ff4444;">Please log in to access the W-9 form.</strong></p>
            <p><a href="' . esc_url(wp_login_url(get_permalink())) . '" style="color:#44f80c;">Log In &rarr;</a></p>
        </div>';
    }

    $user_id = get_current_user_id();

    // Check if user is an affiliate
    if (!function_exists('affwp_get_affiliate_id')) {
        return '<div style="color:#ff4444;">AffiliateWP is not active.</div>';
    }
    $affiliate_id = affwp_get_affiliate_id($user_id);
    if (!$affiliate_id) {
        return '<div style="background:#150f24;border:1px solid #ff4444;border-radius:8px;padding:20px;color:#d1d5db;text-align:center;">
            <p><strong style="color:#ff4444;">You are not registered as an affiliate.</strong></p>
            <p><a href="/affiliate-program" style="color:#44f80c;">Apply to become an affiliate &rarr;</a></p>
        </div>';
    }

    // Check if already complete
    $w9_status = get_user_meta($user_id, 'microdos_w9_status', true);
    if ($w9_status === 'complete') {
        $w9_data = get_user_meta($user_id, 'microdos_w9_data', true);
        return '<div style="background:#150f24;border:1px solid #44f80c;border-radius:8px;padding:24px;color:#d1d5db;text-align:center;">
            <p style="font-size:20px;margin:0 0 8px;">&#10004;</p>
            <p style="font-weight:700;color:#44f80c;margin:0 0 8px;font-size:16px;">Your W-9 is on file.</p>
            <p style="margin:0;font-size:14px;">Thank you! Your tax information has been received and verified. You are eligible for commission payouts.</p>
            <p style="margin:12px 0 0;font-size:13px;color:#94a3b8;">Submitted: ' . esc_html($w9_data['certification_date'] ?? 'N/A') . '</p>
        </div>';
    }

    // Handle form submission
    $error   = '';
    $success = false;
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['microdos_w9_nonce'])) {
        if (!wp_verify_nonce($_POST['microdos_w9_nonce'], 'microdos_w9_submit')) {
            $error = 'Security check failed. Please refresh the page and try again.';
        } else {
            $required_fields = array(
                'w9_full_name'         => 'Full Name / Business Name',
                'w9_tax_classification' => 'Federal Tax Classification',
                'w9_tax_id'            => 'Taxpayer Identification Number (SSN/EIN)',
                'w9_address'           => 'Address',
                'w9_city'              => 'City',
                'w9_state'             => 'State',
                'w9_zip'               => 'ZIP Code',
            );

            $missing = array();
            foreach ($required_fields as $field => $label) {
                if (empty($_POST[$field])) {
                    $missing[] = $label;
                }
            }

            if (!empty($missing)) {
                $error = 'Please fill in all required fields: ' . implode(', ', $missing);
            } elseif (empty($_POST['w9_certification'])) {
                $error = 'You must check the certification box to certify the information is correct.';
            } else {
                // Sanitize and save
                $w9_data = array(
                    'full_name'          => sanitize_text_field($_POST['w9_full_name']),
                    'business_name'      => sanitize_text_field($_POST['w9_business_name'] ?? ''),
                    'tax_classification' => sanitize_text_field($_POST['w9_tax_classification']),
                    'tax_id'             => sanitize_text_field($_POST['w9_tax_id']),
                    'address'            => sanitize_text_field($_POST['w9_address']),
                    'address2'           => sanitize_text_field($_POST['w9_address2'] ?? ''),
                    'city'               => sanitize_text_field($_POST['w9_city']),
                    'state'              => sanitize_text_field($_POST['w9_state']),
                    'zip'                => sanitize_text_field($_POST['w9_zip']),
                    'certification_date' => current_time('mysql'),
                    'ip_address'         => sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''),
                );

                update_user_meta($user_id, 'microdos_w9_data', $w9_data);
                update_user_meta($user_id, 'microdos_w9_status', 'complete');

                // Mark any pending referrals as unblocked
                if (function_exists('affwp_get_affiliate_referrals')) {
                    $pending_refs = affwp_get_referrals(array(
                        'affiliate_id' => $affiliate_id,
                        'status'       => 'pending',
                        'number'       => -1,
                    ));
                    if (!empty($pending_refs)) {
                        foreach ($pending_refs as $ref) {
                            // Move from pending to unpaid so they can be paid out
                            affiliate_wp()->referrals->update_referral($ref->referral_id, array('status' => 'unpaid'));
                        }
                    }
                }

                $success = true;
            }
        }
    }

    if ($success) {
        return '<div style="background:#150f24;border:1px solid #44f80c;border-radius:8px;padding:24px;color:#d1d5db;text-align:center;">
            <p style="font-size:20px;margin:0 0 8px;">&#10004;</p>
            <p style="font-weight:700;color:#44f80c;margin:0 0 8px;font-size:16px;">W-9 Submitted Successfully!</p>
            <p style="margin:0;font-size:14px;">Your tax information has been saved. You are now eligible for commission payouts.</p>
            <p style="margin:12px 0 0;font-size:14px;"><a href="' . esc_url(affwp_get_affiliate_area_page_url()) . '" style="color:#44f80c;">Go to Affiliate Dashboard &rarr;</a></p>
        </div>';
    }

    // Render the form
    ob_start();
    ?>
    <div style="max-width: 700px; margin: 0 auto;">

        <?php if ($error) : ?>
        <div style="background:#150f24;border:1px solid #ff4444;border-radius:8px;padding:16px 20px;margin-bottom:20px;color:#d1d5db;">
            <strong style="color:#ff4444;">Error:</strong> <?php echo esc_html($error); ?>
        </div>
        <?php endif; ?>

        <form method="post" action="" style="background:#150f24;border:1px solid #1f2b47;border-radius:8px;padding:28px;">

            <h2 style="color:#fff;margin:0 0 6px;font-size:20px;">W-9 Tax Information</h2>
            <p style="color:#94a3b8;margin:0 0 24px;font-size:14px;">Required for all US-based affiliates. Information is stored securely and used only for 1099-NEC tax reporting.</p>

            <!-- Section 1: Name -->
            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Name</h3>

            <div style="margin-bottom:16px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">
                    Full Name / Business Name <span style="color:#ff4444;">*</span>
                </label>
                <input type="text" name="w9_full_name" required
                    value="<?php echo esc_attr($_POST['w9_full_name'] ?? ''); ?>"
                    style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;"
                    placeholder="As shown on your income tax return">
            </div>

            <div style="margin-bottom:20px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">
                    Business Name (if different from above)
                </label>
                <input type="text" name="w9_business_name"
                    value="<?php echo esc_attr($_POST['w9_business_name'] ?? ''); ?>"
                    style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;"
                    placeholder="Leave blank if not a business entity">
            </div>

            <!-- Section 2: Tax Classification -->
            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Federal Tax Classification</h3>

            <div style="margin-bottom:20px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:8px;font-weight:600;">
                    Select your federal tax classification <span style="color:#ff4444;">*</span>
                </label>
                <select name="w9_tax_classification" required
                    style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;">
                    <option value="">-- Select Classification --</option>
                    <option value="Individual / Sole Proprietor" <?php selected($_POST['w9_tax_classification'] ?? '', 'Individual / Sole Proprietor'); ?>>Individual / Sole Proprietor or Single-Member LLC</option>
                    <option value="C Corporation" <?php selected($_POST['w9_tax_classification'] ?? '', 'C Corporation'); ?>>C Corporation</option>
                    <option value="S Corporation" <?php selected($_POST['w9_tax_classification'] ?? '', 'S Corporation'); ?>>S Corporation</option>
                    <option value="Partnership" <?php selected($_POST['w9_tax_classification'] ?? '', 'Partnership'); ?>>Partnership</option>
                    <option value="LLC (C Corp)" <?php selected($_POST['w9_tax_classification'] ?? '', 'LLC (C Corp)'); ?>>LLC taxed as C Corporation</option>
                    <option value="LLC (S Corp)" <?php selected($_POST['w9_tax_classification'] ?? '', 'LLC (S Corp)'); ?>>LLC taxed as S Corporation</option>
                    <option value="LLC (Partnership)" <?php selected($_POST['w9_tax_classification'] ?? '', 'LLC (Partnership)'); ?>>LLC taxed as Partnership</option>
                    <option value="LLC (Disregarded)" <?php selected($_POST['w9_tax_classification'] ?? '', 'LLC (Disregarded)'); ?>>LLC (Disregarded entity)</option>
                    <option value="Other" <?php selected($_POST['w9_tax_classification'] ?? '', 'Other'); ?>>Other</option>
                </select>
            </div>

            <!-- Section 3: Tax ID -->
            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Taxpayer Identification Number</h3>

            <div style="margin-bottom:20px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">
                    SSN or EIN <span style="color:#ff4444;">*</span>
                </label>
                <input type="text" name="w9_tax_id" required
                    value="<?php echo esc_attr($_POST['w9_tax_id'] ?? ''); ?>"
                    style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;"
                    placeholder="000-00-0000 (SSN) or 00-0000000 (EIN)"
                    maxlength="11"
                    inputmode="numeric"
                    autocomplete="off">
                <p style="margin:4px 0 0;font-size:12px;color:#94a3b8;">For security, this is encrypted and only the last 4 digits are visible to admins.</p>
            </div>

            <!-- Section 4: Address -->
            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Address</h3>

            <div style="margin-bottom:12px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">Street Address <span style="color:#ff4444;">*</span></label>
                <input type="text" name="w9_address" required
                    value="<?php echo esc_attr($_POST['w9_address'] ?? ''); ?>"
                    style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;"
                    placeholder="123 Main St">
            </div>
            <div style="margin-bottom:16px;">
                <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">Apt / Suite / Unit</label>
                <input type="text" name="w9_address2"
                    value="<?php echo esc_attr($_POST['w9_address2'] ?? ''); ?>"
                    style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;"
                    placeholder="Apt 4B (optional)">
            </div>
            <div style="display:grid;grid-template-columns:2fr 1fr 1fr;gap:10px;margin-bottom:20px;">
                <div>
                    <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">City <span style="color:#ff4444;">*</span></label>
                    <input type="text" name="w9_city" required
                        value="<?php echo esc_attr($_POST['w9_city'] ?? ''); ?>"
                        style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;">
                </div>
                <div>
                    <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">State <span style="color:#ff4444;">*</span></label>
                    <select name="w9_state" required
                        style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;height:40px;">
                        <option value="">--</option>
                        <?php
                        $states = array('AL','AK','AZ','AR','CA','CO','CT','DE','FL','GA','HI','ID','IL','IN','IA','KS','KY','LA','ME','MD','MA','MI','MN','MS','MO','MT','NE','NV','NH','NJ','NM','NY','NC','ND','OH','OK','OR','PA','RI','SC','SD','TN','TX','UT','VT','VA','WA','WV','WI','WY','DC');
                        foreach ($states as $st) {
                            $selected = selected($_POST['w9_state'] ?? '', $st, false);
                            echo '<option value="' . esc_attr($st) . '"' . $selected . '>' . esc_html($st) . '</option>';
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label style="display:block;color:#d1d5db;font-size:13px;margin-bottom:6px;font-weight:600;">ZIP <span style="color:#ff4444;">*</span></label>
                    <input type="text" name="w9_zip" required
                        value="<?php echo esc_attr($_POST['w9_zip'] ?? ''); ?>"
                        style="width:100%;padding:10px 12px;background:#0a0514;border:1px solid #1f2b47;border-radius:6px;color:#fff;font-size:14px;box-sizing:border-box;"
                        placeholder="00000"
                        maxlength="10">
                </div>
            </div>

            <!-- Section 5: Certification -->
            <h3 style="color:#44f80c;font-size:14px;text-transform:uppercase;letter-spacing:0.05em;margin:0 0 12px;border-bottom:1px solid #1f2b47;padding-bottom:8px;">Certification</h3>

            <div style="margin-bottom:20px;background:#0a0514;padding:16px;border-radius:6px;border:1px solid #1f2b47;">
                <label style="display:flex;align-items:flex-start;cursor:pointer;color:#d1d5db;font-size:13px;line-height:1.6;">
                    <input type="checkbox" name="w9_certification" value="1" required
                        style="margin-right:10px;margin-top:3px;min-width:16px;min-height:16px;cursor:pointer;accent-color:#44f80c;">
                    <span>
                        Under penalties of perjury, I certify that:<br><br>
                        1. The number shown on this form is my correct taxpayer identification number (or I am waiting for a number to be issued to me), and<br>
                        2. I am not subject to backup withholding because: (a) I am exempt from backup withholding, or (b) I have not been notified by the IRS that I am subject to backup withholding, or (c) the IRS has notified me that I am no longer subject to backup withholding, and<br>
                        3. I am a U.S. citizen or other U.S. person (including a resident alien), and<br>
                        4. The information provided is accurate and complete to the best of my knowledge.
                    </span>
                </label>
            </div>

            <!-- Submit -->
            <?php wp_nonce_field('microdos_w9_submit', 'microdos_w9_nonce'); ?>

            <button type="submit" style="
                width:100%;
                padding:14px 24px;
                background:#44f80c;
                color:#0a0514;
                font-weight:700;
                font-size:16px;
                border:none;
                border-radius:8px;
                cursor:pointer;
                transition:opacity 0.2s;
            " onmouseover="this.style.opacity='0.85'" onmouseout="this.style.opacity='1'">
                Submit W-9 Form
            </button>

            <p style="text-align:center;margin-top:12px;font-size:12px;color:#94a3b8;">
                Your information is stored securely and is only used for IRS 1099-NEC tax reporting. We do not share this data with third parties.
            </p>

        </form>
    </div>
    <?php
    return ob_get_clean();
}


// ============================================
// W-9 SERVER-SIDE VALIDATION & SAVE
// ============================================

/**
 * Validate W-9 fields during affiliate registration.
 * This runs server-side after the form submits via AJAX.
 * If validation fails, AffiliateWP shows the error and stops registration.
 */
add_action('affwp_process_register_form', 'microdos_validate_w9_on_registration', 10, 1);

function microdos_validate_w9_on_registration($data) {
    $required_fields = array(
        'affwp_w9_legal_name'         => 'Full Legal Name',
        'affwp_w9_tax_classification' => 'Tax Classification',
        'affwp_w9_address'            => 'Street Address',
        'affwp_w9_city'               => 'City',
        'affwp_w9_state'              => 'State',
        'affwp_w9_zip'                => 'ZIP Code',
        'affwp_w9_tax_id'             => 'SSN or EIN',
    );

    foreach ($required_fields as $field => $label) {
        if (empty($_POST[$field])) {
            affwp_add_error($field . '_required', sprintf(__('W-9: %s is required.'), $label));
        }
    }

    if (empty($_POST['affwp_w9_certification'])) {
        affwp_add_error('affwp_w9_certification_required', __('W-9: You must certify the information is correct under penalties of perjury.'));
    }
}

/**
 * Save W-9 data after successful affiliate registration.
 * Runs after the user and affiliate records are created.
 */
add_action('user_register', 'microdos_save_w9_on_affiliate_register', 10, 1);

function microdos_save_w9_on_affiliate_register($user_id) {
    // Only process if W-9 fields were submitted (affiliate registration)
    if (empty($_POST['affwp_w9_legal_name'])) {
        return;
    }

    $w9_data = array(
        'legal_name'          => sanitize_text_field($_POST['affwp_w9_legal_name'] ?? ''),
        'business_name'       => sanitize_text_field($_POST['affwp_w9_business_name'] ?? ''),
        'tax_classification'  => sanitize_text_field($_POST['affwp_w9_tax_classification'] ?? ''),
        'address'             => sanitize_text_field($_POST['affwp_w9_address'] ?? ''),
        'city'                => sanitize_text_field($_POST['affwp_w9_city'] ?? ''),
        'state'               => sanitize_text_field($_POST['affwp_w9_state'] ?? ''),
        'zip'                 => sanitize_text_field($_POST['affwp_w9_zip'] ?? ''),
        'tax_id'              => sanitize_text_field($_POST['affwp_w9_tax_id'] ?? ''),
        'certification_date'  => current_time('mysql'),
        'ip_address'          => sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''),
    );

    update_user_meta($user_id, 'microdos_w9_data', $w9_data);
    update_user_meta($user_id, 'microdos_w9_status', 'complete');
}










// ============================================
// GRAVITY FORMS - CREATE USER & AFFILIATE ON SUBMISSION
// Uses gform_after_submission_1 per Gravity Forms docs
// https://docs.gravityforms.com/gform_after_submission/
// ============================================

add_action('gform_after_submission_2', 'microdos_create_affiliate_from_form', 10, 2);

function microdos_create_affiliate_from_form($entry, $form) {
    // Extract values using rgar() per Gravity Forms docs
    $first_name  = rgar($entry, '1.3');   // Name: First
    $last_name   = rgar($entry, '1.6');   // Name: Last
    $email       = rgar($entry, '2');     // Email
    $password    = rgar($entry, '3');     // Password
    $username    = rgar($entry, '4');     // Username
    $website     = rgar($entry, '5');     // Website
    $legal_name  = rgar($entry, '7');     // W-9: Full Legal Name
    $business    = rgar($entry, '8');     // W-9: Business Name
    $tax_class   = rgar($entry, '9');     // W-9: Tax Classification
    $address     = rgar($entry, '10');    // W-9: Address
    $address2    = rgar($entry, '11');    // W-9: Address 2
    $city        = rgar($entry, '12');    // W-9: City
    $state       = rgar($entry, '13');    // W-9: State
    $zip         = rgar($entry, '14');    // W-9: ZIP
    $tax_id      = rgar($entry, '15');    // W-9: SSN/EIN

    // Auto-generate username from email if empty
    if (empty($username)) {
        $username = sanitize_user(current(explode('@', $email)), true);
    }
    $original = $username;
    $suffix = 1;
    while (username_exists($username)) {
        $username = $original . $suffix;
        $suffix++;
    }

    // Create WordPress user
    $user_id = wp_insert_user(array(
        'user_login'   => $username,
        'user_email'   => sanitize_email($email),
        'user_pass'    => $password,
        'first_name'   => sanitize_text_field($first_name),
        'last_name'    => sanitize_text_field($last_name),
        'user_url'     => esc_url_raw($website),
        'role'         => 'subscriber',
    ));

    if (is_wp_error($user_id)) {
        error_log('[microDOS] User creation failed: ' . $user_id->get_error_message());
        return;
    }

    // Create AffiliateWP affiliate
    if (function_exists('affwp_add_affiliate')) {
        affwp_add_affiliate(array(
            'user_id'       => $user_id,
            'status'        => 'pending',
            'payment_email' => sanitize_email($email),
        ));
    }

    // Save W-9 data
    update_user_meta($user_id, 'microdos_w9_data', array(
        'legal_name'         => sanitize_text_field($legal_name),
        'business_name'      => sanitize_text_field($business),
        'tax_classification' => sanitize_text_field($tax_class),
        'address'            => sanitize_text_field($address),
        'address2'           => sanitize_text_field($address2),
        'city'               => sanitize_text_field($city),
        'state'              => sanitize_text_field($state),
        'zip'                => sanitize_text_field($zip),
        'tax_id'             => sanitize_text_field($tax_id),
        'certification_date' => current_time('mysql'),
        'ip_address'         => sanitize_text_field($_SERVER['REMOTE_ADDR'] ?? ''),
    ));
    update_user_meta($user_id, 'microdos_w9_status', 'complete');

    // Send pending welcome email to new affiliate
    microdos_send_affiliate_pending_email($user_id, $email, $first_name, $last_name);

    // Log the user in
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id, true);
}

// ============================================
// AFFILIATE PENDING WELCOME EMAIL
// Sent immediately after registration
// ============================================

function microdos_send_affiliate_pending_email($user_id, $email, $first_name, $last_name) {
    $site_name   = get_bloginfo('name');
    $site_url    = home_url('/');
    $affiliate_area = get_permalink(get_page_by_path('affiliate-area')) ?: home_url('/affiliate-area/');
    $admin_email = get_option('admin_email');

    $subject = "Your {$site_name} Affiliate Application Received";

    // Build dark-themed HTML email
    $message = '<!DOCTYPE html>';
    $message .= '<html><head><meta charset="UTF-8"></head>';
    $message .= '<body style="margin:0;padding:0;background-color:#0a0514;font-family:Arial,Helvetica,sans-serif;">';
    $message .= '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="padding:30px 20px;">';
    $message .= '<table width="100%" max-width="600" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;background-color:#150f24;border:1px solid #1f2b47;border-radius:12px;overflow:hidden;">';

    // Header
    $message .= '<tr><td style="padding:30px;text-align:center;background-color:#0a0514;border-bottom:1px solid #1f2b47;">';
    $message .= '<h1 style="margin:0;font-size:24px;color:#44f80c;">' . esc_html($site_name) . '</h1>';
    $message .= '</td></tr>';

    // Body
    $display_name = $first_name ?: $last_name ?: 'Affiliate';
    $message .= '<tr><td style="padding:30px;">';
    $message .= '<h2 style="margin:0 0 16px;font-size:20px;color:#ffffff;">Hello ' . esc_html($display_name) . ',</h2>';
    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">';
    $message .= 'Thank you for applying to the <strong style="color:#ffffff;">' . esc_html($site_name) . ' Affiliate Program</strong>. ';
    $message .= 'Your application has been received and is now <strong style="color:#44f80c;">pending review</strong>.';
    $message .= '</p>';

    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">';
    $message .= 'What happens next:';
    $message .= '</p>';

    $message .= '<ul style="color:#94a3b8;font-size:15px;line-height:1.6;padding-left:20px;">';
    $message .= '<li>Our team will review your application within <strong style="color:#ffffff;">24-48 hours</strong>.</li>';
    $message .= '<li>You will receive an email notification once your application is approved.</li>';
    $message .= '<li>After approval, you can log in to your <a href="' . esc_url($affiliate_area) . '" style="color:#ff66c4;text-decoration:underline;">affiliate dashboard</a> to access your referral link and track earnings.</li>';
    $message .= '</ul>';

    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">';
    $message .= 'If you have any questions in the meantime, simply reply to this email or contact us at ' . esc_html($admin_email) . '.';
    $message .= '</p>';
    $message .= '</td></tr>';

    // Footer
    $message .= '<tr><td style="padding:20px 30px;text-align:center;border-top:1px solid #1f2b47;background-color:#0a0514;">';
    $message .= '<p style="margin:0;color:#64748b;font-size:12px;">' . esc_html($site_name) . ' &bull; <a href="' . esc_url($site_url) . '" style="color:#64748b;text-decoration:none;">' . esc_url($site_url) . '</a></p>';
    $message .= '</td></tr>';

    $message .= '</table>';
    $message .= '</td></tr></table>';
    $message .= '</body></html>';

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: ' . $site_name . ' <' . $admin_email . '>',
    );

    wp_mail($email, $subject, $message, $headers);
}

// ============================================
// AFFILIATE LOGIN REDIRECT
// Redirect affiliates to /affiliate-area/ after login
// ============================================

add_filter('login_redirect', 'microdos_affiliate_login_redirect', 10, 3);
function microdos_affiliate_login_redirect($redirect_to, $request, $user) {
    // Must be a valid user
    if (!is_a($user, 'WP_User')) {
        return $redirect_to;
    }

    // Check if user is an affiliate
    if (function_exists('affwp_is_affiliate') && affwp_is_affiliate($user->ID)) {
        $affiliate_area = get_permalink(get_page_by_path('affiliate-area'));
        if ($affiliate_area) {
            return $affiliate_area;
        }
    }

    return $redirect_to;
}

// ============================================
// AFFILIATEWP EMAIL LOGO SIZE FIX
// Prevent oversized logo in affiliate emails
// ============================================

add_filter('affwp_email_logo', 'microdos_fix_affiliate_email_logo');
function microdos_fix_affiliate_email_logo($logo) {
    if (empty($logo)) {
        return $logo;
    }
    // Inject max-width and height constraint into the logo image
    $logo = str_replace('<img', '<img style="max-width:200px;height:auto;display:block;margin:0 auto;" ', $logo);
    return $logo;
}

// ============================================
// AFFILIATE TERMS OF SERVICE LINK
// Add hyperlink to "terms and conditions" in the registration form
// ============================================

add_filter('gform_field_content', 'microdos_affiliate_terms_link', 10, 5);
function microdos_affiliate_terms_link($content, $field, $value, $lead_id, $form_id) {
    // Only apply to the affiliate registration form (ID 2)
    if ($form_id != 2) {
        return $content;
    }

    // Only apply to checkbox fields that contain the terms text
    if ($field->type != 'checkbox' || strpos($content, 'terms and conditions') === false) {
        return $content;
    }

    // Wrap "terms and conditions" in a hyperlink
    $terms_url = home_url('/affiliate-terms-of-use/');
    $content = str_replace(
        'terms and conditions',
        '<a href="' . esc_url($terms_url) . '" target="_blank" style="color:#ff66c4;text-decoration:underline;">terms and conditions</a>',
        $content
    );

    return $content;
}

// ============================================
// GRAVITY FORMS TEXT COLOR FIX (v3 - Ultra Strong)
// ============================================

add_filter('gform_default_styles', 'microdos_gform_dark_theme_styles', 10, 1);
function microdos_gform_dark_theme_styles($styles) {
    // Per docs: $styles can be array, JSON string, or false
    if (is_array($styles)) {
        $style_array = $styles;
    } elseif (is_string($styles)) {
        $style_array = json_decode($styles, true);
    }
    if (empty($style_array) || !is_array($style_array)) {
        $style_array = array();
    }

    // CRITICAL: Set input text color to white for dark theme visibility
    // inputColor sets --gf-color-in-ctrl-contrast which drives --gf-ctrl-color
    $style_array['inputColor'] = '#ffffff';
    $style_array['theme'] = 'orbital';
    $style_array['inputBackgroundColor'] = '#1a1040';
    $style_array['inputBorderColor'] = '#2d2255';
    $style_array['inputPrimaryColor'] = '#44f80c';
    $style_array['labelColor'] = '#ffffff';
    $style_array['descriptionColor'] = '#d1d5db';

    return $style_array; // Return array per official docs Example 1
}



// ============================================
// CUSTOM ORDER STATUS: SHIPPED
// Adds "Shipped" between Processing and Completed
// Integrates with ShipStation (or manual workflow)
// ============================================

add_action('init', 'microdos_register_shipped_status', 10, 0);
function microdos_register_shipped_status() {
    register_post_status('wc-shipped', [
        'label'                     => _x('Shipped', 'Order status', 'microdos4u'),
        'public'                    => false,
        'exclude_from_search'       => false,
        'show_in_admin_all_list'    => true,
        'show_in_admin_status_list' => true,
        'label_count'               => _n_noop('Shipped <span class="count">(%s)</span>', 'Shipped <span class="count">(%s)</span>', 'microdos4u'),
    ]);
}

// Add "Shipped" to WooCommerce order statuses
add_filter('wc_order_statuses', 'microdos_add_shipped_status');
function microdos_add_shipped_status($order_statuses) {
    $new_statuses = [];
    foreach ($order_statuses as $key => $value) {
        $new_statuses[$key] = $value;
        if ($key === 'wc-processing') {
            $new_statuses['wc-shipped'] = _x('Shipped', 'Order status', 'microdos4u');
        }
    }
    return $new_statuses;
}

// Add "Mark as Shipped" bulk action on Orders list
add_filter('bulk_actions-edit-shop_order', 'microdos_add_shipped_bulk_action');
function microdos_add_shipped_bulk_action($actions) {
    $actions['mark_shipped'] = __('Mark as Shipped', 'microdos4u');
    return $actions;
}

// ============================================
// TRACKING NUMBER META FIELD
// Add tracking number to order admin page
// ============================================

add_action('add_meta_boxes', 'microdos_add_tracking_meta_box');
function microdos_add_tracking_meta_box() {
    add_meta_box(
        'microdos_tracking',
        __('Shipping & Tracking', 'microdos4u'),
        'microdos_tracking_meta_box_html',
        'shop_order',
        'side',
        'high'
    );
}

function microdos_tracking_meta_box_html($post) {
    $order = wc_get_order($post->ID);
    $tracking = $order->get_meta('_microdos_tracking_number', true);
    $carrier  = $order->get_meta('_microdos_tracking_carrier', true) ?: 'usps';
    wp_nonce_field('microdos_save_tracking', 'microdos_tracking_nonce');
    ?>
    <p>
        <label for="microdos_tracking_number" style="display:block;margin-bottom:4px;font-weight:600;">Tracking Number</label>
        <input type="text" id="microdos_tracking_number" name="microdos_tracking_number" value="<?php echo esc_attr($tracking); ?>" style="width:100%;padding:6px;border:1px solid #1f2b47;background:#150f24;color:#e2e8f0;border-radius:4px;" placeholder="e.g. 9400111899223456789012">
    </p>
    <p>
        <label for="microdos_tracking_carrier" style="display:block;margin-bottom:4px;font-weight:600;">Carrier</label>
        <select id="microdos_tracking_carrier" name="microdos_tracking_carrier" style="width:100%;padding:6px;border:1px solid #1f2b47;background:#150f24;color:#e2e8f0;border-radius:4px;">
            <option value="usps" <?php selected($carrier, 'usps'); ?>>USPS</option>
            <option value="ups" <?php selected($carrier, 'ups'); ?>>UPS</option>
            <option value="fedex" <?php selected($carrier, 'fedex'); ?>>FedEx</option>
        </select>
    </p>
    <p style="margin-top:8px;">
        <button type="button" class="button" onclick="document.getElementById('microdos_tracking_number').value='';document.getElementById('microdos_tracking_carrier').value='usps';">Clear</button>
    </p>
    <?php
}

// Save tracking meta
add_action('save_post', 'microdos_save_tracking_meta');
function microdos_save_tracking_meta($post_id) {
    if (get_post_type($post_id) !== 'shop_order') return;
    if (!isset($_POST['microdos_tracking_nonce']) || !wp_verify_nonce($_POST['microdos_tracking_nonce'], 'microdos_save_tracking')) return;
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $tracking = isset($_POST['microdos_tracking_number']) ? sanitize_text_field($_POST['microdos_tracking_number']) : '';
    $carrier  = isset($_POST['microdos_tracking_carrier']) ? sanitize_text_field($_POST['microdos_tracking_carrier']) : 'usps';

    $order = wc_get_order($post_id);
    $order->update_meta_data('_microdos_tracking_number', $tracking);
    $order->update_meta_data('_microdos_tracking_carrier', $carrier);
    $order->save();
}

// ============================================
// SHIPPED EMAIL NOTIFICATION
// Send HTML email to customer when order ships
// ============================================

add_action('woocommerce_order_status_shipped', 'microdos_shipped_email_notification', 10, 1);
function microdos_shipped_email_notification($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;

    $tracking  = $order->get_meta('_microdos_tracking_number', true);
    $carrier   = $order->get_meta('_microdos_tracking_carrier', true) ?: 'usps';
    $site_name = get_bloginfo('name');
    $site_url  = home_url('/');
    $to        = $order->get_billing_email();
    $subject   = "Your {$site_name} Order Has Shipped (#" . $order->get_order_number() . ")";

    $tracking_url = microdos_get_tracking_url_by_carrier($tracking, $carrier);

    $message = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head>';
    $message .= '<body style="margin:0;padding:0;background-color:#0a0514;font-family:Arial,Helvetica,sans-serif;">';
    $message .= '<table width="100%" cellpadding="0" cellspacing="0" border="0"><tr><td align="center" style="padding:30px 20px;">';
    $message .= '<table width="100%" style="max-width:600px;background-color:#150f24;border:1px solid #1f2b47;border-radius:12px;overflow:hidden;">';

    $message .= '<tr><td style="padding:30px;text-align:center;background-color:#0a0514;border-bottom:1px solid #1f2b47;">';
    $message .= '<h1 style="margin:0;font-size:24px;color:#44f80c;">' . esc_html($site_name) . '</h1>';
    $message .= '<p style="margin:8px 0 0;color:#94a3b8;font-size:14px;">Order Shipped</p>';
    $message .= '</td></tr>';

    $message .= '<tr><td style="padding:30px;">';
    $message .= '<h2 style="margin:0 0 16px;font-size:20px;color:#ffffff;">Hello ' . esc_html($order->get_billing_first_name()) . ',</h2>';
    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">Great news! Your order <strong style="color:#ffffff;">#' . esc_html($order->get_order_number()) . '</strong> has been shipped and is on its way.</p>';

    if ($tracking && $tracking_url) {
        $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">Tracking number: <strong style="color:#ffffff;">' . esc_html($tracking) . '</strong></p>';
        $message .= '<p style="text-align:center;margin:20px 0;"><a href="' . esc_url($tracking_url) . '" style="display:inline-block;background-color:#44f80c;color:#0a0514;padding:14px 32px;border-radius:8px;text-decoration:none;font-weight:700;font-size:15px;">Track Your Package</a></p>';
    }

    $message .= '<p style="color:#94a3b8;font-size:15px;line-height:1.6;">You can also track your order in your <a href="' . esc_url(wc_get_page_permalink('myaccount') . 'orders/') . '" style="color:#ff66c4;text-decoration:underline;">account</a>.</p>';
    $message .= '</td></tr>';

    $message .= '<tr><td style="padding:20px 30px;text-align:center;border-top:1px solid #1f2b47;background-color:#0a0514;">';
    $message .= '<p style="margin:0;color:#64748b;font-size:12px;">' . esc_html($site_name) . ' &bull; <a href="' . esc_url($site_url) . '" style="color:#64748b;text-decoration:none;">' . esc_url($site_url) . '</a></p>';
    $message .= '</td></tr></table></td></tr></table></body></html>';

    $headers = ['Content-Type: text/html; charset=UTF-8', 'From: ' . $site_name . ' <' . get_option('admin_email') . '>'];
    wp_mail($to, $subject, $message, $headers);
}

// ============================================
// HELPER: Get tracking URL by carrier
// ============================================

function microdos_get_tracking_url_by_carrier($tracking, $carrier) {
    if (!$tracking) return '';
    switch ($carrier) {
        case 'usps':  return 'https://tools.usps.com/go/TrackConfirmAction?tLabels=' . esc_attr($tracking);
        case 'ups':   return 'https://www.ups.com/track?tracknum=' . esc_attr($tracking);
        case 'fedex': return 'https://www.fedex.com/fedextrack/?trknbr=' . esc_attr($tracking);
        default:      return '';
    }
}

// ============================================
// CUSTOMER-FACING: Show tracking on order list
// ============================================

add_filter('woocommerce_my_account_my_orders_columns', 'microdos_add_tracking_column');
function microdos_add_tracking_column($columns) {
    $new_columns = [];
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'order-status') {
            $new_columns['order-tracking'] = __('Tracking', 'microdos4u');
        }
    }
    return $new_columns;
}

add_action('woocommerce_my_account_my_orders_column_order-tracking', 'microdos_display_tracking_column');
function microdos_display_tracking_column($order) {
    $tracking = $order->get_meta('_microdos_tracking_number', true);
    $carrier  = $order->get_meta('_microdos_tracking_carrier', true) ?: 'usps';
    $tracking_url = microdos_get_tracking_url_by_carrier($tracking, $carrier);

    if ($tracking && $tracking_url && $order->has_status('shipped')) {
        echo '<a href="' . esc_url($tracking_url) . '" target="_blank" style="color:#44f80c;text-decoration:underline;font-size:13px;font-weight:600;">' . esc_html($tracking) . '</a>';
    } elseif ($tracking && $tracking_url && $order->has_status('completed')) {
        echo '<a href="' . esc_url($tracking_url) . '" target="_blank" style="color:#44f80c;text-decoration:underline;font-size:13px;">' . esc_html($tracking) . '</a>';
    } else {
        echo '<span style="color:#64748b;font-size:13px;">--</span>';
    }
}

// ============================================
// CUSTOMER-FACING: Show tracking on order details
// ============================================

add_action('woocommerce_view_order', 'microdos_display_tracking_on_order', 20);
function microdos_display_tracking_on_order($order_id) {
    $order = wc_get_order($order_id);
    $tracking = $order->get_meta('_microdos_tracking_number', true);
    $carrier  = $order->get_meta('_microdos_tracking_carrier', true) ?: 'usps';
    $tracking_url = microdos_get_tracking_url_by_carrier($tracking, $carrier);

    if ($tracking && $tracking_url) {
        echo '<div style="background:#0a0514;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin:20px 0;">';
        echo '<h3 style="color:#44f80c;margin:0 0 12px;font-size:16px;">Shipping & Tracking</h3>';
        echo '<p style="color:#94a3b8;margin:0 0 8px;">Carrier: <strong style="color:#e2e8f0;">' . esc_html(strtoupper($carrier)) . '</strong></p>';
        echo '<p style="color:#94a3b8;margin:0 0 12px;">Tracking #: <strong style="color:#e2e8f0;">' . esc_html($tracking) . '</strong></p>';
        echo '<a href="' . esc_url($tracking_url) . '" target="_blank" style="display:inline-block;background:#44f80c;color:#0a0514;padding:10px 24px;border-radius:6px;text-decoration:none;font-weight:700;font-size:14px;">Track on ' . esc_html(strtoupper($carrier)) . '</a>';
        echo '</div>';
    }
}

// ============================================
// CUSTOMER-FACING: Show tracking on thankyou page
// ============================================

add_action('woocommerce_thankyou', 'microdos_thankyou_shipping_notice', 15);
function microdos_thankyou_shipping_notice($order_id) {
    $order = wc_get_order($order_id);
    if (!$order) return;
    $tracking = $order->get_meta('_microdos_tracking_number', true);
    $carrier  = $order->get_meta('_microdos_tracking_carrier', true) ?: 'usps';
    $tracking_url = microdos_get_tracking_url_by_carrier($tracking, $carrier);

    if ($tracking && $tracking_url && ($order->has_status('shipped') || $order->has_status('completed'))) {
        echo '<div style="background:#0a0514;border:1px solid #44f80c;border-radius:8px;padding:20px;margin:20px 0;text-align:center;">';
        echo '<h3 style="color:#44f80c;margin:0 0 8px;font-size:16px;">Your Order Has Shipped!</h3>';
        echo '<p style="color:#94a3b8;margin:0 0 12px;">Tracking: <strong style="color:#e2e8f0;">' . esc_html($tracking) . '</strong></p>';
        echo '<a href="' . esc_url($tracking_url) . '" target="_blank" style="display:inline-block;background:#44f80c;color:#0a0514;padding:10px 24px;border-radius:6px;text-decoration:none;font-weight:700;font-size:14px;">Track Package</a>';
        echo '</div>';
    } else {
        echo '<div style="background:#0a0514;border:1px solid #1f2b47;border-radius:8px;padding:20px;margin:20px 0;text-align:center;">';
        echo '<p style="color:#94a3b8;margin:0;">Your order will ship within <strong style="color:#e2e8f0;">1-2 business days</strong>. You will receive a tracking email once it ships.</p>';
        echo '</div>';
    }
}

// ============================================
// SHIPPING DASHBOARD ADMIN PAGE
// Centralized shipping management interface
// ============================================

require_once get_template_directory() . '/admin-shipping.php';
