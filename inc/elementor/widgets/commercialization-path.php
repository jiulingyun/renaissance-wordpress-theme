<?php
/**
 * Elementor Commercialization Path Widget
 * Research 页面的商业化路径区域
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Commercialization_Path_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-commercialization-path';
    }

    public function get_title() {
        return __('Commercialization Path', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-flow';
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
                'default' => 'Commercialization Path',
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => 'Our research-to-market pipeline ensures that scientific breakthroughs translate into practical applications. Clients of our models can support publicly traded companies and institutional investors worldwide.',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="commercialization-section">
            <div class="container">
                <h2 class="section-title" data-translate="commercialization-title"><?php echo esc_html($settings['title']); ?></h2>
                <p class="section-subtitle" data-translate="commercialization-subtitle">
                    <?php echo esc_html($settings['subtitle']); ?>
                </p>
            </div>
        </section>
        <?php
    }
}

