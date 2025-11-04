<?php
/*
Template Name: User Profile
*/

// 必须登录才能访问
if (!is_user_logged_in()) {
    // 获取当前语言的 Login 页面 URL
    $login_page = get_page_by_path('login');
    if ($login_page && function_exists('pll_get_post')) {
        $current_lang = function_exists('pll_current_language') ? pll_current_language() : 'en';
        $translated_login_id = pll_get_post($login_page->ID, $current_lang);
        if ($translated_login_id) {
            wp_redirect(get_permalink($translated_login_id));
            exit;
        }
    }
    // 回退到默认 URL
    wp_redirect(home_url('/login/'));
    exit;
}

get_header();

$current_user = wp_get_current_user();
$user_id = $current_user->ID;

// 获取用户额外信息
$phone = get_user_meta($user_id, 'phone', true);
$id_number = get_user_meta($user_id, 'id_number', true);
?>

<main>
    <section class="profile-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="profile-content">
                        <!-- Header -->
                        <div class="profile-header">
                            <h1 class="profile-title"><?php echo esc_html__('My Profile', 'renaissance'); ?></h1>
                            <p class="profile-subtitle"><?php echo esc_html__('Manage your account information', 'renaissance'); ?></p>
                        </div>

                        <!-- User Info Card -->
                        <div class="row g-4">
                            <div class="col-lg-8">
                                <div class="info-card">
                                    <h3 class="card-title"><?php echo esc_html__('Personal Information', 'renaissance'); ?></h3>
                                    <div class="info-list">
                                        <div class="info-item">
                                            <div class="info-label"><?php echo esc_html__('Display Name', 'renaissance'); ?></div>
                                            <div class="info-value"><?php echo esc_html($current_user->display_name); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label"><?php echo esc_html__('Full Name', 'renaissance'); ?></div>
                                            <div class="info-value">
                                                <?php 
                                                $full_name = trim(($current_user->first_name ?? '') . ' ' . ($current_user->last_name ?? ''));
                                                echo esc_html($full_name ?: __('Not set', 'renaissance')); 
                                                ?>
                                            </div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label"><?php echo esc_html__('Email Address', 'renaissance'); ?></div>
                                            <div class="info-value"><?php echo esc_html($current_user->user_email); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label"><?php echo esc_html__('Phone Number', 'renaissance'); ?></div>
                                            <div class="info-value"><?php echo esc_html($phone ?: __('Not set', 'renaissance')); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label"><?php echo esc_html__('ID Number', 'renaissance'); ?></div>
                                            <div class="info-value"><?php echo esc_html($id_number ?: __('Not set', 'renaissance')); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label"><?php echo esc_html__('Member Since', 'renaissance'); ?></div>
                                            <div class="info-value"><?php echo esc_html(date('F j, Y', strtotime($current_user->user_registered))); ?></div>
                                        </div>
                                        <div class="info-item">
                                            <div class="info-label"><?php echo esc_html__('Role', 'renaissance'); ?></div>
                                            <div class="info-value">
                                                <?php 
                                                $roles = $current_user->roles;
                                                $role_name = $roles[0];
                                                // 翻译角色名称
                                                if ($role_name === 'premium_member') {
                                                    echo esc_html__('Premium Member', 'renaissance');
                                                } elseif ($role_name === 'administrator') {
                                                    echo esc_html__('Administrator', 'renaissance');
                                                } else {
                                                    echo esc_html(ucfirst(str_replace('_', ' ', $role_name)));
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-lg-4">
                                <div class="info-card">
                                    <h3 class="card-title"><?php echo esc_html__('Quick Actions', 'renaissance'); ?></h3>
                                    <div class="action-buttons">
                                        <button type="button" class="btn btn-outline-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                                            <i class="bi bi-pencil"></i> <?php echo esc_html__('Edit Profile', 'renaissance'); ?>
                                        </button>
                                        <button type="button" class="btn btn-outline-primary w-100 mb-3" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                            <i class="bi bi-key"></i> <?php echo esc_html__('Change Password', 'renaissance'); ?>
                                        </button>
                                        <a href="<?php echo home_url('/downloads/'); ?>" class="btn btn-outline-primary w-100 mb-3">
                                            <i class="bi bi-download"></i> <?php echo esc_html__('Downloads', 'renaissance'); ?>
                                        </a>
                                        <a href="<?php echo wp_logout_url(home_url('/')); ?>" class="btn btn-outline-danger w-100">
                                            <i class="bi bi-box-arrow-right"></i> <?php echo esc_html__('Logout', 'renaissance'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Edit Profile Modal -->
    <div class="modal fade" id="editProfileModal" tabindex="-1" aria-labelledby="editProfileModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content profile-modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editProfileModalLabel"><?php echo esc_html__('Edit Profile', 'renaissance'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo esc_attr__('Close', 'renaissance'); ?>"></button>
                </div>
                <div class="modal-body">
                    <div id="edit-profile-message"></div>
                    <form id="editProfileForm">
                        <div class="form-group mb-3">
                            <label for="edit_first_name"><?php echo esc_html__('First Name', 'renaissance'); ?></label>
                            <input type="text" class="form-control" id="edit_first_name" value="<?php echo esc_attr($current_user->first_name); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_last_name"><?php echo esc_html__('Last Name', 'renaissance'); ?></label>
                            <input type="text" class="form-control" id="edit_last_name" value="<?php echo esc_attr($current_user->last_name); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_email"><?php echo esc_html__('Email Address', 'renaissance'); ?></label>
                            <input type="email" class="form-control" id="edit_email" value="<?php echo esc_attr($current_user->user_email); ?>" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_phone"><?php echo esc_html__('Phone Number', 'renaissance'); ?></label>
                            <input type="text" class="form-control" id="edit_phone" value="<?php echo esc_attr($phone); ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="edit_id_number"><?php echo esc_html__('ID Number', 'renaissance'); ?></label>
                            <input type="text" class="form-control" id="edit_id_number" value="<?php echo esc_attr($id_number); ?>">
                        </div>
                        <input type="hidden" id="edit-profile-nonce" value="<?php echo wp_create_nonce('rena-edit-profile-nonce'); ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo esc_html__('Cancel', 'renaissance'); ?></button>
                    <button type="button" class="btn btn-primary" id="saveProfileBtn"><?php echo esc_html__('Save Changes', 'renaissance'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content profile-modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel"><?php echo esc_html__('Change Password', 'renaissance'); ?></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo esc_attr__('Close', 'renaissance'); ?>"></button>
                </div>
                <div class="modal-body">
                    <div id="change-password-message"></div>
                    <form id="changePasswordForm">
                        <div class="form-group mb-3">
                            <label for="current_password"><?php echo esc_html__('Current Password', 'renaissance'); ?></label>
                            <input type="password" class="form-control" id="current_password" required>
                        </div>
                        <div class="form-group mb-3">
                            <label for="new_password"><?php echo esc_html__('New Password', 'renaissance'); ?></label>
                            <input type="password" class="form-control" id="new_password" required>
                            <small class="form-text text-muted"><?php echo esc_html__('At least 8 characters', 'renaissance'); ?></small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="confirm_new_password"><?php echo esc_html__('Confirm New Password', 'renaissance'); ?></label>
                            <input type="password" class="form-control" id="confirm_new_password" required>
                        </div>
                        <input type="hidden" id="change-password-nonce" value="<?php echo wp_create_nonce('rena-change-password-nonce'); ?>">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><?php echo esc_html__('Cancel', 'renaissance'); ?></button>
                    <button type="button" class="btn btn-primary" id="changePasswordBtn"><?php echo esc_html__('Change Password', 'renaissance'); ?></button>
                </div>
            </div>
        </div>
    </div>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // 编辑资料
        document.getElementById('saveProfileBtn').addEventListener('click', function() {
            const btn = this;
            const originalText = btn.textContent;
            btn.textContent = 'Saving...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('action', 'rena_update_profile');
            formData.append('first_name', document.getElementById('edit_first_name').value);
            formData.append('last_name', document.getElementById('edit_last_name').value);
            formData.append('email', document.getElementById('edit_email').value);
            formData.append('phone', document.getElementById('edit_phone').value);
            formData.append('id_number', document.getElementById('edit_id_number').value);
            formData.append('security', document.getElementById('edit-profile-nonce').value);

            fetch('/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                btn.textContent = originalText;
                btn.disabled = false;

                if (result.success) {
                    showModalMessage('edit-profile-message', result.data.message || 'Profile updated successfully!', 'success');
                    setTimeout(() => location.reload(), 1500);
                } else {
                    showModalMessage('edit-profile-message', result.data.message || 'Failed to update profile.', 'error');
                }
            })
            .catch(error => {
                btn.textContent = originalText;
                btn.disabled = false;
                showModalMessage('edit-profile-message', 'Failed to update profile.', 'error');
            });
        });

        // 修改密码
        document.getElementById('changePasswordBtn').addEventListener('click', function() {
            const currentPassword = document.getElementById('current_password').value;
            const newPassword = document.getElementById('new_password').value;
            const confirmPassword = document.getElementById('confirm_new_password').value;

            if (!currentPassword || !newPassword || !confirmPassword) {
                showModalMessage('change-password-message', 'Please fill in all fields.', 'error');
                return;
            }

            if (newPassword.length < 8) {
                showModalMessage('change-password-message', 'New password must be at least 8 characters.', 'error');
                return;
            }

            if (newPassword !== confirmPassword) {
                showModalMessage('change-password-message', 'New passwords do not match.', 'error');
                return;
            }

            const btn = this;
            const originalText = btn.textContent;
            btn.textContent = 'Changing...';
            btn.disabled = true;

            const formData = new FormData();
            formData.append('action', 'rena_change_password');
            formData.append('current_password', currentPassword);
            formData.append('new_password', newPassword);
            formData.append('security', document.getElementById('change-password-nonce').value);

            fetch('/wp-admin/admin-ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(result => {
                btn.textContent = originalText;
                btn.disabled = false;

                if (result.success) {
                    showModalMessage('change-password-message', result.data.message || 'Password changed successfully!', 'success');
                    document.getElementById('changePasswordForm').reset();
                    setTimeout(() => {
                        bootstrap.Modal.getInstance(document.getElementById('changePasswordModal')).hide();
                    }, 1500);
                } else {
                    showModalMessage('change-password-message', result.data.message || 'Failed to change password.', 'error');
                }
            })
            .catch(error => {
                btn.textContent = originalText;
                btn.disabled = false;
                showModalMessage('change-password-message', 'Failed to change password.', 'error');
            });
        });

        function showModalMessage(containerId, message, type) {
            const container = document.getElementById(containerId);
            container.innerHTML = `<div class="alert alert-${type === 'success' ? 'success' : 'danger'}">${message}</div>`;
            setTimeout(() => {
                container.innerHTML = '';
            }, 5000);
        }
    });
    </script>
</main>

<?php get_footer(); ?>

