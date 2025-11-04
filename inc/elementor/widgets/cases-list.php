<?php
/**
 * Elementor Cases List Widget
 * 显示案例列表（卡片网格）
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Cases_List_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-cases-list';
    }

    public function get_title() {
        return __('Cases List', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-gallery-grid';
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
            'posts_per_page',
            [
                'label' => __('Number of Cases', 'renaissance'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 12,
            ]
        );

        $this->add_control(
            'columns',
            [
                'label' => __('Columns', 'renaissance'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                ],
                'default' => '3',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $theme_uri = get_template_directory_uri();

        $args = [
            'post_type' => 'case',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'menu_order ID', // 使用 menu_order 排序，ID 作为次要排序
            'order' => 'ASC',
        ];

        $query = new \WP_Query($args);

        $default_images = [
            $theme_uri . '/assets/img/case-1.jpg',
            $theme_uri . '/assets/img/case-2.jpg',
            $theme_uri . '/assets/img/case-3.jpg',
        ];

        $col_class = 'col-lg-' . (12 / $settings['columns']);

        if ($query->have_posts()) :
            ?>
            <div class="cases-grid">
                <div class="row g-4">
                    <?php
                    $image_index = 0;
                    while ($query->have_posts()) : $query->the_post();
                        $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'medium');
                        if (!$thumbnail_url) {
                            $thumbnail_url = $default_images[$image_index % 3];
                        }
                        
                        $tags = get_the_tags();
                        ?>
                        <div class="<?php echo $col_class; ?> col-md-6">
                            <a href="<?php the_permalink(); ?>" class="case-card-link">
                                <div class="case-card">
                                    <div class="case-image">
                                        <img src="<?php echo esc_url($thumbnail_url); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                    </div>
                                    <div class="case-content">
                                        <h4 class="case-title"><?php the_title(); ?></h4>
                                        <p class="case-description">
                                            <?php 
                                            if (has_excerpt()) {
                                                echo esc_html(wp_trim_words(get_the_excerpt(), 20));
                                            } else {
                                                echo esc_html(wp_trim_words(get_the_content(), 20));
                                            }
                                            ?>
                                        </p>
                                        <?php if ($tags) : ?>
                                            <div class="case-metrics">
                                                <?php foreach ($tags as $tag) : ?>
                                                    <span class="metric"><?php echo esc_html($tag->name); ?></span>
                                                <?php endforeach; ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php
                        $image_index++;
                    endwhile;
                    wp_reset_postdata();
                    ?>
                </div>
            </div>
            <?php
        else :
            ?>
            <p style="color: rgba(255,255,255,0.6);"><?php _e('No cases found. Please add cases in the backend.', 'renaissance'); ?></p>
            <?php
        endif;
    }
}

