<?php
/**
 * Template for Single Posts
 * 使用 case-detail 布局
 */

get_header();

$theme_uri = get_template_directory_uri();

// 检查是否使用 Elementor 构建
$is_elementor_post = (defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->db->is_built_with_elementor(get_the_ID()));

if ($is_elementor_post) {
    // Elementor 文章，直接输出内容
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
} else {
    // 标准文章，使用 case-detail 布局
    while (have_posts()) : the_post();
        
        $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'large');
        if (!$featured_image) {
            $featured_image = $theme_uri . '/assets/img/case-1.jpg';
        }
        
        // 获取分类
        $categories = get_the_category();
        $category_name = $categories ? $categories[0]->name : 'Article';
        
        // 获取相关文章
        $tags = wp_get_post_tags(get_the_ID(), ['fields' => 'ids']);
        $related_args = [
            'post_type' => 'post',
            'posts_per_page' => 2,
            'post__not_in' => [get_the_ID()],
            'orderby' => 'rand',
        ];
        if ($tags) {
            $related_args['tag__in'] = $tags;
        }
        $related_query = new WP_Query($related_args);
        ?>

        <main>
            <!-- Breadcrumb -->
            <section class="breadcrumb-section">
                <div class="container">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo home_url('/'); ?>"><?php echo esc_html__('Home', 'renaissance'); ?></a></li>
                            <?php if ($categories) : ?>
                                <li class="breadcrumb-item"><a href="<?php echo get_category_link($categories[0]->term_id); ?>"><?php echo esc_html($categories[0]->name); ?></a></li>
                            <?php endif; ?>
                            <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
                        </ol>
                    </nav>
                </div>
            </section>

            <!-- Post Detail Hero -->
            <section class="case-detail-hero">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-lg-6">
                            <div class="case-detail-content">
                                <div class="case-category"><?php echo esc_html($category_name); ?></div>
                                <h1 class="case-detail-title"><?php the_title(); ?></h1>
                                <?php if (has_excerpt()) : ?>
                                    <p class="case-detail-subtitle"><?php the_excerpt(); ?></p>
                                <?php endif; ?>
                                
                                <div class="case-metrics-large">
                                    <div class="metric-item">
                                        <div class="metric-value"><?php echo get_the_date('M j'); ?></div>
                                        <div class="metric-label"><?php echo esc_html__('Published', 'renaissance'); ?></div>
                                    </div>
                                    <div class="metric-item">
                                        <div class="metric-value"><?php comments_number('0', '1', '%'); ?></div>
                                        <div class="metric-label"><?php echo esc_html__('Comments', 'renaissance'); ?></div>
                                    </div>
                                    <div class="metric-item">
                                        <div class="metric-value"><?php echo esc_html(get_the_author()); ?></div>
                                        <div class="metric-label"><?php echo esc_html__('Author', 'renaissance'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6">
                            <div class="case-detail-image">
                                <img src="<?php echo esc_url($featured_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Post Overview -->
            <section class="case-overview">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="case-content-section">
                                <div class="section-text">
                                    <?php the_content(); ?>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="case-sidebar">
                                <?php if ($related_query->have_posts()) : ?>
                                <div class="sidebar-section">
                                    <h3 class="sidebar-title"><?php echo esc_html__('Related Articles', 'renaissance'); ?></h3>
                                    <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                                    <div class="related-case">
                                        <?php if (has_post_thumbnail()) : ?>
                                            <img src="<?php echo esc_url(get_the_post_thumbnail_url(get_the_ID(), 'medium')); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="related-image">
                                        <?php else : ?>
                                            <img src="<?php echo esc_url($theme_uri . '/assets/img/case-2.jpg'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="related-image">
                                        <?php endif; ?>
                                        <div class="related-content">
                                            <h5 class="related-title"><a href="<?php the_permalink(); ?>">
                                                <?php 
                                                $title = get_the_title();
                                                // 限制标题为约 32 个字符（约 4 个单词）
                                                if (strlen($title) > 32) {
                                                    echo esc_html(mb_substr($title, 0, 32)) . '...';
                                                } else {
                                                    echo esc_html($title);
                                                }
                                                ?>
                                            </a></h5>
                                            <p class="related-desc">
                                                <?php 
                                                $excerpt = get_the_excerpt() ?: get_the_content();
                                                $excerpt = wp_strip_all_tags($excerpt);
                                                // 限制描述为约 5-6 个单词
                                                echo esc_html(wp_trim_words($excerpt, 6));
                                                ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </main>

        <?php
    endwhile;
}

get_footer();
?>
