<?php
/**
 * 用户自定义字段管理
 * 在后台用户编辑页面显示额外的用户信息
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

// 在后台用户编辑页面显示额外字段
add_action('show_user_profile', 'rena_show_extra_user_fields');
add_action('edit_user_profile', 'rena_show_extra_user_fields');

function rena_show_extra_user_fields($user) {
    ?>
    <h3><?php _e('Additional Information', 'renaissance'); ?></h3>
    <table class="form-table">
        <tr>
            <th><label for="phone"><?php _e('Phone Number', 'renaissance'); ?></label></th>
            <td>
                <input type="text" name="phone" id="phone" value="<?php echo esc_attr(get_user_meta($user->ID, 'phone', true)); ?>" class="regular-text" />
                <p class="description"><?php _e('Phone number with country code', 'renaissance'); ?></p>
            </td>
        </tr>
        <tr>
            <th><label for="id_number"><?php _e('ID Number', 'renaissance'); ?></label></th>
            <td>
                <input type="text" name="id_number" id="id_number" value="<?php echo esc_attr(get_user_meta($user->ID, 'id_number', true)); ?>" class="regular-text" />
                <p class="description"><?php _e('Government ID, Passport, or Driver\'s License Number', 'renaissance'); ?></p>
            </td>
        </tr>
    </table>
    <?php
}

// 保存额外字段
add_action('personal_options_update', 'rena_save_extra_user_fields');
add_action('edit_user_profile_update', 'rena_save_extra_user_fields');

function rena_save_extra_user_fields($user_id) {
    if (!current_user_can('edit_user', $user_id)) {
        return false;
    }

    if (isset($_POST['phone'])) {
        update_user_meta($user_id, 'phone', sanitize_text_field($_POST['phone']));
    }

    if (isset($_POST['id_number'])) {
        update_user_meta($user_id, 'id_number', sanitize_text_field($_POST['id_number']));
    }
}

// 在后台用户列表中显示额外列
add_filter('manage_users_columns', 'rena_add_user_columns');
function rena_add_user_columns($columns) {
    $columns['phone'] = __('Phone', 'renaissance');
    $columns['id_number'] = __('ID Number', 'renaissance');
    return $columns;
}

add_filter('manage_users_custom_column', 'rena_show_user_column_content', 10, 3);
function rena_show_user_column_content($value, $column_name, $user_id) {
    if ($column_name === 'phone') {
        return get_user_meta($user_id, 'phone', true);
    }
    if ($column_name === 'id_number') {
        return get_user_meta($user_id, 'id_number', true);
    }
    return $value;
}

