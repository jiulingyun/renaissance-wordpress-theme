<?php
/**
 * Template for displaying single announcement posts
 */

get_header();

$theme_uri = get_template_directory_uri();

while (have_posts()) : the_post();
    
    // 获取 ACF 自定义字段，如果没有则使用传统 meta 字段
    if (function_exists('get_field')) {
        $announcement_category = get_field('announcement_category');
        if (!$announcement_category) {
            // 如果没有自定义分类，尝试获取文章标签作为分类
            $tags = get_the_tags();
            if ($tags && count($tags) > 0) {
                $announcement_category = $tags[0]->name;
            } else {
                $announcement_category = __('System Update', 'renaissance');
            }
        }
        
        $subtitle = get_field('announcement_subtitle') ?: get_the_excerpt();
        $version = get_field('update_version') ?: 'v1.0.0';
        $size = get_field('update_size') ?: 'N/A';
        $compatibility = get_field('update_compatibility') ?: __('All Systems', 'renaissance');
        $deployment = get_field('update_deployment') ?: __('Manual', 'renaissance');
        
        // 从 ACF Group 字段中获取指标（最多 5 个）
        $metrics = [];
        for ($i = 1; $i <= 5; $i++) {
            $metric = get_field('metric_' . $i);
            if ($metric && !empty($metric['value']) && !empty($metric['label'])) {
                $metrics[] = [
                    'value' => $metric['value'],
                    'label' => $metric['label'],
                ];
            }
        }
    } else {
        // 降级到传统 meta 字段
        $announcement_category = get_post_meta(get_the_ID(), 'announcement_category', true);
        if (!$announcement_category) {
            $tags = get_the_tags();
            if ($tags && count($tags) > 0) {
                $announcement_category = $tags[0]->name;
            } else {
                $announcement_category = __('System Update', 'renaissance');
            }
        }
        
        $subtitle = get_post_meta(get_the_ID(), 'announcement_subtitle', true) ?: get_the_excerpt();
        $version = get_post_meta(get_the_ID(), 'update_version', true) ?: 'v1.0.0';
        $size = get_post_meta(get_the_ID(), 'update_size', true) ?: 'N/A';
        $compatibility = get_post_meta(get_the_ID(), 'update_compatibility', true) ?: __('All Systems', 'renaissance');
        $deployment = get_post_meta(get_the_ID(), 'update_deployment', true) ?: __('Manual', 'renaissance');
        
        $metrics = [];
    }
    
    $announcement_date = get_the_date('F j, Y');
    
    // 获取相关公告（最新的公告文章，排除当前文章）
    $related_args = [
        'post_type' => 'announcement',
        'posts_per_page' => 3,
        'post__not_in' => [get_the_ID()],
        'orderby' => 'date',
        'order' => 'DESC',
    ];
    $related_query = new WP_Query($related_args);
?>

<main>
    <!-- Hero Section -->
    <section class="announcement-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="announcement-header">
                        <div class="announcement-meta">
                            <span class="announcement-category"><?php echo esc_html($announcement_category); ?></span>
                            <span class="announcement-date"><?php echo esc_html($announcement_date); ?></span>
                        </div>
                        <h1 class="announcement-title"><?php the_title(); ?></h1>
                        <p class="announcement-subtitle"><?php echo esc_html($subtitle); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="announcement-content">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="content-wrapper">
                        <!-- Main Content -->
                        <div class="announcement-body announcement-detail-content">
                            <?php the_content(); ?>
                        </div>

                        <!-- Sidebar -->
                        <div class="announcement-sidebar">
                            <div class="sidebar-card">
                                <h4><?php echo esc_html__('Update Information', 'renaissance'); ?></h4>
                                <div class="info-list">
                                    <div class="info-item">
                                        <span class="info-label"><?php echo esc_html__('Version:', 'renaissance'); ?></span>
                                        <span class="info-value"><?php echo esc_html($version); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label"><?php echo esc_html__('Size:', 'renaissance'); ?></span>
                                        <span class="info-value"><?php echo esc_html($size); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label"><?php echo esc_html__('Compatibility:', 'renaissance'); ?></span>
                                        <span class="info-value"><?php echo esc_html($compatibility); ?></span>
                                    </div>
                                    <div class="info-item">
                                        <span class="info-label"><?php echo esc_html__('Deployment:', 'renaissance'); ?></span>
                                        <span class="info-value"><?php echo esc_html($deployment); ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if ($related_query->have_posts()) : ?>
                            <div class="sidebar-card">
                                <h4><?php echo esc_html__('Related Updates', 'renaissance'); ?></h4>
                                <div class="related-list">
                                    <?php while ($related_query->have_posts()) : $related_query->the_post(); ?>
                                    <a href="<?php the_permalink(); ?>" class="related-item">
                                        <span class="related-date"><?php echo get_the_date('M d'); ?></span>
                                        <span class="related-title"><?php the_title(); ?></span>
                                    </a>
                                    <?php endwhile; wp_reset_postdata(); ?>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
endwhile;
get_footer();
?>

