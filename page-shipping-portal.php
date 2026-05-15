<?php
/**
 * Template Name: Shipping Portal
 *
 * Standalone shipping dashboard for the shipping department.
 * Does NOT look like WordPress admin. Custom dark-themed interface.
 * Accessible at: /shipping-portal/ (create a WordPress page with this template)
 *
 * @package microDOS4U
 */

// Require login
if (!is_user_logged_in()) {
    auth_redirect();
    exit;
}

// Require WooCommerce manager role
if (!current_user_can('manage_woocommerce')) {
    wp_die('<h1>Access Denied</h1><p>You do not have permission to access the shipping portal.</p>', 403);
}

// Handle mark-as-shipped
$notice = '';
if (isset($_POST['microdos_portal_ship']) && check_admin_referer('microdos_portal_ship_nonce')) {
    $order_id = intval($_POST['order_id']);
    $tracking = sanitize_text_field($_POST['tracking_number'] ?? '');
    $order = wc_get_order($order_id);
    if ($order) {
        if ($tracking) {
            $order->update_meta_data('_microdos_tracking_number', $tracking);
            $order->update_meta_data('_microdos_tracking_carrier', 'usps');
        }
        $order->update_status('shipped', __('Marked as shipped via Shipping Portal.', 'microdos4u'));
        $order->save();
        $notice = '<div class="portal-notice portal-success">Order #' . esc_html($order->get_order_number()) . ' marked as shipped. Customer email sent.</div>';
    }
}

// Stats
$processing_ids = wc_get_orders(['status' => 'processing', 'limit' => -1, 'return' => 'ids']);
$shipped_ids    = wc_get_orders(['status' => 'shipped', 'limit' => -1, 'return' => 'ids']);

$today_start = date('Y-m-d 00:00:00');
$today_end   = date('Y-m-d 23:59:59');
$shipped_today = wc_get_orders([
    'status'        => ['shipped', 'completed'],
    'date_modified' => $today_start . '...' . $today_end,
    'limit'         => -1,
    'return'        => 'ids'
]);

// Tab
$tab  = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'ready';
$page = isset($_GET['portal_page']) ? max(1, intval($_GET['portal_page'])) : 1;
$per  = 20;

// Get orders
if ($tab === 'ready') {
    $orders = wc_get_orders([
        'status'  => 'processing',
        'limit'   => $per,
        'page'    => $page,
        'orderby' => 'date',
        'order'   => 'ASC',
    ]);
    $total = count($processing_ids);
} else {
    $orders = wc_get_orders([
        'status'  => ['shipped', 'completed'],
        'limit'   => $per,
        'page'    => $page,
        'orderby' => 'date',
        'order'   => 'DESC',
    ]);
    $total = count($shipped_ids);
}

$total_pages = ceil($total / $per);

// Helper
function portal_tracking_url($tracking, $carrier = 'usps') {
    if (!$tracking) return '';
    switch ($carrier) {
        case 'usps':  return 'https://tools.usps.com/go/TrackConfirmAction?tLabels=' . esc_attr($tracking);
        case 'ups':   return 'https://www.ups.com/track?tracknum=' . esc_attr($tracking);
        case 'fedex': return 'https://www.fedex.com/fedextrack/?trknbr=' . esc_attr($tracking);
        default:      return '';
    }
}

wp_head();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Shipping Portal - microDOS(2)</title>
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }

body {
    background: #0a0514;
    color: #e2e8f0;
    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;
    font-size: 14px;
    line-height: 1.5;
    min-height: 100vh;
}

