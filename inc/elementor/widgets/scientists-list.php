<?php
/**
 * Elementor Scientists List Widget
 * 显示科学家/工程师列表（滚动动画）
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Scientists_List_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-scientists-list';
    }

    public function get_title() {
        return __('Scientists List', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-person';
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
                'label' => __('Number of Scientists', 'renaissance'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => -1,
                'description' => __('-1 for all scientists', 'renaissance'),
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $theme_uri = get_template_directory_uri();

        $args = [
            'post_type' => 'scientist',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'menu_order ID', // 使用 ID 作为次要排序（ID 是固定的，不会改变）
            'order' => 'ASC',
        ];

        $query = new \WP_Query($args);
        $scientists = [];

        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                
                // 获取文章内容并过滤HTML标签
                $content = get_the_content();
                // 应用内容过滤器（正确处理 Gutenberg 块）
                $content = apply_filters('the_content', $content);
                // 移除短代码和HTML标签
                $content = strip_shortcodes($content);
                $content = wp_strip_all_tags($content);
                // 移除多余的空白字符和换行
                $content = preg_replace('/\s+/', ' ', $content);
                $content = trim($content);
                // 截取前200个字符
                $role = wp_trim_words($content, 200);
                
                $scientists[] = [
                    'name' => get_the_title(),
                    'role' => $role,
                    'avatar' => get_the_post_thumbnail_url(get_the_ID(), 'medium'),
                ];
            }
            wp_reset_postdata();
        }

        if (!empty($scientists)) :
            // 分成两行
            $half = ceil(count($scientists) / 2);
            $row1 = array_slice($scientists, 0, $half);
            $row2 = array_slice($scientists, $half);
            ?>
            <div class="scientists-grid">
                <!-- 第一行：从右向左滚动 -->
                <div class="scientist-row scroll-right-to-left">
                    <div class="scientist-track">
                        <?php foreach ($row1 as $scientist) : ?>
                        <div class="scientist-card">
                            <div class="scientist-avatar">
                                <img src="<?php echo esc_url($scientist['avatar'] ?: $theme_uri . '/assets/img/scientist-1.jpg'); ?>" alt="<?php echo esc_attr($scientist['name']); ?>">
                            </div>
                            <div class="scientist-info">
                                <h4 class="scientist-name"><?php echo esc_html($scientist['name']); ?></h4>
                                <p class="scientist-role"><?php echo esc_html($scientist['role']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <!-- 重复卡片实现无缝循环 -->
                        <?php foreach ($row1 as $scientist) : ?>
                        <div class="scientist-card">
                            <div class="scientist-avatar">
                                <img src="<?php echo esc_url($scientist['avatar'] ?: $theme_uri . '/assets/img/scientist-1.jpg'); ?>" alt="<?php echo esc_attr($scientist['name']); ?>">
                            </div>
                            <div class="scientist-info">
                                <h4 class="scientist-name"><?php echo esc_html($scientist['name']); ?></h4>
                                <p class="scientist-role"><?php echo esc_html($scientist['role']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                
                <!-- 第二行：从左向右滚动 -->
                <div class="scientist-row scroll-left-to-right">
                    <div class="scientist-track">
                        <?php foreach ($row2 as $scientist) : ?>
                        <div class="scientist-card">
                            <div class="scientist-avatar">
                                <img src="<?php echo esc_url($scientist['avatar'] ?: $theme_uri . '/assets/img/scientist-4.jpg'); ?>" alt="<?php echo esc_attr($scientist['name']); ?>">
                            </div>
                            <div class="scientist-info">
                                <h4 class="scientist-name"><?php echo esc_html($scientist['name']); ?></h4>
                                <p class="scientist-role"><?php echo esc_html($scientist['role']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                        
                        <!-- 重复卡片实现无缝循环 -->
                        <?php foreach ($row2 as $scientist) : ?>
                        <div class="scientist-card">
                            <div class="scientist-avatar">
                                <img src="<?php echo esc_url($scientist['avatar'] ?: $theme_uri . '/assets/img/scientist-4.jpg'); ?>" alt="<?php echo esc_attr($scientist['name']); ?>">
                            </div>
                            <div class="scientist-info">
                                <h4 class="scientist-name"><?php echo esc_html($scientist['name']); ?></h4>
                                <p class="scientist-role"><?php echo esc_html($scientist['role']); ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php
        else :
            ?>
            <p style="color: rgba(255,255,255,0.6);"><?php _e('No scientists found. Please add scientists in the backend.', 'renaissance'); ?></p>
            <?php
        endif;
    }
}

