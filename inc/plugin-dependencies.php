<?php
/**
 * 主题插件依赖检查
 * 
 * 检查必需和推荐的插件，并在后台显示提示信息
 */

// 防止直接访问
if (!defined('ABSPATH')) {
    exit;
}

/**
 * 检查插件是否已激活
 */
function rena_is_plugin_active($plugin) {
    include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    return is_plugin_active($plugin);
}

/**
 * 获取必需和推荐的插件列表
 */
function rena_get_required_plugins() {
    return [
        'required' => [
            [
                'name' => 'Advanced Custom Fields (ACF)',
                'slug' => 'advanced-custom-fields/acf.php',
                'description' => '用于管理自定义字段，增强内容管理功能',
                'install_url' => admin_url('plugin-install.php?s=Advanced+Custom+Fields&tab=search&type=term'),
            ],
            [
                'name' => 'Polylang',
                'slug' => 'polylang/polylang.php',
                'description' => '多语言支持，让网站支持英文、中文等多种语言',
                'install_url' => admin_url('plugin-install.php?s=Polylang&tab=search&type=term'),
            ],
        ],
        'recommended' => [
            [
                'name' => 'Elementor',
                'slug' => 'elementor/elementor.php',
                'description' => '可视化页面编辑器，可自由设计页面布局',
                'install_url' => admin_url('plugin-install.php?s=Elementor&tab=search&type=term'),
            ],
        ],
    ];
}

/**
 * 检查缺失的插件
 */
function rena_check_missing_plugins() {
    $plugins = rena_get_required_plugins();
    $missing = [
        'required' => [],
        'recommended' => [],
    ];

    foreach ($plugins['required'] as $plugin) {
        if (!rena_is_plugin_active($plugin['slug'])) {
            $missing['required'][] = $plugin;
        }
    }

    foreach ($plugins['recommended'] as $plugin) {
        if (!rena_is_plugin_active($plugin['slug'])) {
            $missing['recommended'][] = $plugin;
        }
    }

    return $missing;
}

/**
 * 显示插件依赖提示
 */
function rena_plugin_dependency_notice() {
    $missing = rena_check_missing_plugins();

    // 显示必需插件提示
    if (!empty($missing['required'])) {
        ?>
        <div class="notice notice-error is-dismissible">
            <h3><?php esc_html_e('Renaissance 主题 - 缺少必需插件', 'renaissance'); ?></h3>
            <p><?php esc_html_e('以下插件是主题正常运行所必需的，请安装并激活：', 'renaissance'); ?></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <?php foreach ($missing['required'] as $plugin) : ?>
                    <li>
                        <strong><?php echo esc_html($plugin['name']); ?></strong> - 
                        <?php echo esc_html($plugin['description']); ?>
                        <a href="<?php echo esc_url($plugin['install_url']); ?>" class="button button-primary" style="margin-left: 10px;">
                            <?php esc_html_e('安装插件', 'renaissance'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }

    // 显示推荐插件提示
    if (!empty($missing['recommended'])) {
        ?>
        <div class="notice notice-warning is-dismissible">
            <h3><?php esc_html_e('Renaissance 主题 - 推荐插件', 'renaissance'); ?></h3>
            <p><?php esc_html_e('以下插件可以增强主题功能，建议安装：', 'renaissance'); ?></p>
            <ul style="list-style: disc; margin-left: 20px;">
                <?php foreach ($missing['recommended'] as $plugin) : ?>
                    <li>
                        <strong><?php echo esc_html($plugin['name']); ?></strong> - 
                        <?php echo esc_html($plugin['description']); ?>
                        <a href="<?php echo esc_url($plugin['install_url']); ?>" class="button" style="margin-left: 10px;">
                            <?php esc_html_e('安装插件', 'renaissance'); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php
    }
}

// 在管理后台显示提示
add_action('admin_notices', 'rena_plugin_dependency_notice');

/**
 * 在主题激活时检查插件
 */
function rena_activation_check() {
    $missing = rena_check_missing_plugins();
    
    if (!empty($missing['required'])) {
        // 存储提示信息到瞬态数据
        set_transient('rena_activation_notice', true, 5);
    }
}
add_action('after_switch_theme', 'rena_activation_check');

/**
 * 显示主题激活提示
 */
function rena_activation_notice() {
    if (get_transient('rena_activation_notice')) {
        ?>
        <div class="notice notice-success">
            <h2><?php esc_html_e('欢迎使用 Renaissance 主题！', 'renaissance'); ?></h2>
            <p><?php esc_html_e('感谢您选择 Renaissance 主题。为了获得最佳体验，请确保安装并激活所有必需的插件。', 'renaissance'); ?></p>
            <p>
                <a href="<?php echo admin_url('themes.php?page=renaissance-setup'); ?>" class="button button-primary">
                    <?php esc_html_e('开始配置', 'renaissance'); ?>
                </a>
            </p>
        </div>
        <?php
        delete_transient('rena_activation_notice');
    }
}
add_action('admin_notices', 'rena_activation_notice');

