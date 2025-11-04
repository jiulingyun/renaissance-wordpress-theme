<?php
/**
 * Elementor Announcements List Widget
 * 显示公告列表
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Announcements_List_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-announcements-list';
    }

    public function get_title() {
        return __('Announcements List', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-post-list';
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
                'label' => __('Number of Announcements', 'renaissance'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 1,
                'min' => 1,
                'max' => 5,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = [
            'post_type' => 'announcement',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'menu_order ID', // 使用 menu_order 排序，ID 作为次要排序
            'order' => 'ASC',
        ];

        $query = new \WP_Query($args);

        if ($query->have_posts()) :
            while ($query->have_posts()) : $query->the_post();
                $tags = get_the_tags();
                $first_tag = $tags ? $tags[0]->name : 'Important Update';
                ?>
                <div class="announcement-article">
                    <div class="article-meta">
                        <span class="article-date"><?php echo get_the_date('Y.m.d'); ?></span>
                        <span class="article-category"><?php echo esc_html($first_tag); ?></span>
                    </div>
                    <h4 class="article-title"><?php the_title(); ?></h4>
                    <div class="article-content">
                        <?php 
                        // 直接从正文获取内容，不使用摘要
                        $description = '';
                        
                        // 获取文章内容并应用过滤器（这样可以正确处理 Gutenberg 块）
                        $content = apply_filters('the_content', get_the_content());
                        // 移除短代码和 HTML 标签
                        $content = strip_shortcodes($content);
                        $content = wp_strip_all_tags($content);
                        // 移除多余的空白字符和换行
                        $content = preg_replace('/\s+/', ' ', $content);
                        $content = trim($content);
                        
                        // 截取前260个字符
                        if (!empty($content)) {
                            if (mb_strlen($content) > 260) {
                                $description = mb_substr($content, 0, 260) . '...';
                            } else {
                                $description = $content;
                            }
                        }
                        
                        // 如果最终还是没有描述，显示提示信息
                        if (empty($description)) {
                            $description = __('No content available. Please edit this announcement to add content.', 'renaissance');
                        }
                        
                        echo '<p>' . esc_html($description) . '</p>';
                        ?>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="btn-read-more">
                        <span><?php echo esc_html__('Read More', 'renaissance'); ?></span>
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none">
                            <path d="M9 18L15 12L9 6" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </a>
                </div>
                <?php
            endwhile;
            wp_reset_postdata();
        else :
            ?>
            <p style="color: rgba(255,255,255,0.6);"><?php _e('No announcements found.', 'renaissance'); ?></p>
            <?php
        endif;
    }
}

