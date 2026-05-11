<?php
/**
 * My Account Template - Customer Portal
 *
 * Complete customer dashboard for microDOS4U.
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}

$current_user   = wp_get_current_user();
$user_id        = get_current_user_id();
$customer       = new WC_Customer($user_id);
$orders         = wc_get_orders(['customer_id' => $user_id, 'limit' => 5, 'orderby' => 'date', 'order' => 'DESC']);
$order_count    = count(wc_get_orders(['customer_id' => $user_id, 'limit' => -1]));
$total_spent    = $customer->get_total_spent();

// Subscription data
$sub_count = 0;
$active_subs = [];
if (function_exists('wcs_get_users_subscriptions')) {
    $all_subs = wcs_get_users_subscriptions($user_id);
    $sub_count = count($all_subs);
    foreach ($all_subs as $sub) {
        if ($sub->get_status() === 'active') {
            $active_subs[] = $sub;
        }
    }
}

$current_endpoint = WC()->query->get_current_endpoint();
$is_dashboard = empty($current_endpoint);
?>

<?php if (!is_user_logged_in()) : ?>

<!-- LOGIN / REGISTER PAGE FOR LOGGED-OUT USERS -->
<div class="py-8" style="color: #94a3b8;">

    <div class="max-w-md mx-auto">
        <!-- Logo/Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2"><?php esc_html_e('Account Access', 'microdos4u'); ?></h1>
            <p class="text-slate-400"><?php esc_html_e('Log in to view orders, manage subscriptions, and update your details.', 'microdos4u'); ?></p>
        </div>

        <!-- Login Form Section -->
        <div class="p-6 rounded-lg mb-6" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="2"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                <?php esc_html_e('Existing Customer? Log In', 'microdos4u'); ?>
            </h2>
            <?php
            wc_print_notices();
            woocommerce_login_form(array(
                'redirect' => wc_get_page_permalink('myaccount'),
            ));
            ?>
        </div>

        <!-- Divider -->
        <div class="flex items-center gap-4 mb-6">
            <div class="flex-1 h-px" style="background-color: #1f2b47;"></div>
            <span class="text-slate-500 text-sm"><?php esc_html_e('or', 'microdos4u'); ?></span>
            <div class="flex-1 h-px" style="background-color: #1f2b47;"></div>
        </div>

        <!-- Register Section -->
        <div class="p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9a02d0" stroke-width="2"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                <?php esc_html_e('New Customer? Create Account', 'microdos4u'); ?>
            </h2>
            <p class="text-slate-400 text-sm mb-4">
                <?php esc_html_e('Create an account to track orders, manage subscriptions, and get faster checkout next time.', 'microdos4u'); ?>
            </p>
            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>
                <?php do_action('woocommerce_register_form_start'); ?>

                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
                        <label for="reg_username" class="text-slate-300 text-sm"><?php esc_html_e('Username', 'microdos4u'); ?> <span class="required" style="color: #ff4444;">*</span></label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text w-full mt-1 px-4 py-3 rounded-lg text-white" style="background-color: #0a0514; border: 1px solid #1f2b47;" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
                    </p>
                <?php endif; ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
                    <label for="reg_email" class="text-slate-300 text-sm"><?php esc_html_e('Email address', 'microdos4u'); ?> <span class="required" style="color: #ff4444;">*</span></label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text w-full mt-1 px-4 py-3 rounded-lg text-white" style="background-color: #0a0514; border: 1px solid #1f2b47;" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" />
                </p>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
                    <label for="reg_password" class="text-slate-300 text-sm"><?php esc_html_e('Password', 'microdos4u'); ?> <span class="required" style="color: #ff4444;">*</span></label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text w-full mt-1 px-4 py-3 rounded-lg text-white" style="background-color: #0a0514; border: 1px solid #1f2b47;" name="password" id="reg_password" autocomplete="new-password" />
                </p>

                <?php do_action('woocommerce_register_form'); ?>
                <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>

                <p class="woocommerce-form-row form-row mb-0">
                    <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit w-full px-6 py-3 rounded-lg font-semibold text-center" style="background-color: #9a02d0; color: #fff;" name="register" value="<?php esc_attr_e('Create Account', 'microdos4u'); ?>"><?php esc_html_e('Create Account', 'microdos4u'); ?></button>
                </p>

                <?php do_action('woocommerce_register_form_end'); ?>
            </form>
        </div>

        <!-- Lost Password Link -->
        <div class="text-center mt-6">
            <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" class="text-sm" style="color: #ff66c4;">
                <?php esc_html_e('Lost your password?', 'microdos4u'); ?>
            </a>
        </div>
    </div>
</div>

<?php else : ?>
<div class="woocommerce-MyAccount-content" style="color: #94a3b8; line-height: 1.7;">

    <!-- Header: Welcome + Logout -->
    <div class="mb-6 p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
        <div class="flex flex-wrap justify-between items-start gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white mb-1">
                    <?php printf(esc_html__('Welcome, %s', 'microdos4u'), esc_html($current_user->display_name)); ?>
                </h2>
                <p class="text-slate-400 text-sm"><?php echo esc_html($current_user->user_email); ?></p>
            </div>
            <a href="<?php echo esc_url(wp_logout_url(wc_get_page_permalink('myaccount'))); ?>" 
               class="inline-flex items-center gap-2 px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200"
               style="background-color: transparent; color: #ff4444; border: 1px solid #ff4444;"
               onmouseover="this.style.backgroundColor='#ff4444'; this.style.color='#fff';"
               onmouseout="this.style.backgroundColor='transparent'; this.style.color='#ff4444';">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                <?php esc_html_e('Log Out', 'microdos4u'); ?>
            </a>
        </div>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="p-5 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-2xl font-bold mb-1" style="color: #44f80c;"><?php echo intval($order_count); ?></div>
            <div class="text-slate-400 text-sm"><?php esc_html_e('Orders', 'microdos4u'); ?></div>
        </div>
        <div class="p-5 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-2xl font-bold mb-1" style="color: #9a02d0;"><?php echo intval($sub_count); ?></div>
            <div class="text-slate-400 text-sm"><?php esc_html_e('Subscriptions', 'microdos4u'); ?></div>
        </div>
        <div class="p-5 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-2xl font-bold mb-1" style="color: #ff66c4;"><?php echo wp_kses_post($total_spent); ?></div>
            <div class="text-slate-400 text-sm"><?php esc_html_e('Total Spent', 'microdos4u'); ?></div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="mb-6">
        <nav class="woocommerce-MyAccount-navigation">
            <ul class="flex flex-wrap gap-2" style="list-style: none; padding: 0; margin: 0;">
                <?php
                $menu_items = wc_get_account_menu_items();
                foreach ($menu_items as $endpoint => $label) :
                    $is_active = $current_endpoint === $endpoint || ($is_dashboard && $endpoint === 'dashboard');
                ?>
                    <li style="margin: 0;">
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>" 
                           class="inline-block px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200"
                           style="background-color: <?php echo $is_active ? '#9a02d0' : '#150f24'; ?>; 
                                  color: <?php echo $is_active ? '#fff' : '#94a3b8'; ?>; 
                                  border: 1px solid <?php echo $is_active ? '#9a02d0' : '#1f2b47'; ?>;">
                            <?php echo esc_html($label); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>

    <?php if ($is_dashboard) : ?>
    <!-- DASHBOARD VIEW -->

    <!-- Quick Actions -->
    <div class="mb-6">
        <h3 class="text-lg font-bold text-white mb-3"><?php esc_html_e('Quick Actions', 'microdos4u'); ?></h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" 
               class="flex items-center gap-3 p-4 rounded-lg transition-all duration-200"
               style="background-color: #150f24; border: 1px solid #1f2b47; color: #94a3b8;"
               onmouseover="this.style.borderColor='#44f80c'; this.style.color='#fff';"
               onmouseout="this.style.borderColor='#1f2b47'; this.style.color='#94a3b8';">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                <div>
                    <div class="font-medium text-white"><?php esc_html_e('View Orders', 'microdos4u'); ?></div>
                    <div class="text-xs text-slate-400"><?php esc_html_e('Check order status & history', 'microdos4u'); ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('subscriptions')); ?>" 
               class="flex items-center gap-3 p-4 rounded-lg transition-all duration-200"
               style="background-color: #150f24; border: 1px solid #1f2b47; color: #94a3b8;"
               onmouseover="this.style.borderColor='#9a02d0'; this.style.color='#fff';"
               onmouseout="this.style.borderColor='#1f2b47'; this.style.color='#94a3b8';">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#9a02d0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="23 4 23 10 17 10"/><path d="M20.49 15a9 9 0 1 1-2.12-9.36L23 10"/></svg>
                <div>
                    <div class="font-medium text-white"><?php esc_html_e('Manage Subscriptions', 'microdos4u'); ?></div>
                    <div class="text-xs text-slate-400"><?php esc_html_e('View & update plans', 'microdos4u'); ?></div>
                </div>
            </a>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('edit-account')); ?>" 
               class="flex items-center gap-3 p-4 rounded-lg transition-all duration-200"
               style="background-color: #150f24; border: 1px solid #1f2b47; color: #94a3b8;"
               onmouseover="this.style.borderColor='#ff66c4'; this.style.color='#fff';"
               onmouseout="this.style.borderColor='#1f2b47'; this.style.color='#94a3b8';">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ff66c4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                <div>
                    <div class="font-medium text-white"><?php esc_html_e('Account Details', 'microdos4u'); ?></div>
                    <div class="text-xs text-slate-400"><?php esc_html_e('Update info & password', 'microdos4u'); ?></div>
                </div>
            </a>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="mb-6">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-lg font-bold text-white"><?php esc_html_e('Recent Orders', 'microdos4u'); ?></h3>
            <?php if ($order_count > 0) : ?>
                <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" class="text-sm" style="color: #44f80c;"><?php esc_html_e('View All', 'microdos4u'); ?> &rarr;</a>
            <?php endif; ?>
        </div>
        
        <?php if (!empty($orders)) : ?>
            <div class="rounded-lg overflow-hidden" style="background-color: #150f24; border: 1px solid #1f2b47;">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr style="border-bottom: 1px solid #1f2b47;">
                                <th class="text-left p-3 text-slate-400 font-medium"><?php esc_html_e('Order', 'microdos4u'); ?></th>
                                <th class="text-left p-3 text-slate-400 font-medium"><?php esc_html_e('Date', 'microdos4u'); ?></th>
                                <th class="text-left p-3 text-slate-400 font-medium"><?php esc_html_e('Status', 'microdos4u'); ?></th>
                                <th class="text-right p-3 text-slate-400 font-medium"><?php esc_html_e('Total', 'microdos4u'); ?></th>
                                <th class="text-right p-3 text-slate-400 font-medium"><?php esc_html_e('Action', 'microdos4u'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order) : 
                                $status = $order->get_status();
                                $status_colors = [
                                    'completed' => '#44f80c',
                                    'processing' => '#3b82f6',
                                    'on-hold' => '#f59e0b',
                                    'pending' => '#f59e0b',
                                    'cancelled' => '#ff4444',
                                    'refunded' => '#94a3b8',
                                    'failed' => '#ff4444',
                                ];
                                $status_color = isset($status_colors[$status]) ? $status_colors[$status] : '#94a3b8';
                            ?>
                                <tr style="border-bottom: 1px solid #1f2b47;">
                                    <td class="p-3 text-white font-medium">#<?php echo esc_html($order->get_order_number()); ?></td>
                                    <td class="p-3"><?php echo esc_html(wc_format_datetime($order->get_date_created(), 'M j, Y')); ?></td>
                                    <td class="p-3">
                                        <span class="inline-block px-2 py-1 rounded text-xs font-medium" style="background-color: <?php echo esc_attr($status_color); ?>20; color: <?php echo esc_attr($status_color); ?>; border: 1px solid <?php echo esc_attr($status_color); ?>40;">
                                            <?php echo esc_html(wc_get_order_status_name($status)); ?>
                                        </span>
                                    </td>
                                    <td class="p-3 text-right text-white"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                                    <td class="p-3 text-right">
                                        <a href="<?php echo esc_url($order->get_view_order_url()); ?>" class="text-sm" style="color: #44f80c;"><?php esc_html_e('View', 'microdos4u'); ?></a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php else : ?>
            <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
                <p class="text-slate-400 mb-3"><?php esc_html_e('No orders yet.', 'microdos4u'); ?></p>
                <a href="<?php echo esc_url(home_url('/#pricing')); ?>" class="inline-block px-4 py-2 rounded-lg text-sm font-medium" style="background-color: #9a02d0; color: #fff;"><?php esc_html_e('Start Your Trial', 'microdos4u'); ?></a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Active Subscriptions -->
    <?php if (function_exists('wcs_get_users_subscriptions') && $sub_count > 0) : ?>
    <div class="mb-6">
        <div class="flex justify-between items-center mb-3">
            <h3 class="text-lg font-bold text-white"><?php esc_html_e('Subscriptions', 'microdos4u'); ?></h3>
            <a href="<?php echo esc_url(wc_get_account_endpoint_url('subscriptions')); ?>" class="text-sm" style="color: #9a02d0;"><?php esc_html_e('Manage All', 'microdos4u'); ?> &rarr;</a>
        </div>
        <div class="grid grid-cols-1 gap-3">
            <?php foreach (array_slice($all_subs, 0, 3) as $subscription) : 
                $sub_status = $subscription->get_status();
                $status_label = wcs_get_subscription_status_name($sub_status);
                $status_color_map = [
                    'active' => '#44f80c',
                    'on-hold' => '#f59e0b',
                    'pending' => '#f59e0b',
                    'cancelled' => '#ff4444',
                    'expired' => '#94a3b8',
                    'pending-cancel' => '#f59e0b',
                ];
                $sub_color = isset($status_color_map[$sub_status]) ? $status_color_map[$sub_status] : '#94a3b8';
                $next_payment = $subscription->get_date('next_payment');
                $items = $subscription->get_items();
                $product_name = '';
                foreach ($items as $item) {
                    $product_name = $item->get_name();
                    break;
                }
            ?>
                <div class="flex flex-wrap justify-between items-center p-4 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
                    <div>
                        <div class="text-white font-medium"><?php echo esc_html($product_name); ?></div>
                        <div class="text-xs text-slate-400 mt-1">
                            <?php 
                            printf(
                                esc_html__('Next payment: %s', 'microdos4u'),
                                $next_payment ? esc_html(date('M j, Y', strtotime($next_payment))) : esc_html__('N/A', 'microdos4u')
                            ); 
                            ?>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 mt-2 sm:mt-0">
                        <span class="inline-block px-2 py-1 rounded text-xs font-medium" style="background-color: <?php echo esc_attr($sub_color); ?>20; color: <?php echo esc_attr($sub_color); ?>; border: 1px solid <?php echo esc_attr($sub_color); ?>40;">
                            <?php echo esc_html($status_label); ?>
                        </span>
                        <a href="<?php echo esc_url($subscription->get_view_order_url()); ?>" class="text-sm" style="color: #9a02d0;"><?php esc_html_e('View', 'microdos4u'); ?></a>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>

    <!-- Account Info Summary -->
    <div class="mb-6">
        <h3 class="text-lg font-bold text-white mb-3"><?php esc_html_e('Account Information', 'microdos4u'); ?></h3>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
            <div class="p-4 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
                <div class="text-xs text-slate-400 mb-1"><?php esc_html_e('Email', 'microdos4u'); ?></div>
                <div class="text-white text-sm"><?php echo esc_html($current_user->user_email); ?></div>
            </div>
            <div class="p-4 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
                <div class="text-xs text-slate-400 mb-1"><?php esc_html_e('Member Since', 'microdos4u'); ?></div>
                <div class="text-white text-sm"><?php echo esc_html(date('F j, Y', strtotime($current_user->user_registered))); ?></div>
            </div>
        </div>
    </div>

    <?php else : ?>
    <!-- SUB-PAGE CONTENT (Orders, Subscriptions, etc.) -->
    <div class="p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
        <?php do_action('woocommerce_account_content'); ?>
    </div>
    <?php endif; ?>

</div>
