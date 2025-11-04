<?php
/**
 * Elementor Section Title Widget
 * 通用区块标题
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Section_Title_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-section-title';
    }

    public function get_title() {
        return __('Section Title', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-t-letter';
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
                'default' => 'Section Title',
            ]
        );

        $this->add_control(
            'subtitle',
            [
                'label' => __('Subtitle', 'renaissance'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'default' => '',
            ]
        );

        $this->add_control(
            'alignment',
            [
                'label' => __('Alignment', 'renaissance'),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'renaissance'),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'renaissance'),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'renaissance'),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'default' => 'center',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $align_class = 'text-' . $settings['alignment'];
        ?>
        <div class="<?php echo $align_class; ?>">
            <h2 class="section-title"><?php echo esc_html($settings['title']); ?></h2>
            <?php if (!empty($settings['subtitle'])) : ?>
                <p class="section-description"><?php echo esc_html($settings['subtitle']); ?></p>
            <?php endif; ?>
        </div>
        <?php
    }
}

