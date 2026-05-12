<?php
/**
 * Template Name: Affiliate Area - Custom
 *
 * Custom affiliate area page with commission structure,
 * payment info, and cookie explanation.
 *
 * @package microDOS4U
 */

get_header();
?>

<main id="primary" class="site-main">

    <section class="affiliate-hero py-16" style="background-color: #0a0514;">
        <div class="container mx-auto px-4 text-center">
            <h1 class="text-4xl font-bold text-white mb-4">Affiliate Program</h1>
            <p class="text-slate-400 text-lg max-w-2xl mx-auto">Partner with microDOS(2) and earn commissions on every referral. Share your unique link or QR code and get paid for every purchase and subscription renewal.</p>
        </div>
    </section>

    <section class="affiliate-content py-12" style="background-color: #150f24;">
        <div class="container mx-auto px-4 max-w-4xl">

            <!-- Commission Structure -->
            <div class="mb-10 p-6 rounded-lg" style="background-color: #0a0514; border: 1px solid #1f2b47;">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                    Commission Structure
                </h2>
                <div class="grid md:grid-cols-2 gap-6">
                    <div class="p-4 rounded" style="background-color: #150f24;">
                        <h3 class="text-lg font-semibold mb-2" style="color: #44f80c;">Initial Purchase</h3>
                        <p class="text-3xl font-bold text-white mb-1">20%</p>
                        <p class="text-slate-400 text-sm">Commission on every first-time purchase made by your referral.</p>
                    </div>
                    <div class="p-4 rounded" style="background-color: #150f24;">
                        <h3 class="text-lg font-semibold mb-2" style="color: #9a02d0;">Subscription Renewals</h3>
                        <p class="text-3xl font-bold text-white mb-1">10%</p>
                        <p class="text-slate-400 text-sm">Recurring commission on every monthly subscription renewal for 24 months.</p>
                    </div>
                </div>
            </div>

            <!-- How It Works -->
            <div class="mb-10 p-6 rounded-lg" style="background-color: #0a0514; border: 1px solid #1f2b47;">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#38bdf8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    How It Works
                </h2>
                <div class="space-y-4">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #44f80c20; color: #44f80c;">1</div>
                        <div>
                            <p class="text-white font-medium">Share Your Link or QR Code</p>
                            <p class="text-slate-400 text-sm">Post your unique referral link or QR code on social media, your website, or anywhere your audience will see it.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #9a02d020; color: #9a02d0;">2</div>
                        <div>
                            <p class="text-white font-medium">Customer Clicks and Purchases</p>
                            <p class="text-slate-400 text-sm">When someone clicks your link, a tracking cookie is placed on their browser. If they purchase within 45 days, you earn a commission.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #ff66c420; color: #ff66c4;">3</div>
                        <div>
                            <p class="text-white font-medium">Earn on Renewals for 24 Months</p>
                            <p class="text-slate-400 text-sm">After the first purchase, your referral is linked to you for 24 months. Every subscription renewal pays you a recurring commission automatically.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold" style="background-color: #44f80c20; color: #44f80c;">4</div>
                        <div>
                            <p class="text-white font-medium">Get Paid Monthly</p>
                            <p class="text-slate-400 text-sm">Commissions are paid via PayPal on the 15th of each month for the previous month's earnings.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Cookie & Tracking FAQ -->
            <div class="mb-10 p-6 rounded-lg" style="background-color: #0a0514; border: 1px solid #1f2b47;">
                <h2 class="text-2xl font-bold text-white mb-6 flex items-center gap-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#ff66c4" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M9.09 9a3 3 0 0 1 5.83 1c0 2-3 3-3 3"/><line x1="12" y1="17" x2="12.01" y2="17"/></svg>
                    Frequently Asked Questions
                </h2>
                <div class="space-y-4">
                    <div>
                        <h3 class="text-white font-medium mb-1">How long does the referral cookie last?</h3>
                        <p class="text-slate-400 text-sm">When someone clicks your referral link, a 45-day cookie is placed on their browser. If they purchase within 45 days, you get the commission.</p>
                    </div>
                    <div>
                        <h3 class="text-white font-medium mb-1">How do I get paid?</h3>
                        <p class="text-slate-400 text-sm">Commissions are paid monthly on the 15th of the following month that a referral purchases, via your PayPal once you reach the minimum payout threshold.</p>
                    </div>
                    <div>
                        <h3 class="text-white font-medium mb-1">What happens if my referral cancels their subscription?</h3>
                        <p class="text-slate-400 text-sm">You earn recurring commissions for up to 24 months from the initial purchase date. If the customer cancels before 24 months, commissions stop at cancellation.</p>
                    </div>
                    <div>
                        <h3 class="text-white font-medium mb-1">Can I see who purchased through my link?</h3>
                        <p class="text-slate-400 text-sm">No. For privacy reasons, you can only see the order total and commission amount. Customer personal information is never shared with affiliates.</p>
                    </div>
                    <div>
                        <h3 class="text-white font-medium mb-1">Do I need a website to be an affiliate?</h3>
                        <p class="text-slate-400 text-sm">No. You can share your link or QR code on social media, email, messaging apps, or any other channel where you have an audience.</p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- Affiliate Registration Form (shortcode forces old-style form that triggers PHP hooks) -->
    <section class="affiliate-area-section py-12" style="background-color: #0a0514;">
        <div class="container mx-auto px-4 max-w-4xl">
            <?php echo do_shortcode('[affiliate_registration]'); ?>
        </div>
    </section>

</main>

<?php
get_footer();
