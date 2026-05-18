<?php
/**
 * Template Name: Easy Creatives
 *
 * Displays creatives with one-click copy buttons.
 * Uses [affiliate_creatives] shortcode with template override.
 * This page is independent of the Affiliate Portal - no React involved.
 *
 * Copy buttons work via PHP template override:
 * - your-theme/affiliatewp/creative.php
 * - your-theme/js/affiliate-copy-buttons.js
 * - your-theme/css/affiliate-copy-buttons.css
 */

get_header();

// Get affiliate data for personalization
$affiliate_id = function_exists( 'affwp_get_affiliate_id' ) ? affwp_get_affiliate_id() : 0;
$user_id      = get_current_user_id();
$display_name = $user_id ? wp_get_current_user()->display_name : 'Affiliate';
?>

<main class="microdos-easy-creatives">

    <!-- Header -->
    <section class="creatives-header">
        <h1>📢 Marketing Creatives — Quick Copy</h1>
        <p class="creatives-intro">
            Hey <strong><?php echo esc_html( $display_name ); ?></strong>! Below are all the banners and links
            you can use to promote microDOS(2). Each one has three copy buttons — just click and paste.
        </p>
    </section>

    <!-- Legend -->
    <section class="creatives-legend">
        <h3>What Each Button Does</h3>
        <div class="legend-grid">
            <div class="legend-item">
                <span class="legend-icon">📋</span>
                <strong>Copy Image URL</strong>
                <span>Copies the banner image address — paste this to upload to social media, email signatures, or website sidebars.</span>
            </div>
            <div class="legend-item">
                <span class="legend-icon">🔗</span>
                <strong>Copy My Link</strong>
                <span>Copies your personal referral URL — share this anywhere. Every click is tracked to your account.</span>
            </div>
            <div class="legend-item">
                <span class="legend-icon">📧</span>
                <strong>Copy for Email</strong>
                <span>Copies ready-to-paste HTML — works in Gmail. Paste with Ctrl+V and the banner appears as a clickable image.</span>
            </div>
        </div>
    </section>

    <!-- Creatives Grid -->
    <section class="creatives-grid-wrapper">
        <h2>Available Creatives</h2>

        <?php
        // Use AffiliateWP's shortcode - this uses our template override
        if ( function_exists( 'affiliate_wp' ) ) {
            echo do_shortcode( '[affiliate_creatives status="active"]' );
        } else {
            echo '<p class="error-msg">AffiliateWP is not active. Please contact support.</p>';
        }
        ?>
    </section>

    <!-- Tips Section -->
    <section class="creatives-tips">
        <h3>💡 Quick Tips</h3>
        <ul>
            <li><strong>Best for social:</strong> Click "Copy Image URL" to get the banner, then upload it directly to Instagram, X/Twitter, or Facebook. Put your referral link in the caption.</li>
            <li><strong>Best for email:</strong> Click "Copy for Email" then paste directly into Gmail. The banner will show as a clickable image that links to your referral URL.</li>
            <li><strong>Best for text:</strong> Click "Copy My Link" and share the URL anywhere — in messages, forums, bio links, anywhere.</li>
            <li><strong>Track results:</strong> Check your <a href="/affiliate-area/">Affiliate Dashboard</a> under the "Referrals" tab to see clicks and commissions.</li>
        </ul>
    </section>

</main>

<style>
/* ===== EASY CREATIVES PAGE STYLES ===== */
.microdos-easy-creatives {
    max-width: 1200px;
    margin: 0 auto;
    padding: 30px 20px;
}