/* Header */
.portal-header {
    background: linear-gradient(135deg, #0a0514 0%, #1a1040 100%);
    border-bottom: 1px solid #1f2b47;
    padding: 0 24px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    position: sticky;
    top: 0;
    z-index: 100;
}
.portal-header-left {
    display: flex;
    align-items: center;
    gap: 12px;
}
.portal-logo {
    font-size: 20px;
    font-weight: 700;
    color: #44f80c;
    letter-spacing: 1px;
}
.portal-subtitle {
    font-size: 12px;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 1px;
    border-left: 1px solid #1f2b47;
    padding-left: 12px;
}
.portal-user {
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 13px;
    color: #94a3b8;
}
.portal-user a {
    color: #94a3b8;
    text-decoration: none;
    transition: color 0.2s;
}
.portal-user a:hover { color: #44f80c; }

/* Container */
.portal-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 24px;
}

/* Notice */
.portal-notice {
    padding: 14px 20px;
    border-radius: 8px;
    margin-bottom: 20px;
    font-size: 14px;
    font-weight: 500;
}
.portal-success {
    background: #44f80c15;
    border: 1px solid #44f80c40;
    color: #44f80c;
}

/* Stats */
.portal-stats {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: 16px;
    margin-bottom: 28px;
}
.portal-stat {
    background: #150f24;
    border: 1px solid #1f2b47;
    border-radius: 10px;
    padding: 20px;
    border-left: 4px solid #44f80c;
    transition: transform 0.2s, border-color 0.2s;
}
.portal-stat:hover {
    transform: translateY(-2px);
    border-color: #44f80c;
}
.portal-stat.ready { border-left-color: #ff66c4; }
.portal-stat.ready:hover { border-color: #ff66c4; }
.portal-stat.today { border-left-color: #44f80c; }
.portal-stat.total { border-left-color: #38bdf8; }
.portal-stat-num {
    font-size: 36px;
    font-weight: 700;
    color: #fff;
    line-height: 1;
    margin-bottom: 6px;
}
.portal-stat.ready .portal-stat-num { color: #ff66c4; }
.portal-stat-label {
    font-size: 11px;
    color: #94a3b8;
    text-transform: uppercase;
    letter-spacing: 1.5px;
}

/* Tabs */
.portal-tabs {
    display: flex;
    gap: 2px;
    margin-bottom: 24px;
    background: #150f24;
    border-radius: 8px;
    padding: 4px;
    width: fit-content;
}
.portal-tab {
    padding: 10px 24px;
    border-radius: 6px;
    text-decoration: none;
    color: #94a3b8;
    font-size: 13px;
    font-weight: 600;
    transition: all 0.2s;
    display: flex;
    align-items: center;
    gap: 8px;
}
.portal-tab:hover {
    color: #e2e8f0;
    background: #1a104040;
}
.portal-tab.active {
    background: #1a1040;
    color: #44f80c;
    border: 1px solid #2d2255;
}
.portal-tab-badge {
    background: #ef4444;
    color: #fff;
    border-radius: 10px;
    padding: 2px 8px;
    font-size: 11px;
    font-weight: 700;
    min-width: 20px;
    text-align: center;
}

/* Table */
.portal-table-wrap {
    background: #150f24;
    border: 1px solid #1f2b47;
    border-radius: 10px;
    overflow: hidden;
}
.portal-table-header {
    padding: 16px 20px;
    border-bottom: 1px solid #1f2b47;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.portal-table-title {
    font-size: 16px;
    font-weight: 700;
    color: #e2e8f0;
}
.portal-table-count {
    font-size: 12px;
    color: #64748b;
}

.portal-table {
    width: 100%;
    border-collapse: collapse;
}
.portal-table th {
    background: #0a0514;
    color: #94a3b8;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    padding: 12px 16px;
    text-align: left;
    border-bottom: 1px solid #1f2b47;
}
.portal-table td {
    padding: 14px 16px;
    border-bottom: 1px solid #1f2b47;
    vertical-align: top;
    font-size: 13px;
}
.portal-table tbody tr {
    background: #150f24;
    transition: background 0.15s;
}
.portal-table tbody tr:nth-child(even) {
    background: #120d20;
}
.portal-table tbody tr:hover {
    background: #1a1040;
}
.portal-table tbody tr:last-child td {
    border-bottom: none;
}

/* Cell content */
.cell-order a {
    color: #44f80c;
    font-weight: 700;
    text-decoration: none;
    font-size: 14px;
}
.cell-order a:hover { text-decoration: underline; }
.cell-date {
    color: #94a3b8;
    font-size: 12px;
}
.cell-name {
    font-weight: 600;
    color: #e2e8f0;
}
.cell-email {
    color: #64748b;
    font-size: 11px;
}
.cell-items {
    font-size: 12px;
    color: #e2e8f0;
}
.cell-items .qty {
    color: #64748b;
}
.cell-total {
    color: #44f80c;
    font-weight: 700;
    font-size: 14px;
}
.cell-address {
    font-size: 12px;
    color: #94a3b8;
    line-height: 1.6;
}
.cell-tracking a {
    color: #44f80c;
    font-size: 12px;
    text-decoration: underline;
}
.cell-tracking .no-track {
    color: #64748b;
    font-size: 12px;
}

/* Tracking input */
.portal-tracking-input {
    width: 100%;
    padding: 8px 10px;
    border: 1px solid #1f2b47;
    background: #0a0514;
    color: #e2e8f0;
    border-radius: 6px;
    font-size: 12px;
    font-family: 'Courier New', monospace;
    transition: border-color 0.2s;
}
.portal-tracking-input:focus {
    outline: none;
    border-color: #44f80c;
    box-shadow: 0 0 0 2px #44f80c20;
}

/* Buttons */
.portal-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    padding: 8px 16px;
    border: none;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.2s;
    text-decoration: none;
    white-space: nowrap;
}
.portal-btn-ship {
    background: #44f80c;
    color: #0a0514;
    width: 100%;
}
.portal-btn-ship:hover {
    background: #3de00b;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px #44f80c30;
}
.portal-btn-view {
    background: #1a1040;
    color: #94a3b8;
    border: 1px solid #2d2255;
    font-size: 11px;
    padding: 6px 12px;
}
.portal-btn-view:hover {
    background: #2d2255;
    color: #e2e8f0;
}

/* Status badges */
.badge {
    display: inline-flex;
    align-items: center;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 700;
    text-transform: uppercase;
}
.badge-processing {
    background: #ff66c420;
    color: #ff66c4;
}
.badge-shipped {
    background: #44f80c20;
    color: #44f80c;
}
.badge-completed {
    background: #38bdf820;
    color: #38bdf8;
}

/* Pagination */
.portal-pagination {
    display: flex;
    justify-content: center;
    gap: 4px;
    padding: 16px;
    border-top: 1px solid #1f2b47;
}
.portal-pagination a,
.portal-pagination span {
    padding: 8px 14px;
    border-radius: 6px;
    font-size: 13px;
    text-decoration: none;
    min-width: 36px;
    text-align: center;
}
.portal-pagination a {
    background: #150f24;
    color: #94a3b8;
    border: 1px solid #1f2b47;
}
.portal-pagination a:hover {
    background: #1a1040;
    color: #e2e8f0;
    border-color: #2d2255;
}
.portal-pagination span.current {
    background: #44f80c;
    color: #0a0514;
    font-weight: 700;
}

/* Empty */
.portal-empty {
    padding: 60px 20px;
    text-align: center;
}
.portal-empty-icon {
    font-size: 48px;
    margin-bottom: 16px;
}
.portal-empty p {
    color: #64748b;
    font-size: 16px;
}

/* Mobile */
@media (max-width: 768px) {
    .portal-stats { grid-template-columns: repeat(2, 1fr); }
    .portal-table th:nth-child(4),
    .portal-table td:nth-child(4),
    .portal-table th:nth-child(6),
    .portal-table td:nth-child(6) { display: none; }
    .portal-container { padding: 12px; }
    .portal-header { padding: 0 12px; }
}
</style>
</head>
<body>

<div class="portal-header">
    <div class="portal-header-left">
        <span class="portal-logo">microDOS(2)</span>
        <span class="portal-subtitle">Shipping Portal</span>
    </div>
    <div class="portal-user">
        <span><?php echo esc_html(wp_get_current_user()->display_name); ?></span>
        <a href="<?php echo wp_logout_url(home_url()); ?>">Logout</a>
    </div>
</div>

<div class="portal-container">

    <?php echo $notice; ?>

    <!-- Stats -->
    <div class="portal-stats">
        <div class="portal-stat ready">
            <div class="portal-stat-num"><?php echo count($processing_ids); ?></div>
            <div class="portal-stat-label">Ready to Ship</div>
        </div>
        <div class="portal-stat today">
            <div class="portal-stat-num"><?php echo count($shipped_today); ?></div>
            <div class="portal-stat-label">Shipped Today</div>
        </div>
        <div class="portal-stat total">
            <div class="portal-stat-num"><?php echo count($shipped_ids); ?></div>
            <div class="portal-stat-label">Total Shipped</div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="portal-tabs">
        <a href="?tab=ready" class="portal-tab <?php echo $tab === 'ready' ? 'active' : ''; ?>">
            Ready to Ship
            <?php if (count($processing_ids) > 0) : ?>
                <span class="portal-tab-badge"><?php echo count($processing_ids); ?></span>
            <?php endif; ?>
        </a>
        <a href="?tab=shipped" class="portal-tab <?php echo $tab === 'shipped' ? 'active' : ''; ?>">
            Shipped Orders
        </a>
    </div>

    <!-- Table -->
    <div class="portal-table-wrap">
        <div class="portal-table-header">
            <span class="portal-table-title">
                <?php echo $tab === 'ready' ? 'Orders Ready to Ship' : 'Shipped Orders'; ?>
            </span>
            <span class="portal-table-count"><?php echo $total; ?> orders</span>
        </div>

        <?php if (empty($orders)) : ?>
            <div class="portal-empty">
                <div class="portal-empty-icon"><?php echo $tab === 'ready' ? '📦' : '✅'; ?></div>
                <p><?php echo $tab === 'ready' ? 'All caught up! No orders waiting to ship.' : 'No shipped orders yet.'; ?></p>
            </div>
        <?php else : ?>
            <table class="portal-table">
                <thead>
                    <tr>
                        <th style="width:70px;">Order</th>
                        <th style="width:120px;">Date</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th style="width:80px;">Total</th>
                        <th style="width:160px;">Ship To</th>
                        <?php if ($tab === 'ready') : ?>
                            <th style="width:180px;">Tracking #</th>
                            <th style="width:100px;"></th>
                        <?php else : ?>
                            <th style="width:180px;">Tracking</th>
                            <th style="width:90px;">Status</th>
                        <?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $order) :
                        $tracking = $order->get_meta('_microdos_tracking_number', true);
                        $t_url    = portal_tracking_url($tracking, 'usps');
                        $address  = $order->get_shipping_address_1();
                        $city     = $order->get_shipping_city();
                        $state    = $order->get_shipping_state();
                        $zip      = $order->get_shipping_postcode();
                    ?>
                    <tr>
                        <td class="cell-order">
                            <a href="<?php echo esc_url($order->get_edit_order_url()); ?>" target="_blank">#<?php echo esc_html($order->get_order_number()); ?></a>
                        </td>
                        <td class="cell-date"><?php echo esc_html($order->get_date_created()->date('M j, Y')); ?><br><?php echo esc_html($order->get_date_created()->date('g:i A')); ?></td>
                        <td>
                            <div class="cell-name"><?php echo esc_html($order->get_formatted_billing_full_name()); ?></div>
                            <div class="cell-email"><?php echo esc_html($order->get_billing_email()); ?></div>
                        </td>
                        <td class="cell-items">
                            <?php foreach ($order->get_items() as $item) : ?>
                                <?php echo esc_html($item->get_name()); ?> <span class="qty">x<?php echo $item->get_quantity(); ?></span><br>
                            <?php endforeach; ?>
                        </td>
                        <td class="cell-total"><?php echo $order->get_formatted_order_total(); ?></td>
                        <td class="cell-address">
                            <?php echo esc_html($address); ?><br>
                            <?php echo esc_html("{$city}, {$state} {$zip}"); ?>
                        </td>

                        <?php if ($tab === 'ready') : ?>
                            <td>
                                <form method="post" style="margin:0;">
                                    <?php wp_nonce_field('microdos_portal_ship_nonce'); ?>
                                    <input type="hidden" name="microdos_portal_ship" value="1">
                                    <input type="hidden" name="order_id" value="<?php echo $order->get_id(); ?>">
                                    <input type="text" name="tracking_number" class="portal-tracking-input" placeholder="940011..." value="<?php echo esc_attr($tracking); ?>">
                            </td>
                            <td>
                                    <button type="submit" class="portal-btn portal-btn-ship">Mark Shipped</button>
                                </form>
                            </td>
                        <?php else : ?>
                            <td class="cell-tracking">
                                <?php if ($tracking && $t_url) : ?>
                                    <a href="<?php echo esc_url($t_url); ?>" target="_blank"><?php echo esc_html($tracking); ?></a>
                                <?php else : ?>
                                    <span class="no-track">No tracking</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?php echo $order->get_status(); ?>"><?php echo ucfirst($order->get_status()); ?></span>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>

        <!-- Pagination -->
        <?php if ($total_pages > 1) : ?>
        <div class="portal-pagination">
            <?php for ($i = 1; $i <= $total_pages; $i++) : ?>
                <?php if ($i === $page) : ?>
                    <span class="current"><?php echo $i; ?></span>
                <?php else : ?>
                    <a href="?tab=<?php echo $tab; ?>&portal_page=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    </div>

</div>

</body>
</html>
<?php wp_footer(); ?>
