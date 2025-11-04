<?php
/**
 * Elementor Downloads Hero Widget
 * Downloads 页面顶部 Hero 区域（视频背景 + 图标）
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Downloads_Hero_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-downloads-hero';
    }

    public function get_title() {
        return __('Downloads Hero', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-download-bold';
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
            'category',
            [
                'label' => __('Category Label', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Encryption',
            ]
        );

        $this->add_control(
            'title',
            [
                'label' => __('Title', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Software & Tools',
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '"Tools are not the end—they are vessels of thought."',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $theme_uri = get_template_directory_uri();
        ?>
        <section class="downloads-hero">
            <video autoplay loop muted playsinline class="hero-bg-video">
                <source src="<?php echo esc_url($theme_uri . '/assets/img/bg-hero-download.webm'); ?>" type="video/webm">
                <?php _e('Your browser does not support the video tag.', 'renaissance'); ?>
            </video>
            <div class="hero-overlay"></div>
            
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <div class="downloads-icon">
                            <div class="icon-circle">
                                <img src="<?php echo esc_url($theme_uri . '/assets/img/icon-hero-download.svg'); ?>" alt="">
                            </div>
                            <div class="icon-label"><?php echo esc_html($settings['category']); ?></div>
                        </div>
                        <h1 class="hero-title"><?php echo esc_html($settings['title']); ?></h1>
                        <p class="hero-description"><?php echo esc_html($settings['subtitle']); ?></p>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

