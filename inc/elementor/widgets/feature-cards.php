<?php
/**
 * Elementor Feature Cards Widget
 * 特性卡片网格（4个卡片）
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Feature_Cards_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-feature-cards';
    }

    public function get_title() {
        return __('Feature Cards', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-icon-box';
    }

    public function get_categories() {
        return ['renaissance-dynamic'];
    }

    protected function register_controls() {
        // 简化：使用固定的 4 个卡片
        for ($i = 1; $i <= 4; $i++) {
            $this->start_controls_section(
                'card' . $i . '_section',
                [
                    'label' => sprintf(__('Card %d', 'renaissance'), $i),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'card' . $i . '_title',
                [
                    'label' => __('Title', 'renaissance'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Feature ' . $i,
                ]
            );

            $this->add_control(
                'card' . $i . '_description',
                [
                    'label' => __('Description', 'renaissance'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Feature description...',
                ]
            );

            $this->end_controls_section();
        }
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $theme_uri = get_template_directory_uri();
        
        $icons = [
            $theme_uri . '/assets/img/icon-section2-01.svg',
            $theme_uri . '/assets/img/icon-section2-02.svg',
            $theme_uri . '/assets/img/icon-section2-03.svg',
            $theme_uri . '/assets/img/icon-section2-04.svg',
        ];
        ?>
        <div class="row g-4">
            <?php for ($i = 1; $i <= 4; $i++) : ?>
            <div class="col-md-6 col-lg-3">
                <div class="feature-card">
                    <div class="feature-icon">
                        <img src="<?php echo esc_url($icons[$i-1]); ?>" alt="<?php echo esc_attr($settings['card' . $i . '_title']); ?>" class="feature-icon-img">
                    </div>
                    <h3 class="feature-title"><?php echo esc_html($settings['card' . $i . '_title']); ?></h3>
                    <p class="feature-description"><?php echo esc_html($settings['card' . $i . '_description']); ?></p>
                </div>
            </div>
            <?php endfor; ?>
        </div>
        <?php
    }
}

