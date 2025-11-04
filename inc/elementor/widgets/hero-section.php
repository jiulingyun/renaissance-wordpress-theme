<?php
/**
 * Elementor Hero Section Widget
 * 首页顶部 Hero 区域
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Hero_Section_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-hero-section';
    }

    public function get_title() {
        return __('Hero Section', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-header';
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
            'title_line1',
            [
                'label' => __('Title Line 1', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'At the intersection of algorithms and humanity',
            ]
        );

        $this->add_control(
            'title_line2',
            [
                'label' => __('Title Line 2', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'We see the future of finance',
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'The end of technology is aesthetics, the end of finance is wisdom',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $theme_uri = get_template_directory_uri();
        ?>
        <section id="home" class="hero-section">
            <div class="container">
                <div class="hero-content">
                    <h1 class="hero-title" data-translate="hero-title-1"><?php echo esc_html($settings['title_line1']); ?></h1>
                    <h1 class="hero-title" data-translate="hero-title-2"><?php echo esc_html($settings['title_line2']); ?></h1>
                    <p class="hero-description" data-translate="hero-description"><?php echo esc_html($settings['description']); ?></p>

                    <!-- 背景视频 -->
                    <div class="globe-container">
                        <video autoplay loop muted playsinline class="hero-video">
                            <source src="<?php echo esc_url($theme_uri . '/assets/img/bg-video-section1.webm'); ?>" type="video/webm">
                            Your browser does not support the video tag.
                        </video>
                        <img src="<?php echo esc_url($theme_uri . '/assets/img/img-section-01.png'); ?>" alt="Overlay" class="video-overlay-img">
                        <div class="particles-container"></div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

