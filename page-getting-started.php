<?php
/**
 * Template Name: Getting Started
 *
 * The template for the affiliate Getting Started page.
 * Dynamically replaces hardcoded commission rates with live AffiliateWP rate.
 *
 * @package microDOS4U
 */

get_header();

// Get the LIVE commission rate from AffiliateWP
$live_rate = 30; // fallback
if (function_exists('affiliate_wp')) {
    $affwp = affiliate_wp();
    if ($affwp && isset($affwp->settings) && method_exists($affwp->settings, 'get')) {
        $rate = $affwp->settings->get('referral_rate', 30);
        $live_rate = floatval($rate) > 0 ? floatval($rate) : 30;
    }
}
$rate_display = ($live_rate == intval($live_rate)) ? intval($live_rate) : number_format($live_rate, 1);
?>

<main id="primary" class="site-main">
    <section class="py-20" style="background-color: #0a0514; min-height: 60vh;">
        <div class="container mx-auto px-4 sm:px-6" style="max-width: 900px;">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    // Capture content output
                    ob_start();
                    the_content();
                    $content = ob_get_clean();

                    // Replace any hardcoded rates with live rate
                    $content = str_replace('30%', $rate_display . '%', $content);
                    $content = str_replace('0%', $rate_display . '%', $content);
                    $content = str_replace('[affiliate_rate]', $rate_display, $content);
                    $content = str_replace('[affiliate_commission_rate]', $rate_display, $content);

                    // Fix payment date
                    $content = str_replace('1st of each month', '15th of each month', $content);
                    $content = str_replace('1st of every month', '15th of every month', $content);

                    echo $content;
                endwhile;
            endif;
            ?>
        </div>
    </section>
</main>

<?php
get_footer();