/* Header */
.creatives-header {
    text-align: center;
    padding: 40px 20px;
    margin-bottom: 30px;
    background: linear-gradient(135deg, #1a1030 0%, #0d0818 100%);
    border: 1px solid rgba(68, 248, 12, 0.2);
    border-radius: 12px;
}

.creatives-header h1 {
    color: #44f80c;
    font-size: 32px;
    margin-bottom: 15px;
    font-weight: 700;
}

.creatives-intro {
    color: #c0c0c0;
    font-size: 16px;
    line-height: 1.7;
    max-width: 700px;
    margin: 0 auto;
}

.creatives-intro strong {
    color: #fff;
}

/* Legend */
.creatives-legend {
    margin-bottom: 40px;
    padding: 25px;
    background: rgba(68, 248, 12, 0.03);
    border: 1px solid rgba(68, 248, 12, 0.15);
    border-radius: 10px;
}

.creatives-legend h3 {
    color: #44f80c;
    font-size: 20px;
    margin-bottom: 20px;
    text-align: center;
}

.legend-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 20px;
}

.legend-item {
    display: flex;
    flex-direction: column;
    gap: 6px;
    padding: 18px;
    background: rgba(10, 5, 20, 0.6);
    border: 1px solid rgba(68, 248, 12, 0.15);
    border-radius: 8px;
    text-align: center;
}

.legend-icon {
    font-size: 28px;
}

.legend-item strong {
    color: #fff;
    font-size: 15px;
}

.legend-item span {
    color: #a0a0a0;
    font-size: 13px;
    line-height: 1.5;
}

/* Grid wrapper */
.creatives-grid-wrapper {
    margin-bottom: 40px;
}

.creatives-grid-wrapper h2 {
    color: #fff;
    font-size: 24px;
    margin-bottom: 25px;
    text-align: center;
}

/* AffiliateWP creative grid */
.creatives-grid-wrapper .affwp-creative {
    background: rgba(10, 5, 20, 0.5);
    border: 1px solid rgba(68, 248, 12, 0.2);
    border-radius: 10px;
    padding: 20px;
    margin-bottom: 25px;
    transition: transform 0.2s, box-shadow 0.2s;
}

.creatives-grid-wrapper .affwp-creative:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(68, 248, 12, 0.1);
}

.creatives-grid-wrapper .affwp-creative-desc {
    color: #44f80c;
    font-size: 15px;
    font-weight: 600;
    margin-bottom: 15px;
}

.creatives-grid-wrapper .affwp-creative p {
    margin-bottom: 12px;
}

.creatives-grid-wrapper .affwp-creative img {
    max-width: 100%;
    height: auto;
    border-radius: 6px;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

.creatives-grid-wrapper .affwp-creative pre {
    background: rgba(0, 0, 0, 0.4);
    border: 1px solid rgba(68, 248, 12, 0.1);
    border-radius: 6px;
    padding: 12px;
    overflow-x: auto;
}

.creatives-grid-wrapper .affwp-creative pre code {
    color: #b0b0b0;
    font-size: 12px;
}

/* Tips */
.creatives-tips {
    padding: 25px;
    background: rgba(100, 180, 255, 0.03);
    border: 1px solid rgba(100, 180, 255, 0.15);
    border-radius: 10px;
}

.creatives-tips h3 {
    color: #64b4ff;
    font-size: 20px;
    margin-bottom: 15px;
}

.creatives-tips ul {
    list-style: none;
    padding: 0;
    margin: 0;
}

.creatives-tips li {
    color: #b0b0b0;
    font-size: 14px;
    line-height: 1.7;
    padding: 10px 0 10px 25px;
    position: relative;
    border-bottom: 1px solid rgba(255, 255, 255, 0.05);
}

.creatives-tips li:last-child {
    border-bottom: none;
}

.creatives-tips li::before {
    content: "→";
    position: absolute;
    left: 0;
    color: #44f80c;
    font-weight: bold;
}

.creatives-tips li strong {
    color: #e0e0e0;
}

.creatives-tips a {
    color: #44f80c;
    text-decoration: none;
}

.creatives-tips a:hover {
    text-decoration: underline;
}

/* Error */
.error-msg {
    color: #e74c3c;
    text-align: center;
    padding: 40px;
    font-size: 16px;
}
</style>

<?php get_footer(); ?>
