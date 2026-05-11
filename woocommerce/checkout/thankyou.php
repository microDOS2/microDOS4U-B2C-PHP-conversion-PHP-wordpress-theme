<?php
/**
 * Checkout Thankyou Page
 *
 * Custom thank-you page with account management links.
 * Overrides WooCommerce's default thankyou.php template.
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="woocommerce-order py-12" style="color: #94a3b8;">

    <?php
    if ($order) :
        do_action('woocommerce_before_thankyou', $order->get_id());
    endif;
    ?>

    <!-- Account Management Bar (shown to logged-in users) -->
    <?php if (is_user_logged_in()) : ?>
    <div class="mb-6 p-4 rounded-lg flex flex-wrap justify-between items-center gap-4" 
         style="background-color: #150f24; border: 1px solid #1f2b47;">
        <div class="flex items-center gap-3">
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            <span class="text-white text-sm font-medium">
                <?php 
                $current_user = wp_get_current_user();
                printf(esc_html__('Logged in as %s', 'microdos4u'), esc_html($current_user->display_name)); 
                ?>
            </span>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" 
               class="text-sm font-medium transition-all duration-200 flex items-center gap-1"
               style="color: #44f80c;"
               onmouseover="this.style.color='#fff';"
               onmouseout="this.style.color='#44f80c';">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                <?php esc_html_e('My Account', 'microdos4u'); ?>
            </a>
            <span class="text-slate-600">|</span>
            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" 
               class="text-sm font-medium transition-all duration-200 flex items-center gap-1"
               style="color: #ff4444;"
               onmouseover="this.style.color='#fff';"
               onmouseout="this.style.color='#ff4444';">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <?php esc_html_e('Log Out', 'microdos4u'); ?>
            </a>
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

    <?php if ($order) : ?>

        <!-- Account Created Notice -->
        <?php if (WC()->session && WC()->session->get('microdos_new_account_created')) : 
            $new_email = WC()->session->get('microdos_new_account_email');
        ?>
        <div class="mb-6 p-5 rounded-lg" style="background-color: #150f24; border: 1px solid #44f80c;">
            <div class="flex items-start gap-3">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="flex-shrink-0 mt-0.5"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><polyline points="22 4 12 14.01 9 11.01"/></svg>
                <div>
                    <p class="text-white font-medium mb-1"><?php esc_html_e('Account Created Successfully!', 'microdos4u'); ?></p>
                    <p class="text-slate-400 text-sm">
                        <?php 
                        printf(
                            esc_html__('We\'ve created an account for you. Check your email at %s for login details and a link to set your password.', 'microdos4u'),
                            '<strong style="color: #44f80c;">' . esc_html($new_email) . '</strong>'
                        ); 
                        ?>
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>

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
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('dashboard')); ?>" 
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
            <p class="text-slate-400 mb-4"><?php esc_html_e('Unable to locate order details. Please check your email for confirmation.', 'microdos4u'); ?></p>
            <a href="<?php echo esc_url(home_url()); ?>" 
               class="inline-flex items-center justify-center px-6 py-3 rounded-lg font-semibold"
               style="background-color: #44f80c; color: #0a0514;">
                <?php esc_html_e('Back to Home', 'microdos4u'); ?>
            </a>
        </div>

    <?php endif; ?>

</div>
