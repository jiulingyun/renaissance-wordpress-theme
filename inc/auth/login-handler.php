<?php
/**
 * 自定义登录处理
 * 处理 AJAX 登录请求
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 注册 AJAX 登录处理（未登录用户）
add_action('wp_ajax_nopriv_rena_login', 'rena_ajax_login');

function rena_ajax_login() {
    // 检查 nonce
    check_ajax_referer('rena-login-nonce', 'security');

    // 获取登录信息
    $username = sanitize_text_field($_POST['username']);
    $password = $_POST['password'];
    $remember = isset($_POST['remember']) && $_POST['remember'] === 'true';

    // 尝试登录
    $creds = [
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => $remember,
    ];

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        wp_send_json_error([
            'message' => $user->get_error_message(),
        ]);
    } else {
        // 获取当前语言的 Downloads 页面 URL
        $redirect_url = home_url('/downloads/');
        $downloads_page = get_page_by_path('downloads');
        if ($downloads_page && function_exists('pll_get_post') && function_exists('pll_current_language')) {
            $current_lang = pll_current_language();
            $translated_downloads_id = pll_get_post($downloads_page->ID, $current_lang);
            if ($translated_downloads_id) {
                $redirect_url = get_permalink($translated_downloads_id);
            }
        }
        
        wp_send_json_success([
            'message' => 'Login successful!',
            'redirect' => $redirect_url,
        ]);
    }
}

// 注册 AJAX 注册处理（未登录用户）
add_action('wp_ajax_nopriv_rena_register', 'rena_ajax_register');

function rena_ajax_register() {
    // 检查 nonce
    check_ajax_referer('rena-register-nonce', 'security');

    // 获取注册信息
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $id_number = sanitize_text_field($_POST['id_number']);
    $password = $_POST['password'];

    // 验证邮箱
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
    }

    // 检查邮箱是否已存在
    if (email_exists($email)) {
        wp_send_json_error(['message' => 'Email already registered.']);
    }

    // 创建用户名（使用邮箱）
    $username = sanitize_user(current(explode('@', $email)));
    
    // 如果用户名已存在，添加随机数字
    if (username_exists($username)) {
        $username = $username . rand(100, 999);
    }

    // 创建用户
    $user_id = wp_create_user($username, $password, $email);

    if (is_wp_error($user_id)) {
        wp_send_json_error(['message' => $user_id->get_error_message()]);
    }

    // 更新用户信息
    wp_update_user([
        'ID' => $user_id,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'display_name' => $first_name . ' ' . $last_name,
    ]);

    // 保存额外信息
    update_user_meta($user_id, 'phone', $phone);
    update_user_meta($user_id, 'id_number', $id_number);

    // 自动登录
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);

    // 获取当前语言的 Downloads 页面 URL
    $redirect_url = home_url('/downloads/');
    $downloads_page = get_page_by_path('downloads');
    if ($downloads_page && function_exists('pll_get_post') && function_exists('pll_current_language')) {
        $current_lang = pll_current_language();
        $translated_downloads_id = pll_get_post($downloads_page->ID, $current_lang);
        if ($translated_downloads_id) {
            $redirect_url = get_permalink($translated_downloads_id);
        }
    }

    wp_send_json_success([
        'message' => 'Registration successful!',
        'redirect' => $redirect_url,
    ]);
}

// 更新用户资料
add_action('wp_ajax_rena_update_profile', 'rena_ajax_update_profile');

function rena_ajax_update_profile() {
    check_ajax_referer('rena-edit-profile-nonce', 'security');

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error(['message' => 'Not logged in.']);
    }

    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $email = sanitize_email($_POST['email']);
    $phone = sanitize_text_field($_POST['phone']);
    $id_number = sanitize_text_field($_POST['id_number']);

    // 验证邮箱
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
    }

    // 检查邮箱是否被其他用户使用
    $email_exists = email_exists($email);
    if ($email_exists && $email_exists != $user_id) {
        wp_send_json_error(['message' => 'Email already in use by another account.']);
    }

    // 更新用户信息
    $update_result = wp_update_user([
        'ID' => $user_id,
        'user_email' => $email,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'display_name' => $first_name . ' ' . $last_name,
    ]);

    if (is_wp_error($update_result)) {
        wp_send_json_error(['message' => $update_result->get_error_message()]);
    }

    // 更新额外字段
    update_user_meta($user_id, 'phone', $phone);
    update_user_meta($user_id, 'id_number', $id_number);

    wp_send_json_success(['message' => 'Profile updated successfully!']);
}

// 修改密码
add_action('wp_ajax_rena_change_password', 'rena_ajax_change_password');

function rena_ajax_change_password() {
    check_ajax_referer('rena-change-password-nonce', 'security');

    $user_id = get_current_user_id();
    if (!$user_id) {
        wp_send_json_error(['message' => 'Not logged in.']);
    }

    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];

    // 验证当前密码
    $user = get_userdata($user_id);
    if (!wp_check_password($current_password, $user->user_pass, $user_id)) {
        wp_send_json_error(['message' => 'Current password is incorrect.']);
    }

    // 验证新密码长度
    if (strlen($new_password) < 8) {
        wp_send_json_error(['message' => 'New password must be at least 8 characters.']);
    }

    // 更新密码
    wp_set_password($new_password, $user_id);

    wp_send_json_success(['message' => 'Password changed successfully!']);
}

// 忘记密码处理
add_action('wp_ajax_nopriv_rena_forgot_password', 'rena_ajax_forgot_password');

function rena_ajax_forgot_password() {
    check_ajax_referer('rena-forgot-password-nonce', 'security');

    $email = sanitize_email($_POST['email']);

    // 验证邮箱
    if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email address.']);
    }

    // 检查用户是否存在
    $user = get_user_by('email', $email);
    if (!$user) {
        // 为了安全，即使用户不存在也返回成功消息
        wp_send_json_success(['message' => 'If an account exists with this email, you will receive a password reset link.']);
    }

    // 生成密码重置密钥
    $key = get_password_reset_key($user);
    
    if (is_wp_error($key)) {
        wp_send_json_error(['message' => 'Failed to generate reset link.']);
    }

    // 构建重置链接
    $reset_url = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login');

    // 发送邮件
    $message = sprintf(
        __('Someone has requested a password reset for the following account:%s', 'renaissance') . "\n\n",
        network_home_url('/')
    );
    $message .= sprintf(__('Username: %s', 'renaissance'), $user->user_login) . "\n\n";
    $message .= __('If this was a mistake, just ignore this email and nothing will happen.', 'renaissance') . "\n\n";
    $message .= __('To reset your password, visit the following address:', 'renaissance') . "\n\n";
    $message .= $reset_url . "\n";

    $title = sprintf(__('[%s] Password Reset', 'renaissance'), wp_specialchars_decode(get_option('blogname'), ENT_QUOTES));

    if (wp_mail($email, $title, $message)) {
        wp_send_json_success(['message' => 'Password reset link sent to your email.']);
    } else {
        wp_send_json_error(['message' => 'Failed to send email. Please try again.']);
    }
}

