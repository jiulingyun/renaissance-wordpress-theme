<?php
/**
 * Elementor Main Download Card Widget
 * Downloads 页面主下载卡片
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Main_Download_Card_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-main-download-card';
    }

    public function get_title() {
        return __('Main Download Card', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-download-button';
    }

    public function get_categories() {
        return ['renaissance-dynamic'];
    }

    protected function register_controls() {
        // 权限设置
        $this->start_controls_section(
            'permission_section',
            [
                'label' => __('Permission Settings', 'renaissance'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'require_login',
            [
                'label' => __('Require Login', 'renaissance'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'renaissance'),
                'label_off' => __('No', 'renaissance'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Only show this card to logged-in users', 'renaissance'),
            ]
        );

        $this->add_control(
            'require_premium',
            [
                'label' => __('Require Premium Membership', 'renaissance'),
                'type' => \Elementor\Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'renaissance'),
                'label_off' => __('No', 'renaissance'),
                'return_value' => 'yes',
                'default' => 'yes',
                'description' => __('Only show this card to users with "access_downloads" capability', 'renaissance'),
                'condition' => [
                    'require_login' => 'yes',
                ],
            ]
        );

        $this->add_control(
            'fallback_message',
            [
                'label' => __('Fallback Message', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'default' => 'This content is only available to Premium Members. Please login or upgrade your membership to access.',
                'description' => __('Message to show when user does not have permission', 'renaissance'),
                'condition' => [
                    'require_login' => 'yes',
                ],
            ]
        );

        $this->end_controls_section();

        // 内容设置
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'renaissance'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'badge',
            [
                'label' => __('Badge Text', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Premium Members',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Financial engineering software package',
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'All-around quantitative tool, driving investment innovation',
            ]
        );

        $this->add_control(
            'file_url',
            [
                'label' => __('Download File URL', 'renaissance'),
                'type' => \Elementor\Controls_Manager::URL,
                'placeholder' => 'https://example.com/file.zip',
                'default' => [
                    'url' => '#',
                ],
            ]
        );

        $this->add_control(
            'button_text',
            [
                'label' => __('Button Text', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Download',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        
        // 权限检查
        $require_login = ($settings['require_login'] === 'yes');
        $require_premium = ($settings['require_premium'] === 'yes');
        $has_permission = true;
        
        if ($require_login) {
            if (!is_user_logged_in()) {
                $has_permission = false;
            } elseif ($require_premium && !current_user_can('access_downloads')) {
                $has_permission = false;
            }
        }
        
        // 如果没有权限，显示提示信息
        if (!$has_permission) {
            ?>
            <div class="row justify-content-center mb-5">
                <div class="col-lg-12">
                    <div class="main-download-card permission-required">
                        <div class="download-content">
                            <div class="download-info text-center">
                                <div class="permission-icon mb-3">
                                    <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"></rect>
                                        <path d="M7 11V7a5 5 0 0 1 10 0v4"></path>
                                    </svg>
                                </div>
                                <h3 class="permission-title mb-3"><?php echo esc_html__('Premium Content', 'renaissance'); ?></h3>
                                <p class="permission-message"><?php echo esc_html($settings['fallback_message']); ?></p>
                                <div class="permission-actions mt-4">
                                    <?php if (!is_user_logged_in()) : ?>
                                        <a href="<?php echo esc_url(rena_get_translated_page_url('login')); ?>" class="btn btn-primary me-2">
                                            <?php echo esc_html__('Login', 'renaissance'); ?>
                                        </a>
                                        <a href="<?php echo esc_url(rena_get_translated_page_url('register')); ?>" class="btn btn-outline-primary">
                                            <?php echo esc_html__('Register', 'renaissance'); ?>
                                        </a>
                                    <?php else : ?>
                                        <a href="<?php echo esc_url(rena_get_translated_page_url('contact')); ?>" class="btn btn-primary">
                                            <?php echo esc_html__('Upgrade Membership', 'renaissance'); ?>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            return;
        }
        
        // 有权限，显示正常内容
        ?>
        <div class="row justify-content-center mb-5">
            <div class="col-lg-12">
                <div class="main-download-card">
                    <div class="download-content">
                        <div class="download-info">
                            <div class="download-badge">
                                <span><?php echo esc_html($settings['badge']); ?></span>
                            </div>
                            <h1 class="download-title"><?php echo esc_html($settings['title']); ?></h1>
                            <p class="download-description"><?php echo esc_html($settings['description']); ?></p>
                        </div>
                        <div class="download-actions">
                            <a href="<?php echo esc_url($settings['file_url']['url']); ?>" class="btn-primary-download" <?php echo ($settings['file_url']['url'] !== '#') ? 'download' : ''; ?>>
                                <svg width="18" height="18" viewBox="0 0 24 24" fill="none">
                                    <path d="M21 15V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V15" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7 10L12 15L17 10" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M12 15V3" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                                <?php echo esc_html($settings['button_text']); ?>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
}

