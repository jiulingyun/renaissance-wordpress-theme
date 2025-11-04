<?php
/**
 * Elementor Get Started CTA Widget
 * Downloads 页面的行动号召区域
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Get_Started_CTA_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-get-started-cta';
    }

    public function get_title() {
        return __('Get Started CTA', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-call-to-action';
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
                'default' => 'Ready to Get Started?',
            ]
        );

        $this->add_control(
            'description',
            [
                'label' => __('Description', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'rows' => 3,
                'default' => 'Join our member platform to access all software tools, documentation, and ongoing updates. Premium members receive priority support and early access to new features.',
            ]
        );

        $this->add_control(
            'login_text',
            [
                'label' => __('Login Button Text', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Login to Download',
            ]
        );

        $this->add_control(
            'register_text',
            [
                'label' => __('Register Button Text', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => 'Create Account',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        ?>
        <section class="get-started-section">
            <div class="container">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <div class="get-started-icon">
                            <div class="icon-label"><?php echo esc_html($settings['category']); ?></div>
                        </div>
                        <h2 class="hero-title"><?php echo esc_html($settings['title']); ?></h2>
                        <p class="get-started-desc"><?php echo esc_html($settings['description']); ?></p>
                        <div class="get-started-buttons">
                            <a href="<?php echo esc_url(rena_get_translated_page_url('login')); ?>" class="btn btn-outline-primary me-3"><?php echo esc_html($settings['login_text']); ?></a>
                            <a href="<?php echo esc_url(rena_get_translated_page_url('register')); ?>" class="btn btn-primary"><?php echo esc_html($settings['register_text']); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <?php
    }
}

