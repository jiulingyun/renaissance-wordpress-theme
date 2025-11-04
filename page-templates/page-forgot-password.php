<?php
/*
Template Name: Forgot Password
*/

get_header();
$theme_uri = get_template_directory_uri();
?>

<!-- Forgot Password Section -->
<section class="forgot-password-section">
    <canvas id="floating-particles" class="floating-particles-canvas"></canvas>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 col-md-7">
                <div class="forgot-password-container">
                    <div class="forgot-password-header">
                        <div class="forgot-password-icon">
                            <i class="bi bi-key"></i>
                        </div>
                        <h1 class="forgot-password-title"><?php echo esc_html__('Reset Password', 'renaissance'); ?></h1>
                        <p class="forgot-password-subtitle"><?php echo esc_html__("Enter your email address and we'll send you a link to reset your password.", 'renaissance'); ?></p>
                    </div>

                    <form class="forgot-password-form" id="forgotPasswordForm">
                        <div id="forgot-password-message"></div>
                        
                        <div class="form-group">
                            <label for="email" class="form-label"><?php echo esc_html__('Email Address', 'renaissance'); ?></label>
                            <input type="email" class="form-control" id="email" placeholder="<?php echo esc_attr__('your@email.com', 'renaissance'); ?>" required>
                        </div>

                        <button type="submit" class="btn-forgot-password"><?php echo esc_html__('Send Reset Link', 'renaissance'); ?></button>
                        <input type="hidden" id="forgot-password-nonce" value="<?php echo wp_create_nonce('rena-forgot-password-nonce'); ?>">
                    </form>

                    <div class="forgot-password-footer">
                        <p class="back-to-login">
                            <a href="<?php echo esc_url(rena_get_translated_page_url('login')); ?>">
                                <i class="bi bi-arrow-left"></i>
                                <span><?php echo esc_html__('Back to Login', 'renaissance'); ?></span>
                            </a>
                        </p>
                    </div>

                    <!-- Success Message (Hidden by default) -->
                    <div class="forgot-password-success" id="successMessage" style="display: none;">
                        <div class="success-icon">
                            <i class="bi bi-check-circle"></i>
                        </div>
                        <h3 class="success-title"><?php echo esc_html__('Email Sent!', 'renaissance'); ?></h3>
                        <p class="success-message">
                            <?php echo esc_html__("We've sent a password reset link to your email address. Please check your inbox and follow the instructions.", 'renaissance'); ?>
                        </p>
                        <p class="success-note">
                            <?php echo esc_html__("Didn't receive the email? Check your spam folder or", 'renaissance'); ?> <a href="#" id="resendLink"><?php echo esc_html__('resend the link', 'renaissance'); ?></a>.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>

