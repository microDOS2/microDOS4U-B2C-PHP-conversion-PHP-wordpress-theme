<?php
/**
 * Template Name: Affiliate Getting Started
 * Description: Complete walkthrough of the affiliate portal for new affiliates
 */

get_header();

// Only allow affiliates to view this page
if ( ! function_exists( 'affwp_is_affiliate' ) || ! affwp_is_affiliate() ) {
    echo '<div class="max-w-xl mx-auto mt-16 p-8 bg-[#1a1633] border border-[#2a2a4a] rounded-lg text-center">';
    echo '<h2 class="text-xl font-semibold text-[#e2e8f0] mb-4">Affiliate Access Only</h2>';
    echo '<p class="text-[#94a3b8] mb-6">This page is for registered affiliates only.</p>';
    echo '<a href="' . esc_url( wp_login_url( get_permalink() ) ) . '" class="inline-block px-6 py-3 bg-[#4f46e5] text-white rounded-md font-medium">Log In</a>';
    echo '</div>';
    get_footer();
    return;
}

// Get affiliate's referral link
$affiliate_id = affwp_get_affiliate_id();
$referral_url = affwp_get_affiliate_referral_url( array( 'affiliate_id' => $affiliate_id ) );
?>

<style>
.guide-container { max-width: 880px; }
.guide-section {
    background: #1a1633;
    border: 1px solid #2a2a4a;
    border-radius: 0.5rem;
    padding: 1.75rem;
    margin-bottom: 1.5rem;
}
.guide-section h3 {
    color: #60a5fa;
    font-size: 1.125rem;
    font-weight: 600;
    margin: 0 0 1rem 0;
    display: flex;
    align-items: center;
    gap: 0.5rem;
}
.guide-section ol,
.guide-section ul {
    color: #c7d2e8;
    font-size: 0.9375rem;
    line-height: 1.7;
    padding-left: 1.5rem;
    margin: 0;
}
.guide-section li { margin-bottom: 0.5rem; }
.guide-section p {
    color: #94a3b8;
    font-size: 0.9375rem;
    line-height: 1.6;
    margin: 0 0 0.75rem 0;
}
.guide-section .tip-box {
    background: rgba(16, 185, 129, 0.08);
    border-left: 3px solid #10b981;
    padding: 1rem 1.25rem;
    border-radius: 0 0.375rem 0.375rem 0;
    margin-top: 1rem;
}
.guide-section .tip-box strong { color: #10b981; }
.guide-section .tip-box p {
    color: #c7d2e8;
    font-size: 0.875rem;
    margin: 0.25rem 0 0 0;
}
.guide-section .warning-box {
    background: rgba(245, 158, 11, 0.08);
    border-left: 3px solid #f59e0b;
    padding: 1rem 1.25rem;
    border-radius: 0 0.375rem 0.375rem 0;
    margin-top: 1rem;
}
.guide-section .warning-box strong { color: #f59e0b; }
.guide-section .warning-box p {
    color: #c7d2e8;
    font-size: 0.875rem;
    margin: 0.25rem 0 0 0;
}
.nav-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 0.75rem;
    margin-top: 1rem;
}
.nav-card {
    background: #231d3d;
    border: 1px solid #2a2a4a;
    border-radius: 0.5rem;
    padding: 1rem 1.25rem;
    cursor: pointer;
    transition: border-color 0.2s;
}
.nav-card:hover { border-color: #4f46e5; }
.nav-card a {
    color: #e2e8f0;
    font-size: 0.9375rem;
    font-weight: 500;
    text-decoration: none;
    display: block;
}
.nav-card a:hover { color: #60a5fa; }
.nav-card p {
    color: #94a3b8;
    font-size: 0.8125rem;
    margin: 0.25rem 0 0 0;
}
.quick-link {
    color: #60a5fa;
    text-decoration: underline;
    font-weight: 500;
}
.quick-link:hover { color: #93bbfc; }
.referral-box {
    background: linear-gradient(135deg, #1e3a5f 0%, #0f1d3a 100%);
    border: 1px solid #3b82f6;
    border-radius: 0.5rem;
    padding: 1.25rem;
    margin: 1.5rem 0;
}
.referral-box code {
    background: rgba(59, 130, 246, 0.15);
    color: #93bbfc;
    padding: 0.5rem 0.75rem;
    border-radius: 0.375rem;
    font-size: 0.9375rem;
    word-break: break-all;
    display: block;
    margin: 0.5rem 0;
}
.copy-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.5rem 1rem;
    background: #2563eb;
    color: white;
    border: none;
    border-radius: 0.375rem;
    font-size: 0.875rem;
    font-weight: 500;
    cursor: pointer;
    transition: background 0.2s;
}
.copy-btn:hover { background: #1d4ed8; }
.copy-btn.copied { background: #059669; }
.tab-list {
    list-style: none;
    padding: 0;
    margin: 0;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 0.75rem;
}
.tab-list li {
    display: flex;
    align-items: flex-start;
    gap: 0.75rem;
    padding: 0.875rem 1rem;
    background: #231d3d;
    border: 1px solid #2a2a4a;
    border-radius: 0.5rem;
}
.tab-list li strong {
    color: #e2e8f0;
    font-size: 0.9375rem;
    display: block;
    margin-bottom: 0.15rem;
}
.tab-list li span {
    color: #94a3b8;
    font-size: 0.8125rem;
    line-height: 1.4;
}
.tab-icon {
    width: 36px;
    height: 36px;
    min-width: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 0.375rem;
    font-size: 1.1rem;
}
</style>

<div class="guide-container mx-auto px-4 py-8">

    <h2 class="text-2xl font-bold text-[#e2e8f0] mb-2">Getting Started as a microDOS(2) Affiliate</h2>
    <p class="text-[#94a3b8] text-base mb-6">
        Everything you need to know about your affiliate dashboard, how to earn commissions, and where to start.
    </p>

    <!-- YOUR REFERRAL LINK -->
    <div class="referral-box">
        <strong class="text-[#60a5fa] text-sm uppercase tracking-wide font-semibold">Your Unique Referral Link</strong>
        <p class="text-[#c7d2e8] text-sm mt-1 mb-2">Copy this link and share it everywhere. When someone clicks and buys, you earn 20%.</p>
        <code id="aff-ref-url"><?php echo esc_html( $referral_url ); ?></code>
        <button class="copy-btn" onclick="copyRefLink(this)">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 01-2-2V4a2 2 0 012-2h9a2 2 0 012 2v1"></path></svg>
            <span>Copy Link</span>
        </button>
    </div>

    <!-- SECTION 1: HOW IT WORKS -->
    <div class="guide-section" id="how-it-works">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><path d="M12 16v-4"></path><path d="M12 8h.01"></path></svg>
            How the Affiliate Program Works
        </h3>
        <ol>
            <li><strong>You share your link</strong> — Post it on social media, email, your website, or anywhere people might see it.</li>
            <li><strong>Someone clicks</strong> — That click is tracked to your account. We know they came from you.</li>
            <li><strong>They make a purchase</strong> — Anytime in the next 60 days (our cookie lasts that long), if they buy, it counts as your referral.</li>
            <li><strong>You earn 20% commission</strong> — On every sale. No cap. If they spend $100, you get $20.</li>
            <li><strong>Get paid monthly</strong> — Once you hit $50 in earnings, we pay you on the 1st of each month.</li>
        </ol>
        <div class="tip-box">
            <strong>Your cookie lasts 60 days.</strong>
            <p>That means if someone clicks your link today but buys 45 days later, you STILL get credit. Most programs only give 30 days. We give you more time to earn.</p>
        </div>
    </div>

    <!-- SECTION 2: YOUR DASHBOARD EXPLAINED -->
    <div class="guide-section" id="dashboard-explained">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"></rect><rect x="14" y="3" width="7" height="7"></rect><rect x="14" y="14" width="7" height="7"></rect><rect x="3" y="14" width="7" height="7"></rect></svg>
            Your Dashboard — Every Tab Explained
        </h3>
        <p class="mb-4">Your affiliate area has 9 tabs. Here's what each one means and how to use it:</p>
        <ul class="tab-list">
            <li>
                <div class="tab-icon bg-[#2563eb]/15">📊</div>
                <div>
                    <strong>Dashboard</strong>
                    <span>Your numbers at a glance — total referrals, visits, conversion rate, and earnings. Check this daily to track your progress.</span>
                </div>
            </li>
            <li>
                <div class="tab-icon bg-[#7c3aed]/15">🔗</div>
                <div>
                    <strong>Affiliate URLs</strong>
                    <span>Your unique referral link lives here. Copy it. Add campaign tags (like ?campaign=instagram) to track which platform works best.</span>
                </div>
            </li>
            <li>
                <div class="tab-icon bg-[#059669]/15">📈</div>
                <div>
                    <strong>Statistics</strong>
                    <span>Detailed breakdown of clicks, referrals, and earnings by date. Use this to figure out which days and campaigns perform best.</span>
                </div>
            </li>
            <li>
                <div class="tab-icon bg-[#d97706]/15">📉</div>
                <div>
                    <strong>Graphs</strong>
                    <span>Visual charts showing your growth over time. Empty at first — they'll fill in as you promote and get clicks.</span>
                </div>
            </li>
            <li>
                    <div class="tab-icon bg-[#dc2626]/15">💰</div>
                <div>
                    <strong>Referrals</strong>
                    <span>Every sale you generated. Pending = processing. Unpaid = complete, awaiting payout. Paid = money sent. Rejected = refunded.</span>
                </div>
            </li>
            <li>
                <div class="tab-icon bg-[#0891b2]/15">💳</div>
                <div>
                    <strong>Payouts</strong>
                    <span>Your payment history. Shows when you got paid and how much. Minimum payout is $50. Payments go out on the 1st of each month.</span>
                </div>
            </li>
            <li>
                <div class="tab-icon bg-[#4f46e5]/15">👆</div>
                <div>
                    <strong>Visits</strong>
                    <span>Every click on your link. See when people clicked, from where, and how many times. Use this to test what content drives clicks.</span>
                </div>
            </li>
            <li>
                <div class="tab-icon bg-[#ec4899]/15">🎨</div>
                <div>
                    <strong>Creatives</strong>
                    <span>Ready-made banners and images with your referral link already built in. Click "View" to preview, "Copy" to share. Descriptions on each tell you where to use it.</span>
                </div>
            </li>
            <li>
                <div class="tab-icon bg-[#84cc16]/15">🛒</div>
                <div>
                    <strong>Products</strong>
                    <span>Browse our product catalog. Know what you're promoting so you can write authentic recommendations that convert.</span>
                </div>
            </li>
        </ul>
    </div>

    <!-- SECTION 3: WHAT THE NUMBERS MEAN -->
    <div class="guide-section" id="numbers">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20V10"></path><path d="M18 20V4"></path><path d="M6 20v-4"></path></svg>
            What the Numbers Mean
        </h3>
        <p class="mb-3">You'll see these terms across your dashboard. Here's what each one means:</p>
        <ul>
            <li><strong>Visit</strong> — Someone clicked your referral link. That's it. A visit does not equal a sale.</li>
            <li><strong>Referral</strong> — Someone clicked your link AND made a purchase. This is what earns you money.</li>
            <li><strong>Conversion Rate</strong> — The percentage of visits that turned into sales. 100 visits with 5 sales = 5% conversion rate. Average is 1-3%.</li>
            <li><strong>Commission</strong> — The money you earned from a sale. 20% of the order total.</li>
            <li><strong>Unpaid Earnings</strong> — Money you've earned but hasn't been paid out yet.</li>
            <li><strong>Paid Earnings</strong> — Money that's already been sent to you.</li>
            <li><strong>Cookie</strong> — A small tracking file that tells us "this visitor came from [your link]." Our cookies last 60 days.</li>
        </ul>
        <div class="warning-box">
            <strong>Your numbers start at zero.</strong>
            <p>That's normal. Every affiliate starts at 0 referrals and $0.00. Your numbers grow as you share your link and people click and buy. Don't panic if it says zero for the first few days — consistency is what matters.</p>
        </div>
    </div>

    <!-- SECTION 4: WHERE TO SHARE -->
    <div class="guide-section" id="where-to-share">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
            Where to Share Your Link
        </h3>
        <p class="mb-3">The more places you share, the more chances to earn. Here are the best platforms:</p>
        <ul>
            <li><strong>Instagram</strong> — Put your link in your bio. Mention "link in bio" in your posts and stories.</li>
            <li><strong>Facebook</strong> — Share in your timeline, relevant groups, and through Messenger.</li>
            <li><strong>X (Twitter)</strong> — Pin a tweet with your link. Share it in threads about wellness or research.</li>
            <li><strong>TikTok</strong> — Add your link to your bio. Mention it in video descriptions.</li>
            <li><strong>Reddit</strong> — Find relevant subreddits (research, wellness, cognitive enhancement) and share where appropriate.</li>
            <li><strong>Telegram / Discord</strong> — Share in groups and channels you participate in.</li>
            <li><strong>Email</strong> — Send to friends, family, or your newsletter list. Email has the highest conversion rate.</li>
            <li><strong>Website / Blog</strong> — Add a banner or sidebar widget with your link.</li>
            <li><strong>Text / WhatsApp</strong> — Personal recommendations to people you know convert the best.</li>
        </ul>
        <div class="tip-box">
            <strong>Personal recommendations convert 3-5x better than generic ads.</strong>
            <p>Instead of just posting your link, write 1-2 sentences about why you believe in the product. "I've been using microDOS(2) for 3 months and it's been a game-changer for my focus..." That personal touch makes people click.</p>
        </div>
    </div>

    <!-- SECTION 5: QUICK START CHECKLIST -->
    <div class="guide-section" id="checklist">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            Your Quick Start Checklist
        </h3>
        <p class="mb-3">Do these steps in order to start earning today:</p>
        <ol>
            <li><strong>Copy your referral link</strong> (it's in the blue box above, or go to the Affiliate URLs tab)</li>
            <li><strong>Add your link to your social media bios</strong> — Instagram, TikTok, X, Facebook</li>
            <li><strong>Visit the Creatives tab</strong> — Grab a banner or image to use in your first post</li>
            <li><strong>Make your first post today</strong> — Share your link with a personal recommendation on your main platform</li>
            <li><strong>Check your Visits tab tomorrow</strong> — See how many people clicked</li>
            <li><strong>Post again in 2-3 days</strong> — Consistency is key. The more you share, the more you earn.</li>
        </ol>
    </div>

    <!-- SECTION 6: WHEN DO I GET PAID -->
    <div class="guide-section" id="payouts">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="5" width="20" height="14" rx="2"></rect><line x1="2" y1="10" x2="22" y2="10"></line></svg>
            When and How You Get Paid
        </h3>
        <ul>
            <li><strong>Commission rate:</strong> 20% on every sale</li>
            <li><strong>Minimum payout:</strong> $50 (you need at least $50 in unpaid earnings)</li>
            <li><strong>Payment date:</strong> 1st of every month</li>
            <li><strong>Payment methods:</strong> PayPal, direct deposit</li>
            <li><strong>Holding period:</strong> Referrals are "pending" for a short period to allow for refunds, then become "unpaid" and eligible for payout.</li>
        </ul>
        <div class="tip-box">
            <strong>Example:</strong>
            <p>You refer 5 customers who each spend $100. You earn 20% = $20 per sale × 5 sales = $100 total. On the 1st of next month, $100 gets sent to your PayPal.</p>
        </div>
    </div>

    <!-- SECTION 7: BEST PRACTICES -->
    <div class="guide-section" id="best-practices">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            Best Practices for Maximum Earnings
        </h3>
        <ul>
            <li><strong>Post consistently.</strong> Share 2-3 times per week across platforms. More shares = more clicks = more sales.</li>
            <li><strong>Use images.</strong> Posts with images get 2.3x more engagement than text-only. Use the Creatives tab banners.</li>
            <li><strong>Be genuine.</strong> Write a personal sentence about why you recommend the product. Trust converts.</li>
            <li><strong>Target the right people.</strong> Share in communities interested in research, wellness, or cognitive enhancement.</li>
            <li><strong>Track what works.</strong> Check your Statistics and Visits tabs. Double down on platforms that get clicks.</li>
            <li><strong>Answer questions.</strong> When people comment or DM you, respond quickly. Engagement builds trust.</li>
            <li><strong>Use multiple platforms.</strong> Don't rely on just one. Spread your link everywhere for maximum reach.</li>
            <li><strong>Add your link to your bio everywhere.</strong> Instagram, TikTok, X — every bio should have your referral link. That's passive income.</li>
        </ul>
    </div>

    <!-- SECTION 8: NEXT STEPS -->
    <div class="guide-section" id="next-steps">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M13 2L3 14h9l-1 8 10-12h-9l1-8z"></path></svg>
            What's Next
        </h3>
        <p class="mb-3">Now that you understand your dashboard, here's where to go:</p>
        <div class="nav-grid">
            <div class="nav-card">
                <a href="/affiliate-area/urls/">🔗 Affiliate URLs</a>
                <p>Copy your referral link and add it to your social bios</p>
            </div>
            <div class="nav-card">
                <a href="/affiliate-area/creatives/">🎨 Creatives</a>
                <p>Grab ready-made banners and images for your posts</p>
            </div>
            <div class="nav-card">
                <a href="/marketing-guide/">📖 Marketing Guide</a>
                <p>Step-by-step instructions for Instagram, Facebook, X, TikTok, Reddit, Email, and more</p>
            </div>
            <div class="nav-card">
                <a href="/affiliate-area/">📊 Dashboard</a>
                <p>Check your stats and track your progress</p>
            </div>
        </div>
        <div class="tip-box mt-4">
            <strong>Questions?</strong>
            <p>Email us at <a href="mailto:support@microdos2.com" style="color:#60a5fa;text-decoration:underline;">support@microdos2.com</a> — we're here to help you succeed.</p>
        </div>
    </div>

</div>

<script>
function copyRefLink(btn) {
    var url = document.getElementById('aff-ref-url').textContent.trim();
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function() {
            btn.classList.add('copied');
            btn.querySelector('span').textContent = 'Copied!';
            setTimeout(function() {
                btn.classList.remove('copied');
                btn.querySelector('span').textContent = 'Copy Link';
            }, 2500);
        });
    } else {
        var ta = document.createElement('textarea');
        ta.value = url;
        document.body.appendChild(ta);
        ta.select();
        document.execCommand('copy');
        document.body.removeChild(ta);
        btn.classList.add('copied');
        btn.querySelector('span').textContent = 'Copied!';
        setTimeout(function() {
            btn.classList.remove('copied');
            btn.querySelector('span').textContent = 'Copy Link';
        }, 2500);
    }
}
</script>

<?php get_footer(); ?>