<?php
/**
 * Checkout Thankyou Page
 *
 * Custom thank-you page with account creation notice and password display.
 * Overrides WooCommerce's default thankyou.php template.
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}

// Ensure $order is available — retrieve from URL if not passed by WooCommerce
if (!isset($order) || !is_object($order) || !($order instanceof WC_Order)) {
    $order = false;

    // Try to get order from URL parameters (standard WooCommerce order-received URL)
    if (isset($_GET['order']) && isset($_GET['key'])) {
        $order_id  = absint($_GET['order']);
        $order_key = wc_clean(wp_unslash($_GET['key']));
        $possible_order = wc_get_order($order_id);
        if ($possible_order && $possible_order instanceof WC_Order && hash_equals($possible_order->get_order_key(), $order_key)) {
            $order = $possible_order;
        }
    }

    // Fallback: try from global scope
    if (!$order && isset($GLOBALS['order']) && is_object($GLOBALS['order']) && $GLOBALS['order'] instanceof WC_Order) {
        $order = $GLOBALS['order'];
    }

    // Fallback: try from the order-received endpoint
    if (!$order && function_exists('wc_get_order_id_by_order_key') && isset($_GET['key'])) {
        $order_id = wc_get_order_id_by_order_key(wc_clean(wp_unslash($_GET['key'])));
        if ($order_id) {
            $possible_order = wc_get_order($order_id);
            if ($possible_order && $possible_order instanceof WC_Order) {
                $order = $possible_order;
            }
        }
    }
}

// Safely get order ID for actions
$order_id = ($order && $order instanceof WC_Order) ? $order->get_id() : 0;

// Check for newly created account — check transient first, then session
$new_account_email = '';
$new_account_password = '';
$account_created = false;

if ($order_id) {
    // Check transient (most reliable — set by auto-create function)
    $transient_key = 'microdos_new_account_' . $order_id;
    $account_data = get_transient($transient_key);
    if ($account_data && is_array($account_data)) {
        $new_account_email = isset($account_data['email']) ? $account_data['email'] : '';
        $new_account_password = isset($account_data['password']) ? $account_data['password'] : '';
        $account_created = !empty($new_account_email);
    }
}

// Fallback to session if transient not found
if (!$account_created && function_exists('WC') && WC()->session) {
    $session_created = WC()->session->get('microdos_new_account_created');
    $session_email   = WC()->session->get('microdos_new_account_email');
    $session_password = WC()->session->get('microdos_new_account_password');
    if ($session_created && !empty($session_email)) {
        $account_created = true;
        $new_account_email = $session_email;
        $new_account_password = $session_password ? $session_password : '';
    }
}
?>

<div class="woocommerce-order py-12" style="color: #94a3b8;">

    <?php if ($order && $order instanceof WC_Order) : ?>

        <?php do_action('woocommerce_before_thankyou', $order->get_id()); ?>

        <!-- Account Creation Notice (with password) -->
        <?php if ($account_created && !empty($new_account_email)) : ?>
        <div class="mb-6 p-5 rounded-lg" style="background-color: #150f24; border: 1px solid #44f80c;">
            <div class="flex items-start gap-3">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <div class="flex-1">
                    <p class="text-white font-medium mb-2"><?php esc_html_e('Account Created Successfully!', 'microdos4u'); ?></p>
                    <p class="text-slate-400 text-sm mb-3">
                        <?php esc_html_e('We\'ve automatically created an account for you. Save these credentials — you\'ll need them to manage your orders and subscriptions.', 'microdos4u'); ?>
                    </p>
                    <div class="p-3 rounded mb-3" style="background-color: #0a0514; border: 1px solid #1f2b47;">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-slate-400 text-sm"><?php esc_html_e('Email:', 'microdos4u'); ?></span>
                            <span class="text-white font-medium"><?php echo esc_html($new_account_email); ?></span>
                        </div>
                        <?php if (!empty($new_account_password)) : ?>
                        <div class="flex items-center gap-2">
                            <span class="text-slate-400 text-sm"><?php esc_html_e('Password:', 'microdos4u'); ?></span>
                            <code class="text-sm font-mono px-2 py-0.5 rounded" style="background-color: #1f2b47; color: #44f80c;"><?php echo esc_html($new_account_password); ?></code>
                            <button type="button" onclick="var r=document.createRange();r.selectNode(this.previousElementSibling);window.getSelection().removeAllRanges();window.getSelection().addRange(r);document.execCommand('copy');this.textContent='Copied!';var b=this;setTimeout(function(){b.textContent='Copy';},2000);" 
                                    class="text-xs px-2 py-0.5 rounded transition-colors" 
                                    style="background-color: #1f2b47; color: #94a3b8; cursor: pointer; border: none;">Copy</button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <p class="text-slate-500 text-xs">
                        <?php esc_html_e('You can also log in anytime at:', 'microdos4u'); ?>
                        <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>" style="color: #38bdf8; text-decoration: underline;"><?php echo esc_url(wc_get_page_permalink('myaccount')); ?></a>
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Success Header -->
        <div class="text-center mb-8">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background-color: #44f80c20;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="20 6 9 17 4 12"/></svg>
            </div>
            <h1 class="text-3xl font-bold text-white mb-2"><?php esc_html_e('Thank You for Your Order!', 'microdos4u'); ?></h1>
            <p class="text-slate-400"><?php esc_html_e('Your order has been received and is being processed.', 'microdos4u'); ?></p>
        </div>

        <!-- Order Summary Card -->
        <div class="p-6 rounded-lg mb-6" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <h2 class="text-xl font-bold text-white mb-4"><?php esc_html_e('Order Summary', 'microdos4u'); ?></h2>

            <ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details mb-4" style="list-style: none; padding: 0; margin: 0;">

                <li class="woocommerce-order-overview__order order mb-2 pb-2" style="border-bottom: 1px solid #1f2b47;">
                    <span class="text-slate-400"><?php esc_html_e('Order number:', 'microdos4u'); ?></span>
                    <strong class="text-white"><?php echo $order->get_order_number(); ?></strong>
                </li>

                <li class="woocommerce-order-overview__date date mb-2 pb-2" style="border-bottom: 1px solid #1f2b47;">
                    <span class="text-slate-400"><?php esc_html_e('Date:', 'microdos4u'); ?></span>
                    <strong class="text-white"><?php echo wc_format_datetime($order->get_date_created()); ?></strong>
                </li>

                <li class="woocommerce-order-overview__email email mb-2 pb-2" style="border-bottom: 1px solid #1f2b47;">
                    <span class="text-slate-400"><?php esc_html_e('Email:', 'microdos4u'); ?></span>
                    <strong class="text-white"><?php echo $order->get_billing_email(); ?></strong>
                </li>

                <li class="woocommerce-order-overview__total total mb-2 pb-2" style="border-bottom: 1px solid #1f2b47;">
                    <span class="text-slate-400"><?php esc_html_e('Total:', 'microdos4u'); ?></span>
                    <strong class="text-white"><?php echo $order->get_formatted_order_total(); ?></strong>
                </li>

                <?php if ($order->get_payment_method_title()) : ?>
                <li class="woocommerce-order-overview__payment-method method">
                    <span class="text-slate-400"><?php esc_html_e('Payment method:', 'microdos4u'); ?></span>
                    <strong class="text-white"><?php echo wp_kses_post($order->get_payment_method_title()); ?></strong>
                </li>
                <?php endif; ?>

            </ul>

            <?php do_action('woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id()); ?>
        </div>

        <!-- Next Steps -->
        <div class="p-6 rounded-lg mb-6" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <h3 class="text-lg font-bold text-white mb-4"><?php esc_html_e('What Happens Next?', 'microdos4u'); ?></h3>
            <div class="space-y-3">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #44f80c20; color: #44f80c;">1</div>
                    <div>
                        <p class="text-white font-medium"><?php esc_html_e('Order Confirmation Email', 'microdos4u'); ?></p>
                        <p class="text-slate-400 text-sm"><?php esc_html_e('You will receive an email with your order details and receipt.', 'microdos4u'); ?></p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #9a02d020; color: #9a02d0;">2</div>
                    <div>
                        <p class="text-white font-medium"><?php esc_html_e('Processing', 'microdos4u'); ?></p>
                        <p class="text-slate-400 text-sm"><?php esc_html_e('Your order is being prepared and will ship within 1-2 business days.', 'microdos4u'); ?></p>
                    </div>
                </div>
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #ff66c420; color: #ff66c4;">3</div>
                    <div>
                        <p class="text-white font-medium"><?php esc_html_e('Track Your Order', 'microdos4u'); ?></p>
                        <p class="text-slate-400 text-sm"><?php esc_html_e('Log in to your account anytime to track orders and manage subscriptions.', 'microdos4u'); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- CTA Buttons -->
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-semibold transition-all duration-300"
               style="background-color: #44f80c; color: #0a0514;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/></svg>
                <?php esc_html_e('View My Orders', 'microdos4u'); ?>
            </a>
            <a href="<?php echo esc_url(wc_get_page_permalink('myaccount')); ?>"
               class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-lg font-semibold transition-all duration-300"
               style="background-color: #9a02d0; color: #fff;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <?php esc_html_e('My Account Dashboard', 'microdos4u'); ?>
            </a>
        </div>

        <?php do_action('woocommerce_thankyou', $order->get_id()); ?>

    <?php else : ?>

        <!-- No order found -->
        <div class="p-8 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="inline-flex items-center justify-center w-16 h-16 rounded-full mb-4" style="background-color: #ff444420;">
                <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="#ff4444" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="12"/><line x1="12" y1="16" x2="12.01" y2="16"/></svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2"><?php esc_html_e('Order Not Found', 'microdos4u'); ?></h2>
            <p class="text-slate-400 mb-4"><?php esc_html_e('We couldn\'t locate your order details. Please check your email for confirmation or contact support.', 'microdos4u'); ?></p>
            <a href="<?php echo esc_url(home_url()); ?>"
               class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold"
               style="background-color: #44f80c; color: #0a0514;">
                <?php esc_html_e('Back to Home', 'microdos4u'); ?>
            </a>
        </div>

    <?php endif; ?>

</div>

<?php
// Clean up: clear session flags and transient after rendering
if ($order_id) {
    delete_transient('microdos_new_account_' . $order_id);
}
if (function_exists('WC') && WC()->session) {
    WC()->session->set('microdos_new_account_created', null);
    WC()->session->set('microdos_new_account_email', null);
    WC()->session->set('microdos_new_account_password', null);
}
?>
