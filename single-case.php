<?php
/**
 * Template for displaying single case posts
 */

get_header();

$theme_uri = get_template_directory_uri();

while (have_posts()) : the_post();
    
    // 获取 ACF 自定义字段，如果没有则使用传统 meta 字段
    if (function_exists('get_field')) {
        $category_label = get_field('case_category') ?: __('Case Study', 'renaissance');
        $subtitle = get_field('case_subtitle') ?: get_the_excerpt();
        $duration = get_field('project_duration') ?: __('12 months', 'renaissance');
        $team_size = get_field('project_team_size') ?: __('10 specialists', 'renaissance');
        $markets = get_field('project_markets') ?: __('Global Markets', 'renaissance');
        $technology = get_field('project_technology') ?: __('Advanced Technology', 'renaissance');
        
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
        
        // 如果没有指标，使用默认值
        if (empty($metrics)) {
            $metrics = [
                ['value' => '100%', 'label' => __('Success Rate', 'renaissance')],
                ['value' => '2.5', 'label' => __('Performance', 'renaissance')],
                ['value' => '99%', 'label' => __('Accuracy', 'renaissance')],
            ];
        }
        
        // 从 ACF 文本字段中获取关键特性（最多 10 个）
        $features = [];
        for ($i = 1; $i <= 10; $i++) {
            $feature = get_field('feature_' . $i);
            if ($feature && !empty($feature)) {
                $features[] = $feature;
            }
        }
        
        // 如果没有特性，使用默认值
        if (empty($features)) {
            $features = [
                __('Feature 1', 'renaissance'),
                __('Feature 2', 'renaissance'),
                __('Feature 3', 'renaissance'),
            ];
        }
    } else {
        // 降级到传统 meta 字段
        $category_label = get_post_meta(get_the_ID(), 'case_category', true) ?: __('Case Study', 'renaissance');
        $subtitle = get_post_meta(get_the_ID(), 'case_subtitle', true) ?: get_the_excerpt();
        $duration = get_post_meta(get_the_ID(), 'project_duration', true) ?: __('12 months', 'renaissance');
        $team_size = get_post_meta(get_the_ID(), 'project_team_size', true) ?: __('10 specialists', 'renaissance');
        $markets = get_post_meta(get_the_ID(), 'project_markets', true) ?: __('Global Markets', 'renaissance');
        $technology = get_post_meta(get_the_ID(), 'project_technology', true) ?: __('Advanced Technology', 'renaissance');
        
        $metrics = [
            ['value' => '100%', 'label' => __('Success Rate', 'renaissance')],
            ['value' => '2.5', 'label' => __('Performance', 'renaissance')],
            ['value' => '99%', 'label' => __('Accuracy', 'renaissance')],
        ];
        
        $features = [
            __('Feature 1', 'renaissance'),
            __('Feature 2', 'renaissance'),
            __('Feature 3', 'renaissance'),
        ];
    }
    
    // 获取相关案例（优先同标签，如果不够则随机获取其他案例）
    $tags = wp_get_post_tags(get_the_ID(), ['fields' => 'ids']);
    $related_args = [
        'post_type' => 'case',
        'posts_per_page' => 2,
        'post__not_in' => [get_the_ID()],
        'orderby' => 'date',
        'order' => 'DESC',
    ];
    
    // 如果有标签，先尝试查询同标签的案例
    if ($tags) {
        $related_args['tag__in'] = $tags;
    }
    
    $related_query = new WP_Query($related_args);
    
    // 如果同标签的案例不足2篇，补充其他案例
    if ($related_query->post_count < 2) {
        $related_args_fallback = [
            'post_type' => 'case',
            'posts_per_page' => 2 - $related_query->post_count,
            'post__not_in' => [get_the_ID()],
            'orderby' => 'date',
            'order' => 'DESC',
        ];
        
        $fallback_query = new WP_Query($related_args_fallback);
        
        // 合并查询结果
        if ($fallback_query->have_posts()) {
            $related_query->posts = array_merge($related_query->posts, $fallback_query->posts);
            $related_query->post_count = count($related_query->posts);
        }
    }
    
    // 获取特色图片
    $featured_image = get_the_post_thumbnail_url(get_the_ID(), 'large') ?: $theme_uri . '/assets/img/case-1.jpg';
?>

