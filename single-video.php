<?php
/**
 * Template for displaying single video posts
 */

get_header();

$theme_uri = get_template_directory_uri();

while (have_posts()) : the_post();
    
    // 获取自定义字段
    $video_category = get_post_meta(get_the_ID(), 'video_category', true) ?: __('Tutorial', 'renaissance');
    $video_duration = rena_get_video_duration(get_the_ID(), rena_get_first_video_url(get_the_ID()));
    $video_level = get_post_meta(get_the_ID(), 'video_level', true) ?: __('Beginner', 'renaissance');
    $subtitle = get_post_meta(get_the_ID(), 'video_subtitle', true) ?: get_the_excerpt();
    
    // 视频统计信息
    $views = get_post_meta(get_the_ID(), 'video_views', true) ?: '0';
    $publish_date = get_the_date('M d, Y');
    
    // 视频语言和字幕
    $language = get_post_meta(get_the_ID(), 'video_language', true) ?: __('English', 'renaissance');
    $subtitles = get_post_meta(get_the_ID(), 'video_subtitles', true) ?: __('Available', 'renaissance');
    
    // 讲师信息
    $instructor_name = get_post_meta(get_the_ID(), 'instructor_name', true) ?: __('Dr. Michael Chen', 'renaissance');
    $instructor_title = get_post_meta(get_the_ID(), 'instructor_title', true) ?: __('Senior Quantitative Analyst', 'renaissance');
    $instructor_bio = get_post_meta(get_the_ID(), 'instructor_bio', true) ?: __('15+ years experience in algorithmic trading and financial modeling.', 'renaissance');
    
    // 获取视频URL
    $video_url = rena_get_first_video_url(get_the_ID());
    
    // 获取相关视频（同标签的视频）
    $tags = wp_get_post_tags(get_the_ID(), ['fields' => 'ids']);
    $related_args = [
        'post_type' => 'video',
        'posts_per_page' => 3,
        'post__not_in' => [get_the_ID()],
        'orderby' => 'rand',
    ];
    if ($tags) {
        $related_args['tag__in'] = $tags;
    }
    $related_query = new WP_Query($related_args);
?>

<main>
    <!-- Hero Section -->
    <section class="video-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="video-header">
                        <div class="video-meta">
                            <span class="video-category"><?php echo esc_html($video_category); ?></span>
                            <span class="video-duration"><?php echo esc_html($video_duration); ?></span>
                            <span class="video-level"><?php echo esc_html($video_level); ?></span>
                        </div>
                        <h1 class="video-title"><?php the_title(); ?></h1>
                        <p class="video-subtitle"><?php echo esc_html($subtitle); ?></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Video Player Section -->
    <section class="video-player-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12">
                    <div class="video-player-wrapper">
                        <div class="video-player">
                            <?php if ($video_url) : ?>
                                <video controls width="100%" height="600">
                                    <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
                                    <?php echo esc_html__('Your browser does not support the video tag.', 'renaissance'); ?>
                                </video>
                            <?php else : ?>
                                <div class="video-placeholder">
                                    <div class="play-button">
                                        <i class="bi bi-play-fill"></i>
                                    </div>
                                    <div class="video-overlay">
                                        <span><?php echo esc_html__('Video not available', 'renaissance'); ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="video-controls">
                            <div class="video-info">
                                <span class="video-views"><?php echo esc_html(number_format((int)$views)); ?> <?php echo esc_html__('views', 'renaissance'); ?></span>
                                <span class="video-date"><?php echo esc_html__('Published on', 'renaissance'); ?> <?php echo esc_html($publish_date); ?></span>
                            </div>
                            <div class="video-actions">
                                <button class="btn-video-action">
                                    <i class="bi bi-heart"></i>
                                    <span><?php echo esc_html__('Like', 'renaissance'); ?></span>
                                </button>
                                <button class="btn-video-action">
                                    <i class="bi bi-bookmark"></i>
                                    <span><?php echo esc_html__('Save', 'renaissance'); ?></span>
                                </button>
                                <button class="btn-video-action">
                                    <i class="bi bi-share"></i>
                                    <span><?php echo esc_html__('Share', 'renaissance'); ?></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Content Section -->
    <section class="video-content">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="video-description">
                        <h2><?php echo esc_html__('Description', 'renaissance'); ?></h2>
                        <div class="description-content">
                            <?php the_content(); ?>
                        </div>
                    </div>
                </div>

                <!-- Sidebar -->
                <div class="col-lg-4">
                    <div class="video-sidebar">
                        <!-- Video Info -->
                        <div class="sidebar-card">
                            <h4><?php echo esc_html__('Video Information', 'renaissance'); ?></h4>
                            <div class="info-list">
                                <div class="info-item">
                                    <span class="info-label"><?php echo esc_html__('Duration:', 'renaissance'); ?></span>
                                    <span class="info-value"><?php echo esc_html($video_duration); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><?php echo esc_html__('Level:', 'renaissance'); ?></span>
                                    <span class="info-value"><?php echo esc_html($video_level); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><?php echo esc_html__('Language:', 'renaissance'); ?></span>
                                    <span class="info-value"><?php echo esc_html($language); ?></span>
                                </div>
                                <div class="info-item">
                                    <span class="info-label"><?php echo esc_html__('Subtitles:', 'renaissance'); ?></span>
                                    <span class="info-value"><?php echo esc_html($subtitles); ?></span>
                                </div>
                            </div>
                        </div>

                        <!-- Related Videos -->
                        <?php if ($related_query->have_posts()) : ?>
                        <div class="sidebar-card">
                            <h4><?php echo esc_html__('Related Videos', 'renaissance'); ?></h4>
                            <div class="related-videos">
                                <?php while ($related_query->have_posts()) : $related_query->the_post(); 
                                    $related_duration = rena_get_video_duration(get_the_ID(), rena_get_first_video_url(get_the_ID()));
                                    $related_excerpt = get_the_excerpt() ?: wp_trim_words(get_the_content(), 8);
                                ?>
                                <a href="<?php the_permalink(); ?>" class="related-video-item">
                                    <div class="video-thumbnail">
                                        <i class="bi bi-play-circle"></i>
                                        <span class="video-duration"><?php echo esc_html($related_duration); ?></span>
                                    </div>
                                    <div class="video-info">
                                        <h5><?php the_title(); ?></h5>
                                        <p><?php echo esc_html($related_excerpt); ?></p>
                                    </div>
                                </a>
                                <?php endwhile; wp_reset_postdata(); ?>
                            </div>
                        </div>
                        <?php endif; ?>

                        <!-- Instructor -->
                        <div class="sidebar-card">
                            <h4><?php echo esc_html__('Instructor', 'renaissance'); ?></h4>
                            <div class="instructor-info">
                                <div class="instructor-avatar">
                                    <i class="bi bi-person-circle"></i>
                                </div>
                                <div class="instructor-details">
                                    <h5 class="instructor-name"><?php echo esc_html($instructor_name); ?></h5>
                                    <p class="instructor-title"><?php echo esc_html($instructor_title); ?></p>
                                    <p class="instructor-bio"><?php echo esc_html($instructor_bio); ?></p>
                                </div>
                            </div>
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

