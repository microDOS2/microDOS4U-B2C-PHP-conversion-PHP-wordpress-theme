<?php
/**
 * Template Name: Affiliate Area - Custom
 *
 * Custom affiliate area page with commission structure,
 * payment info, cookie explanation, and W-9 tax fields.
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

            <!-- FAQ -->
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

    <!-- Affiliate Portal (login/registration/dashboard) -->
    <section class="affiliate-portal py-12" style="background-color: #0a0514;">
        <div class="container mx-auto px-4 max-w-4xl">
            <?php echo do_shortcode('[affiliate_area]'); ?>
        </div>
    </section>

    <!-- W-9 Tax Fields injected via JavaScript -->
    <script>
    (function() {
        function injectW9Fields() {
            var regForm = document.querySelector('form.affwp-registration-form, .affwp-form form, #affwp-register-form, form[action*="affiliate"]');
            if (!regForm) {
                var regButton = document.querySelector('input[name="affwp_register_submit"], button[name="affwp_register_submit"]');
                if (regButton) regForm = regButton.closest('form');
            }
            if (!regForm) {
                setTimeout(injectW9Fields, 500);
                return;
            }
            if (regForm.querySelector('#microdos-w9-fields')) return;

            var w9Container = document.createElement('div');
            w9Container.id = 'microdos-w9-fields';
            w9Container.style.cssText = 'margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid #1f2b47;';
            w9Container.innerHTML =
                '<h4 style="color: #94a3b8; margin-bottom: 1rem; font-size: 1.1rem; font-weight: 600;">Tax Information (Required for 1099)</h4>' +
                '<p style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 1rem;">The IRS requires us to collect this information to report payments of $600 or more per year.</p>' +
                '<p style="margin-bottom: 1rem;"><label style="color: #94a3b8; display: block; margin-bottom: 0.25rem;">Full Legal Name (as shown on tax return) <span style="color: #ef4444;">*</span></label><input type="text" name="affwp_w9_legal_name" id="affwp_w9_legal_name" required style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; box-sizing: border-box;"></p>' +
                '<p style="margin-bottom: 1rem;"><label style="color: #94a3b8; display: block; margin-bottom: 0.25rem;">Business Name (if different from above)</label><input type="text" name="affwp_w9_business_name" id="affwp_w9_business_name" style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; box-sizing: border-box;"></p>' +
                '<p style="margin-bottom: 1rem;"><label style="color: #94a3b8; display: block; margin-bottom: 0.25rem;">Federal Tax Classification <span style="color: #ef4444;">*</span></label><select name="affwp_w9_tax_classification" id="affwp_w9_tax_classification" required style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; box-sizing: border-box;"><option value="">-- Select One --</option><option value="individual">Individual / Sole Proprietor</option><option value="llc">Limited Liability Company (LLC)</option><option value="ccorp">C Corporation</option><option value="scorp">S Corporation</option><option value="partnership">Partnership</option></select></p>' +
                '<p style="margin-bottom: 1rem;"><label style="color: #94a3b8; display: block; margin-bottom: 0.25rem;">Street Address <span style="color: #ef4444;">*</span></label><input type="text" name="affwp_w9_address" id="affwp_w9_address" required style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; box-sizing: border-box;"></p>' +
                '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;"><p style="margin: 0;"><label style="color: #94a3b8; display: block; margin-bottom: 0.25rem;">City <span style="color: #ef4444;">*</span></label><input type="text" name="affwp_w9_city" id="affwp_w9_city" required style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; box-sizing: border-box;"></p><p style="margin: 0;"><label style="color: #94a3b8; display: block; margin-bottom: 0.25rem;">State <span style="color: #ef4444;">*</span></label><input type="text" name="affwp_w9_state" id="affwp_w9_state" required maxlength="2" placeholder="CO" style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; box-sizing: border-box;"></p></div>' +
                '<p style="margin-bottom: 1rem;"><label style="color: #94a3b8; display: block; margin-bottom: 0.25rem;">ZIP Code <span style="color: #ef4444;">*</span></label><input type="text" name="affwp_w9_zip" id="affwp_w9_zip" required maxlength="10" placeholder="80004" style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; box-sizing: border-box;"></p>' +
                '<p style="margin-bottom: 1rem;"><label style="color: #94a3b8; display: block; margin-bottom: 0.25rem;">SSN or EIN <span style="color: #ef4444;">*</span></label><input type="text" name="affwp_w9_tax_id" id="affwp_w9_tax_id" required maxlength="11" placeholder="123-45-6789 or 12-3456789" style="width: 100%; background-color: #150f24; border: 1px solid #1f2b47; color: #e2e8f0; padding: 0.5rem; border-radius: 0.375rem; box-sizing: border-box;"><span style="color: #64748b; font-size: 0.75rem; display: block; margin-top: 0.25rem;">Required for 1099 tax reporting</span></p>' +
                '<div style="margin-top: 1.5rem; padding: 1rem; border: 1px solid #1f2b47; border-radius: 0.5rem; background-color: #150f24;"><p style="color: #94a3b8; font-size: 0.875rem; margin-bottom: 1rem;"><strong style="color: #e2e8f0;">Certification</strong> &mdash; Under penalties of perjury, I certify that:</p><ol style="color: #94a3b8; font-size: 0.75rem; margin-left: 1.25rem; margin-bottom: 1rem;"><li>The number shown on this form is my correct taxpayer identification number (or I am waiting for a number to be issued to me), and</li><li>I am not subject to backup withholding because: (a) I am exempt from backup withholding, or (b) I have not been notified by the IRS that I am subject to backup withholding, and</li><li>I am a U.S. citizen or other U.S. person, and</li><li>The FATCA code(s) entered on this form (if any) indicating that I am exempt from FATCA reporting is correct.</li></ol><label style="color: #94a3b8; display: flex; align-items: flex-start; gap: 0.5rem; cursor: pointer;"><input type="checkbox" name="affwp_w9_certification" id="affwp_w9_certification" value="1" required style="margin-top: 0.125rem;"><span>I agree to the above certification and understand this is the same as my electronic signature on an IRS Form W-9. <span style="color: #ef4444;">*</span></span></label></div>';

            var submitBtn = regForm.querySelector('input[type="submit"], button[type="submit"]');
            if (submitBtn && submitBtn.parentNode) {
                submitBtn.parentNode.insertBefore(w9Container, submitBtn);
            } else {
                regForm.appendChild(w9Container);
            }
        }

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', injectW9Fields);
        } else {
            injectW9Fields();
        }
    })();
    </script>

</main>

<?php
get_footer();
