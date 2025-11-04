<?php
/**
 * Elementor Research Hero Widget
 * Research 页面顶部 Hero 区域（包含特性卡片）
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Research_Hero_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-research-hero';
    }

    public function get_title() {
        return __('Research Hero', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-featured-image';
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
                'default' => 'Research Excellence',
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => 'We don\'t pursue short-term "excess returns"—we pursue repeatable, verifiable, and shareable financial scientific structures.',
            ]
        );

        $this->end_controls_section();

        // Feature Cards
        for ($i = 1; $i <= 3; $i++) {
            $this->start_controls_section(
                'feature' . $i . '_section',
                [
                    'label' => sprintf(__('Feature %d', 'renaissance'), $i),
                    'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
                ]
            );

            $this->add_control(
                'feature' . $i . '_title',
                [
                    'label' => __('Title', 'renaissance'),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => 'Feature ' . $i,
                ]
            );

            $this->add_control(
                'feature' . $i . '_description',
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
        ?>
        <section class="research-hero">
            <div class="research-particles"></div>
            <div class="container">
                <div class="research-hero-content">
                    <h1 class="hero-title"><?php echo esc_html($settings['title']); ?></h1>
                    <p class="hero-description"><?php echo esc_html($settings['subtitle']); ?></p>
                    
                    <div class="research-features">
                        <div class="row g-4">
                            <?php for ($i = 1; $i <= 3; $i++) : ?>
                            <div class="col-md-4">
                                <div class="research-feature-card">
                                    <h3><?php echo esc_html($settings['feature' . $i . '_title']); ?></h3>
                                    <p><?php echo esc_html($settings['feature' . $i . '_description']); ?></p>
                                </div>
                            </div>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

