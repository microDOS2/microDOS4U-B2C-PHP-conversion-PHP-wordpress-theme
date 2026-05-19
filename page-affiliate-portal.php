<?php
/**
 * Template Name: Affiliate Portal
 *
 * The main affiliate dashboard. Logged-in affiliates land here after login.
 * Shows referral URL, quick stats, and all creatives with one-click copy buttons.
 *
 * @package microDOS4U
 */

// SECURITY: Only logged-in affiliates may access this page
if (!is_user_logged_in()) {
    wp_redirect(esc_url(home_url('/affiliate-area/')));
    exit;
}

if (!function_exists('affwp_is_affiliate') || !affwp_is_affiliate()) {
    wp_redirect(esc_url(home_url('/affiliate-area/')));
    exit;
}

// Get affiliate data
$affiliate_id   = affwp_get_affiliate_id();
$user           = wp_get_current_user();
$display_name   = $user->display_name;
$referral_url   = affwp_get_affiliate_referral_url();
$affiliate_area = get_permalink(get_page_by_path('affiliate-area')) ?: home_url('/affiliate-area/');

// Get stats
$earnings        = affiliate_wp()->referrals->get_earnings($affiliate_id);
$referral_count  = affiliate_wp()->referrals->get_referral_count($affiliate_id);
$visit_count     = affiliate_wp()->visits->get_visit_count($affiliate_id);
$conversion_rate = $visit_count > 0 ? round(($referral_count / $visit_count) * 100, 1) : 0;

get_header();
?>

<main id="primary" class="site-main" style="background-color: #0a0514; min-height: 100vh;">

    <!-- Welcome Header -->
    <section class="portal-hero" style="background-color: #0a0514; border-bottom: 1px solid #1f2b47;">
        <div class="container mx-auto px-4 py-8 max-w-6xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-white mb-1">
                        Welcome back, <span style="color: #44f80c;"><?php echo esc_html($display_name); ?></span>
                    </h1>
                    <p class="text-slate-400 text-sm">Affiliate ID: <strong class="text-white">#<?php echo esc_html($affiliate_id); ?></strong></p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="<?php echo esc_url($affiliate_area); ?>" 
                       style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #150f24; border: 1px solid #1f2b47; border-radius: 8px; color: #94a3b8; text-decoration: none; font-size: 13px; font-weight: 500; transition: all 0.2s;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                        Detailed Reports
                    </a>
                    <a href="<?php echo esc_url(wp_logout_url(home_url('/affiliate-area/'))); ?>" 
                       style="display: inline-flex; align-items: center; gap: 6px; padding: 10px 20px; background: #150f24; border: 1px solid #ff444440; border-radius: 8px; color: #ff6b6b; text-decoration: none; font-size: 13px; font-weight: 500; transition: all 0.2s;">
                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                        Log Out
                    </a>
                </div>
            </div>
        </div>
    </section>

    <!-- Quick Stats -->
    <section class="portal-stats" style="background-color: #0a0514;">
        <div class="container mx-auto px-4 py-6 max-w-6xl">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <div class="p-5 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
                    <p class="text-xs text-slate-400 mb-1 uppercase tracking-wider">Total Earnings</p>
                    <p class="text-2xl font-bold" style="color: #44f80c;">$<?php echo number_format($earnings, 2); ?></p>
                </div>
                <div class="p-5 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
                    <p class="text-xs text-slate-400 mb-1 uppercase tracking-wider">Referrals</p>
                    <p class="text-2xl font-bold text-white"><?php echo number_format($referral_count); ?></p>
                </div>
                <div class="p-5 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
                    <p class="text-xs text-slate-400 mb-1 uppercase tracking-wider">Visits</p>
                    <p class="text-2xl font-bold text-white"><?php echo number_format($visit_count); ?></p>
                </div>
                <div class="p-5 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
                    <p class="text-xs text-slate-400 mb-1 uppercase tracking-wider">Conversion Rate</p>
                    <p class="text-2xl font-bold" style="color: #ff66c4;"><?php echo $conversion_rate; ?>%</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Referral URL -->
    <section class="portal-url" style="background-color: #0a0514;">
        <div class="container mx-auto px-4 py-4 max-w-6xl">
            <div class="p-5 rounded-lg" style="background-color: #150f24; border: 1px solid #44f80c40;">
                <p class="text-xs text-slate-400 mb-2 uppercase tracking-wider">Your Referral URL</p>
                <div class="flex flex-col md:flex-row gap-3">
                    <input type="text" 
                           value="<?php echo esc_url($referral_url); ?>" 
                           readonly 
                           class="flex-1 px-4 py-3 rounded-md text-sm"
                           style="background: #0a0514; border: 1px solid #1f2b47; color: #e2e8f0; font-family: monospace;"
                           id="portal-referral-url"
                    >
                    <button type="button" 
                            class="portal-copy-btn"
                            data-copy-target="portal-referral-url"
                            style="display: inline-flex; align-items: center; gap: 8px; padding: 12px 24px; background: #44f80c; color: #0a0514; font-weight: 700; border: none; border-radius: 8px; cursor: pointer; font-size: 14px; white-space: nowrap;">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
                        Copy Link
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- Creatives -->
    <section class="portal-creatives" style="background-color: #0a0514;">
        <div class="container mx-auto px-4 py-8 max-w-6xl">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-xl font-bold text-white">Marketing Creatives</h2>
                <span class="text-xs text-slate-400" style="color: #94a3b8;">Click any button to copy</span>
            </div>
            <?php echo do_shortcode('[affiliate_creatives status="active"]'); ?>
        </div>
    </section>

    <!-- Footer Navigation -->
    <section class="portal-footer" style="background-color: #0a0514; border-top: 1px solid #1f2b47;">
        <div class="container mx-auto px-4 py-6 max-w-6xl">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <a href="<?php echo esc_url($affiliate_area); ?>" 
                   style="display: inline-flex; align-items: center; gap: 8px; color: #38bdf8; text-decoration: none; font-size: 14px; font-weight: 500;">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
                    View Detailed Reports (full dashboard)
                </a>
                <p class="text-xs text-slate-500">microDOS(2) Affiliate Portal</p>
            </div>
        </div>
    </section>

</main>

<?php get_footer(); ?>
