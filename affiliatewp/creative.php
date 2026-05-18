<?php
/**
 * AffiliateWP Creative Template Override
 *
 * Location: your-theme/affiliatewp/creative.php
 *
 * Overrides the default creative display to add easy copy buttons
 * for non-technical affiliates. Works server-side before React renders.
 *
 * Original template: affiliate-wp/templates/creative.php
 * Docs: https://affiliatewp.com/docs/modifying-template-files/
 */

global $affwp_creative_atts;

// Extract data for easy reference
$creative_id      = $affwp_creative_atts['id'];
$url              = $affwp_creative_atts['url'];
$id_class         = $affwp_creative_atts['id_class'];
$desc             = $affwp_creative_atts['desc'];
$preview          = $affwp_creative_atts['preview'];
$image_attributes = $affwp_creative_atts['image_attributes'];
$image_link       = $affwp_creative_atts['image_link'];
$text             = $affwp_creative_atts['text'];

// Build the affiliate referral URL
$affiliate_url = affwp_get_affiliate_referral_url(array('base_url' => $url));

// Determine the image source URL
if ($image_attributes) {
    $img_src = $image_attributes[0];
} elseif ($image_link) {
    $img_src = $image_link;
} else {
    $img_src = '';
}

// Build the HTML creative code
if ($image_attributes) {
    $image_or_text = '<img src="' . esc_attr($image_attributes[0]) . '" alt="' . esc_attr($text) . '" />';
} elseif ($image_link) {
    $image_or_text = '<img src="' . esc_attr($image_link) . '" alt="' . esc_attr($text) . '" />';
} else {
    $image_or_text = esc_attr($text);
}

$creative_html = '<a href="' . esc_url($affiliate_url) . '" title="' . esc_attr($text) . '">' . $image_or_text . '</a>';

// Build email-friendly version
if ($img_src) {
    $email_html = '<a href="' . esc_url($affiliate_url) . '">' . "\n" . '  <img src="' . esc_url($img_src) . '" alt="' . esc_attr($text) . '" style="max-width:100%;height:auto;" />' . "\n" . '</a>';
} else {
    $email_html = '<a href="' . esc_url($affiliate_url) . '">' . esc_html($text) . '</a>';
}

// Unique ID for this creative's buttons
$uid = 'affwp-copy-' . ($creative_id ? $creative_id : uniqid());

?>
<div class="affwp-creative<?php echo esc_attr($id_class); ?>">

    <?php if (!empty($desc)) : ?>
        <p class="affwp-creative-desc"><?php echo esc_html($desc); ?></p>
    <?php endif; ?>

    <?php if ($preview !== 'no') : ?>

        <?php if ($image_attributes) : ?>
            <p>
                <a href="<?php echo esc_url($affiliate_url); ?>" title="<?php echo esc_attr($text); ?>">
                    <img src="<?php echo esc_attr($image_attributes[0]); ?>" width="<?php echo esc_attr($image_attributes[1]); ?>" height="<?php echo esc_attr($image_attributes[2]); ?>" alt="<?php echo esc_attr($text); ?>">
                </a>
            </p>

        <?php elseif ($image_link) : ?>
            <p>
                <a href="<?php echo esc_url($affiliate_url); ?>" title="<?php echo esc_attr($text); ?>">
                    <img src="<?php echo esc_attr($image_link); ?>" alt="<?php echo esc_attr($text); ?>">
                </a>
            </p>

        <?php else : ?>
            <p>
                <a href="<?php echo esc_url($affiliate_url); ?>" title="<?php echo esc_attr($text); ?>"><?php echo esc_html($text); ?></a>
            </p>
        <?php endif; ?>

    <?php endif; ?>

    <?php echo apply_filters('affwp_affiliate_creative_text', '<p>' . __('Copy and paste the following:', 'affiliate-wp') . '</p>'); ?>

    <pre><code><?php echo esc_html($creative_html); ?></code></pre>

    <?php // === MICRODOS EASY COPY BUTTONS === // ?>
    <div class="microdos-copy-buttons" data-creative-id="<?php echo esc_attr($creative_id); ?>">

        <?php if ($img_src) : ?>
        <button type="button" class="microdos-copy-btn microdos-copy-img" data-uid="<?php echo esc_attr($uid); ?>-img">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="9" y="9" width="13" height="13" rx="2"></rect><path d="M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1"></path></svg>
            <?php _e('Copy Image URL', 'microdos'); ?>
        </button>
        <?php endif; ?>

        <button type="button" class="microdos-copy-btn microdos-copy-link" data-uid="<?php echo esc_attr($uid); ?>-link">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71"></path><path d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71"></path></svg>
            <?php _e('Copy My Link', 'microdos'); ?>
        </button>

        <button type="button" class="microdos-copy-btn microdos-copy-email" data-uid="<?php echo esc_attr($uid); ?>-email">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
            <?php _e('Copy for Email', 'microdos'); ?>
        </button>

        <span class="microdos-copy-feedback" id="<?php echo esc_attr($uid); ?>-feedback" style="display:none;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="3"><polyline points="20 6 9 17 4 12"></polyline></svg>
            <?php _e('Copied!', 'microdos'); ?>
        </span>

    </div>

    <?php // Hidden data elements for JavaScript to read ?>
    <script type="text/template" id="<?php echo esc_attr($uid); ?>-img-data" style="display:none;"><?php echo esc_html($img_src); ?></script>
    <script type="text/template" id="<?php echo esc_attr($uid); ?>-link-data" style="display:none;"><?php echo esc_html($affiliate_url); ?></script>
    <script type="text/template" id="<?php echo esc_attr($uid); ?>-email-data" style="display:none;"><?php echo esc_html($email_html); ?></script>

</div>
