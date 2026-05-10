<?php
/**
 * My Account Template - Enhanced Dashboard
 *
 * Shows orders, subscription status, next billing date,
 * and action buttons (pause/cancel/switch).
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}

$current_user = wp_get_current_user();
$customer_orders = wc_get_orders([
    'customer_id' => get_current_user_id(),
    'limit' => -1,
    'status' => ['wc-processing', 'wc-completed', 'wc-on-hold', 'wc-pending']
]);

$subscriptions = function_exists('wcs_get_users_subscriptions') 
    ? wcs_get_users_subscriptions(get_current_user_id()) 
    : [];
?>

<div class="woocommerce-MyAccount-content" style="color: #94a3b8; line-height: 1.7;">

    <!-- Welcome Header -->
    <div class="mb-8 p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
        <h2 class="text-2xl font-bold text-white mb-2">
            <?php printf(esc_html__('Welcome, %s', 'woocommerce'), esc_html($current_user->display_name)); ?>
        </h2>
        <p class="text-slate-400">
            Manage your orders, subscriptions, and account details.
        </p>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-3xl font-bold mb-2" style="color: #44f80c;">
                <?php echo count($customer_orders); ?>
            </div>
            <div class="text-slate-400">Orders</div>
        </div>
        <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-3xl font-bold mb-2" style="color: #9a02d0;">
                <?php echo count($subscriptions); ?>
            </div>
            <div class="text-slate-400">Subscriptions</div>
        </div>
        <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-3xl font-bold mb-2" style="color: #ff66c4;">
                <?php 
                $customer = new WC_Customer(get_current_user_id());
                echo wp_kses_post(wc_price($customer->get_total_spent())); 
                ?>
            </div>
            <div class="text-slate-400">Total Spent</div>
        </div>
    </div>

    <!-- Orders Section -->
    <div class="mb-8">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center">
            <span style="color: #44f80c; margin-right: 8px;">📦</span> Your Orders
        </h3>

        <?php if (empty($customer_orders)) : ?>
            <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
                <p class="text-slate-400">No orders yet. <a href="/shop/" style="color: #44f80c;">Start shopping →</a></p>
            </div>
        <?php else : ?>
            <div class="overflow-x-auto rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
                <table class="w-full text-left">
                    <thead>
                        <tr style="border-bottom: 2px solid #1f2b47;">
                            <th class="py-3 px-4" style="color: #fff;">Order #</th>
                            <th class="py-3 px-4" style="color: #fff;">Date</th>
                            <th class="py-3 px-4" style="color: #fff;">Status</th>
                            <th class="py-3 px-4" style="color: #fff;">Total</th>
                            <th class="py-3 px-4" style="color: #fff;">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach (array_slice($customer_orders, 0, 5) as $order) : ?>
                            <tr style="border-bottom: 1px solid #1a1329;">
                                <td class="py-3 px-4">
                                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>" style="color: #38bdf8; font-weight: 600;">
                                        #<?php echo esc_html($order->get_order_number()); ?>
                                    </a>
                                </td>
                                <td class="py-3 px-4"><?php echo esc_html(date_i18n('M j, Y', strtotime($order->get_date_created()))); ?></td>
                                <td class="py-3 px-4">
                                    <span class="px-2 py-1 rounded text-xs font-medium" 
                                          style="background-color: <?php echo $order->get_status() === 'completed' ? '#44f80c20' : ($order->get_status() === 'processing' ? '#f59e0b20' : '#94a3b820'); ?>; 
                                                 color: <?php echo $order->get_status() === 'completed' ? '#44f80c' : ($order->get_status() === 'processing' ? '#f59e0b' : '#94a3b8'); ?>;">
                                        <?php echo esc_html(ucfirst($order->get_status())); ?>
                                    </span>
                                </td>
                                <td class="py-3 px-4" style="color: #fff;"><?php echo wp_kses_post($order->get_formatted_order_total()); ?></td>
                                <td class="py-3 px-4">
                                    <a href="<?php echo esc_url($order->get_view_order_url()); ?>" style="color: #38bdf8; font-size: 13px;">View →</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (count($customer_orders) > 5) : ?>
                    <div class="p-4 text-center" style="border-top: 1px solid #1f2b47;">
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url('orders')); ?>" style="color: #44f80c;">View all <?php echo count($customer_orders); ?> orders →</a>
                    </div>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Subscriptions Section -->
    <div class="mb-8">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center">
            <span style="color: #9a02d0; margin-right: 8px;">🔄</span> Your Subscriptions
        </h3>

        <?php if (empty($subscriptions)) : ?>
            <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
                <p class="text-slate-400">No active subscriptions. <a href="/shop/" style="color: #9a02d0;">Browse subscription products →</a></p>
            </div>
        <?php else : ?>
            <?php foreach ($subscriptions as $subscription) : 
                $status = $subscription->get_status();
                $status_colors = [
                    'active' => ['#44f80c20', '#44f80c'],
                    'on-hold' => ['#f59e0b20', '#f59e0b'],
                    'pending' => ['#f59e0b20', '#f59e0b'],
                    'cancelled' => ['#dc262620', '#dc2626'],
                    'expired' => ['#94a3b820', '#94a3b8'],
                ];
                $bg_color = $status_colors[$status][0] ?? '#94a3b820';
                $text_color = $status_colors[$status][1] ?? '#94a3b8';
                $items = $subscription->get_items();
                $next_payment = $subscription->get_date('next_payment');
            ?>
                <div class="p-6 rounded-lg mb-4" style="background-color: #150f24; border: 1px solid #1f2b47;">
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-4">
                        <div>
                            <span class="px-3 py-1 rounded-full text-xs font-bold" style="background-color: <?php echo $bg_color; ?>; color: <?php echo $text_color; ?>;">
                                <?php echo esc_html(ucfirst($status)); ?>
                            </span>
                            <span class="ml-3 text-white font-semibold">
                                Subscription #<?php echo esc_html($subscription->get_order_number()); ?>
                            </span>
                        </div>
                        <div class="mt-2 md:mt-0 text-slate-400 text-sm">
                            Started: <?php echo esc_html(date_i18n('M j, Y', strtotime($subscription->get_date('start_date')))); ?>
                        </div>
                    </div>

                    <!-- Product Details -->
                    <?php foreach ($items as $item) : ?>
                        <div class="mb-4 pb-4" style="border-bottom: 1px solid #1a1329;">
                            <p class="text-white font-semibold text-lg"><?php echo esc_html($item->get_name()); ?></p>
                            <p class="text-slate-400 text-sm">
                                <?php echo wp_kses_post($subscription->get_formatted_order_total()); ?> 
                                every <?php echo esc_html($subscription->get_billing_interval()); ?> 
                                <?php echo esc_html($subscription->get_billing_period()); ?>(s)
                            </p>
                        </div>
                    <?php endforeach; ?>

                    <!-- Next Payment -->
                    <?php if ($next_payment && $status === 'active') : ?>
                        <div class="mb-4 p-3 rounded" style="background-color: #0a0514;">
                            <p class="text-sm">
                                <span class="text-slate-400">Next payment:</span>
                                <span class="font-semibold" style="color: #ff66c4;">
                                    <?php echo esc_html(date_i18n('F j, Y', strtotime($next_payment))); ?>
                                </span>
                            </p>
                        </div>
                    <?php endif; ?>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 mt-4">
                        <?php 
                        $actions = wcs_get_all_user_actions_for_subscription($subscription, get_current_user_id());
                        foreach ($actions as $key => $action) : 
                            $btn_colors = [
                                'cancel' => ['#dc2626', '#fff'],
                                'suspend' => ['#f59e0b', '#0a0514'],
                                'reactivate' => ['#44f80c', '#0a0514'],
                                'change_payment_method' => ['#150f24', '#38bdf8'],
                            ];
                            $btn_bg = $btn_colors[$key][0] ?? '#150f24';
                            $btn_text = $btn_colors[$key][1] ?? '#fff';
                            $btn_border = $key === 'change_payment_method' ? '1px solid #1f2b47' : 'none';
                        ?>
                            <a href="<?php echo esc_url($action['url']); ?>" 
                               class="px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200 hover:opacity-80"
                               style="background-color: <?php echo $btn_bg; ?>; color: <?php echo $btn_text; ?>; border: <?php echo $btn_border; ?>;">
                                <?php echo esc_html($action['name']); ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- WooCommerce Default Content (for other endpoints) -->
    <div class="mt-8">
        <?php do_action('woocommerce_account_content'); ?>
    </div>

</div>
