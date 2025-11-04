<?php
/**
 * Elementor Mission & Vision Widget
 * 使命与愿景区域（包含地球动画）
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Mission_Vision_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-mission-vision';
    }

    public function get_title() {
        return __('Mission & Vision', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-globe';
    }

    public function get_categories() {
        return ['renaissance-dynamic'];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __('Content', 'renaissance'),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Mission and Vision',
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => 'Devoted to reshaping the financial engineering system through artificial intelligence and mathematical models, Based on scientific research and driven by industrial applications',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <section id="research" class="mission-section">
            <div class="container">
                <h2 class="section-title" data-translate="mission-title"><?php echo esc_html($settings['title']); ?></h2>
                <p class="section-description" data-translate="mission-subtitle">
                    <?php echo esc_html($settings['subtitle']); ?>
                </p>

                <div class="earth-container">
                    <canvas class="globe" id="globe"></canvas>
                </div>
            </div>
        </section>
        <?php
    }
}

