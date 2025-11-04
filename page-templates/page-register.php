<?php
/*
Template Name: Register
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

<!-- Register Section -->
<section class="register-section">
    <canvas id="floating-particles" class="floating-particles-canvas"></canvas>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="register-container">
                    <div class="register-header">
                        <h1 class="hero-title"><?php echo esc_html__('Register', 'renaissance'); ?></h1>
                        <p class="hero-description"><?php echo esc_html__('Open your exclusive quantitative resource library', 'renaissance'); ?></p>
                    </div>

                    <form class="register-form" id="registerForm">
                        <div id="register-message"></div>
                        
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="firstName" class="form-label"><?php echo esc_html__('First Name', 'renaissance'); ?></label>
                                <input type="text" class="form-control" id="firstName" placeholder="<?php echo esc_attr__('John', 'renaissance'); ?>" required>
                            </div>
                            <div class="form-group col-md-6">
                                <label for="lastName" class="form-label"><?php echo esc_html__('Last Name', 'renaissance'); ?></label>
                                <input type="text" class="form-control" id="lastName" placeholder="<?php echo esc_attr__('Doe', 'renaissance'); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="email" class="form-label"><?php echo esc_html__('Email Address', 'renaissance'); ?></label>
                            <input type="email" class="form-control" id="email" placeholder="<?php echo esc_attr__('john.doe@example.com', 'renaissance'); ?>" required>
                        </div>

                        <div class="form-group">
                            <label for="phone" class="form-label"><?php echo esc_html__('Phone Number', 'renaissance'); ?></label>
                            <div class="phone-input-container">
                                <select class="form-control country-code" id="countryCode">
                                    <option value="+1">🇺🇸 +1</option>
                                    <option value="+1">🇨🇦 +1</option>
                                    <option value="+44">🇬🇧 +44</option>
                                    <option value="+33">🇫🇷 +33</option>
                                    <option value="+49">🇩🇪 +49</option>
                                    <option value="+86">🇨🇳 +86</option>
                                    <option value="+81">🇯🇵 +81</option>
                                    <option value="+82">🇰🇷 +82</option>
                                    <option value="+65">🇸🇬 +65</option>
                                    <option value="+852">🇭🇰 +852</option>
                                </select>
                                <input type="tel" class="form-control phone-number" id="phone" placeholder="<?php echo esc_attr__('123-456-7890', 'renaissance'); ?>" required>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="idNumber" class="form-label"><?php echo esc_html__('Government ID / Passport Number', 'renaissance'); ?></label>
                            <input type="text" class="form-control" id="idNumber" placeholder="<?php echo esc_attr__('A12345678', 'renaissance'); ?>" required>
                            <small class="form-text text-muted"><?php echo esc_html__("Driver's License, Passport, or National ID Number", 'renaissance'); ?></small>
                        </div>

                        <div class="form-group">
                            <label for="regPassword" class="form-label"><?php echo esc_html__('Password', 'renaissance'); ?></label>
                            <input type="password" class="form-control" id="regPassword" placeholder="<?php echo esc_attr__('••••••••', 'renaissance'); ?>" required>
                            <small class="form-text text-muted"><?php echo esc_html__('At least 8 characters with uppercase, lowercase, and numbers', 'renaissance'); ?></small>
                        </div>

                        <div class="form-group">
                            <label for="confirmPassword" class="form-label"><?php echo esc_html__('Confirm Password', 'renaissance'); ?></label>
                            <input type="password" class="form-control" id="confirmPassword" placeholder="<?php echo esc_attr__('••••••••', 'renaissance'); ?>" required>
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="termsCheck" required>
                                <label class="form-check-label" for="termsCheck">
                                    <?php echo esc_html__('I agree to the', 'renaissance'); ?> 
                                    <a href="<?php echo esc_url(rena_get_translated_page_url('privacy-policy')); ?>"><?php echo esc_html__('Terms of Service', 'renaissance'); ?></a> 
                                    <?php echo esc_html__('and', 'renaissance'); ?> 
                                    <a href="<?php echo esc_url(rena_get_translated_page_url('privacy-policy')); ?>"><?php echo esc_html__('Privacy Policy', 'renaissance'); ?></a>
                                </label>
                            </div>
                        </div>

                        <button type="submit" class="btn-register"><?php echo esc_html__('Create Account', 'renaissance'); ?></button>
                        <input type="hidden" id="register-nonce" value="<?php echo wp_create_nonce('rena-register-nonce'); ?>">
                    </form>

                    <div class="register-footer">
                        <p class="login-link"><?php echo esc_html__('Already have an account,', 'renaissance'); ?> <a href="<?php echo esc_url(rena_get_translated_page_url('login')); ?>"><?php echo esc_html__('Go to login', 'renaissance'); ?></a>.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>

