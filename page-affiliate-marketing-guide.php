<?php
/**
 * Template Name: Affiliate Marketing Guide
 * Description: Step-by-step tutorial for affiliates on how to use marketing materials
 */

get_header();

// Only allow affiliates to view this page
if ( ! function_exists( 'affwp_is_affiliate' ) || ! affwp_is_affiliate() ) {
    echo '<div style="max-width:600px;margin:4rem auto;padding:2rem;background:#1a1633;border:1px solid #2a2a4a;border-radius:0.5rem;text-align:center;">';
    echo '<h2 style="color:#e2e8f0;margin-bottom:1rem;">Affiliate Access Only</h2>';
    echo '<p style="color:#94a3b8;margin-bottom:1.5rem;">This page is for registered affiliates only. Log in to your affiliate account to access the marketing guide.</p>';
    echo '<a href="' . esc_url( wp_login_url( get_permalink() ) ) . '" style="display:inline-block;padding:0.75rem 1.5rem;background:#4f46e5;color:#fff;text-decoration:none;border-radius:0.375rem;font-weight:500;">Log In</a>';
    echo '</div>';
    get_footer();
    return;
}
?>

<div class="wrap marketing-guide">

    <h2>Marketing Guide</h2>
    <p class="guide-intro">
        Everything you need to start promoting microDOS(2). Follow the steps below for each platform. 
        Your unique referral link is automatically embedded in every creative — when someone clicks and buys, you earn commission.
    </p>

    <!-- STEP 1: Where to Find Materials -->
    <div class="guide-section">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 16V8a2 2 0 00-1-1.73l-7-4a2 2 0 00-2 0l-7 4A2 2 0 003 8v8a2 2 0 001 1.73l7 4a2 2 0 002 0l7-4A2 2 0 0021 16z"></path><polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline><line x1="12" y1="22.08" x2="12" y2="12"></line></svg>
            Step 1: Get Your Marketing Materials
        </h3>
        <ol>
            <li>Click <strong>Creatives</strong> in the left sidebar menu</li>
            <li>Browse the available images, banners, and text links</li>
            <li>Click <strong>View</strong> to preview any creative in full size</li>
            <li>Click <strong>Copy Link</strong> to copy the code with your personal referral URL embedded</li>
            <li>The code is now on your clipboard — ready to paste anywhere</li>
        </ol>
        <div class="tip-box">
            <strong>Tip:</strong>
            <p>Each creative has your unique referral ID built in. You never need to edit the code — just copy and paste as-is.</p>
        </div>
    </div>

    <!-- STEP 2: How to Share -->
    <div class="guide-section">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
            Step 2: Share on Social Media
        </h3>
        <div class="platform-grid">
            
            <div class="platform-card">
                <h4>Instagram</h4>
                <p>Best for: Stories, bio link, DMs</p>
                <ol>
                    <li>Copy your creative code from the Creatives page</li>
                    <li>Open Instagram, start a new Story</li>
                    <li>Upload the image (save from View first)</li>
                    <li>Add the link as a sticker using Link Sticker (10K+ followers) or put in bio</li>
                    <li>For bio: paste your referral link in your profile website field</li>
                </ol>
            </div>

            <div class="platform-card">
                <h4>Facebook</h4>
                <p>Best for: Posts, Groups, Messenger</p>
                <ol>
                    <li>Copy the creative code</li>
                    <li>Start a new post on your timeline or in a group</li>
                    <li>Paste the code directly — Facebook will show the preview image</li>
                    <li>Add your own message above the preview</li>
                    <li>Post it publicly or in relevant groups</li>
                </ol>
            </div>

            <div class="platform-card">
                <h4>X (Twitter)</h4>
                <p>Best for: Tweets, replies, DMs</p>
                <ol>
                    <li>Copy a <strong>Text Link</strong> creative (works best here)</li>
                    <li>Paste it into a new tweet</li>
                    <li>Add your own comment or review</li>
                    <li>The link will auto-shorten and track clicks</li>
                </ol>
            </div>

            <div class="platform-card">
                <h4>TikTok</h4>
                <p>Best for: Video descriptions, bio link</p>
                <ol>
                    <li>Add your referral link to your TikTok bio</li>
                    <li>In video descriptions, paste your text link creative</li>
                    <li>Mention "Link in bio" in your videos</li>
                    <li>Save images from creatives to use as video backgrounds</li>
                </ol>
            </div>

            <div class="platform-card">
                <h4>Reddit</h4>
                <p>Best for: Subreddit posts, comments</p>
                <ol>
                    <li>Copy a <strong>Text Link</strong> creative</li>
                    <li>Find relevant subreddits (research chemicals, microdosing)</li>
                    <li>Post genuinely helpful content with your link where appropriate</li>
                    <li>Follow subreddit rules — avoid spamming</li>
                </ol>
            </div>

            <div class="platform-card">
                <h4>Telegram / Discord</h4>
                <p>Best for: Groups, channels, DMs</p>
                <ol>
                    <li>Copy any creative code</li>
                    <li>Paste directly into chat — preview will show</li>
                    <li>For images: save from View, then upload directly</li>
                    <li>Include your referral link in the caption</li>
                </ol>
            </div>

        </div>
    </div>

    <!-- STEP 3: Email & Website -->
    <div class="guide-section">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            Step 3: Email & Website
        </h3>
        
        <p style="color:#94a3b8;font-size:0.9375rem;margin-bottom:1rem;">For affiliates with email lists or personal websites/blogs.</p>

        <div class="platform-grid">

            <div class="platform-card">
                <h4>Email Newsletter</h4>
                <ol>
                    <li>Copy a <strong>Text Link</strong> or <strong>Image</strong> creative</li>
                    <li>In your email editor (Gmail, Mailchimp, etc.), paste the code</li>
                    <li>For best results: paste as HTML if your editor supports it</li>
                    <li>Add your personal recommendation above the creative</li>
                    <li>Send to your list — clicks and sales are tracked automatically</li>
                </ol>
            </div>

            <div class="platform-card">
                <h4>Website or Blog</h4>
                <ol>
                    <li>Copy an <strong>Image</strong> creative code</li>
                    <li>In your website editor, add an HTML block</li>
                    <li>Paste the code — the image with your referral link will appear</li>
                    <li>Alternatively: download the image and upload to your site manually, then link to your referral URL</li>
                </ol>
            </div>

        </div>

        <div class="tip-box">
            <strong>Pro Tip:</strong>
            <p>Personal recommendations convert 3-5x better than generic ads. Write 1-2 sentences about your own experience before posting any creative.</p>
        </div>
    </div>

    <!-- STEP 4: Best Practices -->
    <div class="guide-section">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"></polygon></svg>
            Best Practices for Higher Earnings
        </h3>
        <ol>
            <li><strong>Be genuine.</strong> Share your actual experience with the product. Authentic posts get more engagement than generic ads.</li>
            <li><strong>Use images.</strong> Posts with images get 2.3x more engagement than text-only. Use the Image creatives for best results.</li>
            <li><strong>Post consistently.</strong> Share 2-3 times per week across platforms. The more you share, the more chances to earn.</li>
            <li><strong>Target the right audience.</strong> Post in groups and communities interested in research, wellness, or cognitive enhancement.</li>
            <li><strong>Track what works.</strong> Check your <strong>Statistics</strong> tab to see which links get the most clicks. Double down on what works.</li>
            <li><strong>Answer questions.</strong> When people comment or DM you, respond quickly. Trust leads to conversions.</li>
            <li><strong>Use multiple platforms.</strong> Don't rely on just one. Spread your links across Instagram, Facebook, Reddit, and email for maximum reach.</li>
        </ol>
        <div class="tip-box">
            <strong>Remember:</strong>
            <p>You earn 20% commission on every sale made through your link. There is no cap on earnings. The more you share, the more you earn.</p>
        </div>
    </div>

    <!-- STEP 5: Quick Start Checklist -->
    <div class="guide-section">
        <h3>
            <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
            Quick Start Checklist
        </h3>
        <ol>
            <li>Go to <strong>Creatives</strong> and copy your first creative</li>
            <li>Post it on your main social media platform today</li>
            <li>Add your referral link to your bio/profile</li>
            <li>Check back in 24 hours under <strong>Statistics</strong> to see your clicks</li>
            <li>Post again tomorrow with a different creative</li>
        </ol>
        <div class="tip-box">
            <strong>Need help?</strong>
            <p>Contact us at <a href="mailto:support@microdos2.com" style="color:#60a5fa;">support@microdos2.com</a> if you have questions about marketing or need custom creatives.</p>
        </div>
    </div>

</div>

<?php get_footer(); ?>