<?php
/**
 * My Account Template - Custom Styled
 *
 * Overrides WooCommerce default to match microDOS4U theme.
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="woocommerce-MyAccount-content" style="color: #94a3b8; line-height: 1.7;">

    <!-- Welcome Message -->
    <div class="mb-8 p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
        <div class="flex flex-wrap justify-between items-start gap-4">
            <div>
                <h2 class="text-2xl font-bold text-white mb-2">
                    <?php
                    $current_user = wp_get_current_user();
                    printf(esc_html__('Welcome, %s', 'woocommerce'), esc_html($current_user->display_name));
                    ?>
                </h2>
                <p class="text-slate-400">
                    From your account dashboard you can view your orders, manage your subscriptions, 
                    and edit your account details.
                </p>
            </div>
            <a href="<?php echo esc_url(wp_logout_url(home_url())); ?>" 
               class="inline-block px-4 py-2 rounded-lg font-medium text-sm transition-all duration-200"
               style="background-color: #150f24; color: #ff4444; border: 1px solid #ff4444;"
               onmouseover="this.style.backgroundColor='#ff4444'; this.style.color='#fff';"
               onmouseout="this.style.backgroundColor='#150f24'; this.style.color='#ff4444';">
                <?php esc_html_e('Log Out', 'microdos4u'); ?>
            </a>
        </div>
    </div>

    <!-- Dashboard Stats -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
        <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-3xl font-bold mb-2" style="color: #44f80c;">
                <?php echo count(wc_get_orders(['customer_id' => get_current_user_id(), 'limit' => -1])); ?>
            </div>
            <div class="text-slate-400">Orders</div>
        </div>
        <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-3xl font-bold mb-2" style="color: #9a02d0;">
                <?php
                if (function_exists('wcs_get_users_subscriptions')) {
                    echo count(wcs_get_users_subscriptions(get_current_user_id()));
                } else {
                    echo '0';
                }
                ?>
            </div>
            <div class="text-slate-400">Subscriptions</div>
        </div>
        <div class="p-6 rounded-lg text-center" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <div class="text-3xl font-bold mb-2" style="color: #ff66c4;">
                <?php
                $customer = new WC_Customer(get_current_user_id());
                echo wp_kses_post($customer->get_total_spent());
                ?>
            </div>
            <div class="text-slate-400">Total Spent</div>
        </div>
    </div>

    <!-- Navigation -->
    <div class="mb-8">
        <nav class="woocommerce-MyAccount-navigation">
            <ul class="flex flex-wrap gap-2" style="list-style: none; padding: 0;">
                <?php
                $menu_items = wc_get_account_menu_items();
                $current_endpoint = WC()->query->get_current_endpoint();
                foreach ($menu_items as $endpoint => $label) :
                    $is_active = $current_endpoint === $endpoint;
                    $item_classes = 'inline-block px-4 py-2 rounded-lg font-medium transition-all duration-2';
                    if ($is_active) {
                        $item_classes .= ' is-active';
                    }
                ?>
                    <li class="woocommerce-MyAccount-navigation-link woocommerce-MyAccount-navigation-link--<?php echo esc_attr($endpoint); ?>" style="margin: 0;">
                        <a href="<?php echo esc_url(wc_get_account_endpoint_url($endpoint)); ?>" 
                           class="<?php echo esc_attr($item_classes); ?>"
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

    <!-- Content -->
    <div class="woocommerce-MyAccount-content-wrapper p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
        <?php
            do_action('woocommerce_account_content');
        ?>
    </div>

</div>
