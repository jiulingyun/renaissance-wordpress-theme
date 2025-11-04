<?php
/*
Template Name: Login
*/

// 如果已登录，重定向到 Profile 页面
if (is_user_logged_in()) {
    // 获取当前语言的 Profile 页面 URL
    $profile_page = get_page_by_path('profile');
    if ($profile_page && function_exists('pll_get_post')) {
        $current_lang = function_exists('pll_current_language') ? pll_current_language() : 'en';
        $translated_profile_id = pll_get_post($profile_page->ID, $current_lang);
        if ($translated_profile_id) {
            wp_redirect(get_permalink($translated_profile_id));
            exit;
        }
    }
    // 回退到默认 URL
    wp_redirect(home_url('/profile/'));
    exit;
}

get_header();
$theme_uri = get_template_directory_uri();
?>

<!-- Login Section -->
<section class="login-section">
    <canvas id="floating-particles" class="floating-particles-canvas"></canvas>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="login-container">
                    <div class="login-header">
                        <h1 class="login-title"><?php echo esc_html__('Login', 'renaissance'); ?></h1>
                        <p class="login-subtitle"><?php echo esc_html__('Access your exclusive quantitative resource library', 'renaissance'); ?></p>
                    </div>

                    <form class="login-form" id="loginForm">
                        <div id="login-message"></div>
                        
                        <div class="form-group">
                            <input type="text" class="form-control" id="username" placeholder="<?php echo esc_attr__('your@email.com', 'renaissance'); ?>" required>
                        </div>

                        <div class="form-group">
                            <input type="password" class="form-control" id="password" placeholder="<?php echo esc_attr__('your password', 'renaissance'); ?>" required>
                        </div>

                        <div class="form-group form-options">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="rememberMe">
                                <label class="form-check-label" for="rememberMe">
                                    <?php echo esc_html__('Remember me', 'renaissance'); ?>
                                </label>
                            </div>
                            <a href="<?php echo esc_url(rena_get_translated_page_url('forgot-password')); ?>" class="forgot-password"><?php echo esc_html__('Forgot password?', 'renaissance'); ?></a>
                        </div>

                        <button type="submit" class="btn-login"><?php echo esc_html__('Login', 'renaissance'); ?></button>
                        <input type="hidden" id="login-nonce" value="<?php echo wp_create_nonce('rena-login-nonce'); ?>">
                    </form>

                    <div class="login-footer">
                        <p class="register-link"><?php echo esc_html__("Don't have an account?", 'renaissance'); ?> <a href="<?php echo esc_url(rena_get_translated_page_url('register')); ?>"><?php echo esc_html__('Create one', 'renaissance'); ?></a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
