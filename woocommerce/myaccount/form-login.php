<?php
/**
 * Login/Register Form Template
 *
 * Custom login and registration form with password field.
 *
 * @package microDOS4U
 */

if (!defined('ABSPATH')) {
    exit;
}

$login_url = wc_get_page_permalink('myaccount');
?>

<div class="u-columns col2-set" id="customer_login">

    <!-- LOGIN COLUMN -->
    <div class="u-column1 col-1">
        <div class="p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#44f80c" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4"/><polyline points="10 17 15 12 10 7"/><line x1="15" y1="12" x2="3" y2="12"/></svg>
                <?php esc_html_e('Log In', 'microdos4u'); ?>
            </h2>

            <form class="woocommerce-form woocommerce-form-login login" method="post">
                <?php do_action('woocommerce_login_form_start'); ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
                    <label for="username" class="text-slate-300 text-sm">
                        <?php esc_html_e('Username or email address', 'microdos4u'); ?>&nbsp;<span class="required" style="color: #ff4444;">*</span>
                    </label>
                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text w-full mt-1 px-4 py-3 rounded-lg text-white" style="background-color: #0a0514; border: 1px solid #1f2b47;" name="username" id="username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
                </p>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
                    <label for="password" class="text-slate-300 text-sm">
                        <?php esc_html_e('Password', 'microdos4u'); ?>&nbsp;<span class="required" style="color: #ff4444;">*</span>
                    </label>
                    <input class="woocommerce-Input woocommerce-Input--text input-text w-full mt-1 px-4 py-3 rounded-lg text-white" style="background-color: #0a0514; border: 1px solid #1f2b47;" type="password" name="password" id="password" autocomplete="current-password" />
                </p>

                <?php do_action('woocommerce_login_form'); ?>

                <p class="form-row mb-4">
                    <?php wp_nonce_field('woocommerce-login', 'woocommerce-login-nonce'); ?>
                    <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-login__submit w-full px-6 py-3 rounded-lg font-semibold" style="background-color: #9a02d0; color: #fff;" name="login" value="<?php esc_attr_e('Log in', 'microdos4u'); ?>">
                        <?php esc_html_e('Log in', 'microdos4u'); ?>
                    </button>
                </p>

                <p class="woocommerce-LostPassword lost_password">
                    <a href="<?php echo esc_url(wp_lostpassword_url()); ?>" style="color: #ff66c4;">
                        <?php esc_html_e('Lost your password?', 'microdos4u'); ?>
                    </a>
                </p>

                <?php do_action('woocommerce_login_form_end'); ?>
            </form>
        </div>
    </div>

    <!-- REGISTER COLUMN -->
    <div class="u-column2 col-2">
        <div class="p-6 rounded-lg" style="background-color: #150f24; border: 1px solid #1f2b47;">
            <h2 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#9a02d0" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="8.5" cy="7" r="4"/><line x1="20" y1="8" x2="20" y2="14"/><line x1="23" y1="11" x2="17" y2="11"/></svg>
                <?php esc_html_e('Create Account', 'microdos4u'); ?>
            </h2>

            <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action('woocommerce_register_form_tag'); ?>>
                <?php do_action('woocommerce_register_form_start'); ?>

                <?php if ('no' === get_option('woocommerce_registration_generate_username')) : ?>
                    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
                        <label for="reg_username" class="text-slate-300 text-sm">
                            <?php esc_html_e('Username', 'microdos4u'); ?>&nbsp;<span class="required" style="color: #ff4444;">*</span>
                        </label>
                        <input type="text" class="woocommerce-Input woocommerce-Input--text input-text w-full mt-1 px-4 py-3 rounded-lg text-white" style="background-color: #0a0514; border: 1px solid #1f2b47;" name="username" id="reg_username" autocomplete="username" value="<?php echo (!empty($_POST['username'])) ? esc_attr(wp_unslash($_POST['username'])) : ''; ?>" />
                    </p>
                <?php endif; ?>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
                    <label for="reg_email" class="text-slate-300 text-sm">
                        <?php esc_html_e('Email address', 'microdos4u'); ?>&nbsp;<span class="required" style="color: #ff4444;">*</span>
                    </label>
                    <input type="email" class="woocommerce-Input woocommerce-Input--text input-text w-full mt-1 px-4 py-3 rounded-lg text-white" style="background-color: #0a0514; border: 1px solid #1f2b47;" name="email" id="reg_email" autocomplete="email" value="<?php echo (!empty($_POST['email'])) ? esc_attr(wp_unslash($_POST['email'])) : ''; ?>" />
                </p>

                <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide mb-4">
                    <label for="reg_password" class="text-slate-300 text-sm">
                        <?php esc_html_e('Password', 'microdos4u'); ?>&nbsp;<span class="required" style="color: #ff4444;">*</span>
                    </label>
                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text w-full mt-1 px-4 py-3 rounded-lg text-white" style="background-color: #0a0514; border: 1px solid #1f2b47;" name="password" id="reg_password" autocomplete="new-password" />
                </p>

                <?php do_action('woocommerce_register_form'); ?>

                <p class="woocommerce-form-row form-row mb-0">
                    <?php wp_nonce_field('woocommerce-register', 'woocommerce-register-nonce'); ?>
                    <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit w-full px-6 py-3 rounded-lg font-semibold" style="background-color: #44f80c; color: #0a0514;" name="register" value="<?php esc_attr_e('Create Account', 'microdos4u'); ?>">
                        <?php esc_html_e('Create Account', 'microdos4u'); ?>
                    </button>
                </p>

                <?php do_action('woocommerce_register_form_end'); ?>
            </form>
        </div>
    </div>
</div>