<main>
    <!-- Breadcrumb -->
    <section class="breadcrumb-section">
        <div class="container">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html__('Home', 'renaissance'); ?></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo esc_url(get_post_type_archive_link('case')); ?>"><?php echo esc_html__('Cases', 'renaissance'); ?></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php the_title(); ?></li>
                </ol>
            </nav>
        </div>
    </section>

    <!-- Case Detail Hero -->
    <section class="case-detail-hero">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="case-detail-content">
                        <div class="case-category"><?php echo esc_html($category_label); ?></div>
                        <h1 class="case-detail-title"><?php the_title(); ?></h1>
                        <p class="case-detail-subtitle"><?php echo esc_html($subtitle); ?></p>
                        
                        <div class="case-metrics-large">
                            <?php foreach (array_slice($metrics, 0, 3) as $metric) : ?>
                            <div class="metric-item">
                                <div class="metric-value"><?php echo esc_html($metric['value']); ?></div>
                                <div class="metric-label"><?php echo esc_html($metric['label']); ?></div>
                            </div>
                            <?php endforeach; ?>
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

    <!-- Case Overview -->
    <section class="case-overview">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="case-content-section">
                        <?php 
                        // 显示文章内容（使用Gutenberg或经典编辑器）
                        the_content();
                        ?>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="case-sidebar">
                        <!-- Project Information -->
                        <div class="sidebar-section">
                            <h3 class="sidebar-title"><?php echo esc_html__('Project Information', 'renaissance'); ?></h3>
                            <div class="info-item">
                                <div class="info-label"><?php echo esc_html__('Duration', 'renaissance'); ?></div>
                                <div class="info-value"><?php echo esc_html($duration); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><?php echo esc_html__('Team Size', 'renaissance'); ?></div>
                                <div class="info-value"><?php echo esc_html($team_size); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><?php echo esc_html__('Markets', 'renaissance'); ?></div>
                                <div class="info-value"><?php echo esc_html($markets); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label"><?php echo esc_html__('Technology', 'renaissance'); ?></div>
                                <div class="info-value"><?php echo esc_html($technology); ?></div>
                            </div>
                        </div>

                        <!-- Key Features -->
                        <div class="sidebar-section">
                            <h3 class="sidebar-title"><?php echo esc_html__('Key Features', 'renaissance'); ?></h3>
                            <ul class="feature-list">
                                <?php foreach ($features as $feature) : ?>
                                    <li><?php echo esc_html(trim($feature)); ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <!-- Related Cases -->
                        <?php if ($related_query->have_posts()) : ?>
                        <div class="sidebar-section">
                            <h3 class="sidebar-title"><?php echo esc_html__('Related Cases', 'renaissance'); ?></h3>
                            <?php while ($related_query->have_posts()) : $related_query->the_post(); 
                                $related_image = get_the_post_thumbnail_url(get_the_ID(), 'medium') ?: $theme_uri . '/assets/img/case-2.jpg';
                                
                                // 获取标题并截取
                                $related_title = get_the_title();
                                if (strlen($related_title) > 32) {
                                    $related_title = mb_substr($related_title, 0, 32) . '...';
                                }
                                
                                // 获取描述并截取（6个单词）
                                $related_excerpt = get_the_excerpt() ?: get_the_content();
                                $related_excerpt = wp_strip_all_tags($related_excerpt);
                                $related_excerpt = wp_trim_words($related_excerpt, 6);
                            ?>
                            <a href="<?php the_permalink(); ?>" class="related-case">
                                <img src="<?php echo esc_url($related_image); ?>" alt="<?php echo esc_attr(get_the_title()); ?>" class="related-image">
                                <div class="related-content">
                                    <h5 class="related-title"><?php echo esc_html($related_title); ?></h5>
                                    <p class="related-desc"><?php echo esc_html($related_excerpt); ?></p>
                                </div>
                            </a>
                            <?php endwhile; wp_reset_postdata(); ?>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="case-cta">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h2 class="cta-title"><?php echo esc_html__('Interested in Similar Solutions?', 'renaissance'); ?></h2>
                    <p class="cta-description">
                        <?php echo esc_html__('Discover how our quantitative trading strategies can transform your investment approach. Contact our team to learn more about implementing advanced algorithmic solutions.', 'renaissance'); ?>
                    </p>
                    <div class="cta-buttons">
                        <a href="<?php echo esc_url(get_post_type_archive_link('case')); ?>" class="btn btn-outline-primary me-3"><?php echo esc_html__('View More Cases', 'renaissance'); ?></a>
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
