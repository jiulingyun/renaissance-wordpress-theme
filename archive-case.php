<?php
/**
 * Template Name: Cases Archive
 * Description: Archive page for Cases (Successful Cases List)
 */

get_header(); ?>

<!-- Hero Section -->
<section class="cases-list-hero">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="hero-title" data-translate="cases-list-title">
                    <?php 
                    if (is_post_type_archive('case')) {
                        echo esc_html(post_type_archive_title('', false));
                    } else {
                        echo esc_html__('Successful Cases', 'renaissance');
                    }
                    ?>
                </h1>
                <p class="hero-description" data-translate="cases-list-subtitle">
                    <?php 
                    $description = get_the_archive_description();
                    if ($description) {
                        echo wp_kses_post($description);
                    } else {
                        echo esc_html__('Explore our comprehensive portfolio of successful quantitative trading strategies and market innovations', 'renaissance');
                    }
                    ?>
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Cases Grid Section -->
<section class="cases-list-section">
    <div class="container">
        <div class="row g-4">
            <?php
            if (have_posts()) :
                while (have_posts()) : the_post();
                    // 获取自定义字段（可使用 ACF）
                    $roi = get_post_meta(get_the_ID(), 'case_roi', true);
                    $sharpe = get_post_meta(get_the_ID(), 'case_sharpe', true);
                    ?>
                    <div class="col-lg-4 col-md-6">
                        <a href="<?php the_permalink(); ?>" class="case-card-link">
                            <div class="case-card">
                                <?php if (has_post_thumbnail()) : ?>
                                    <div class="case-image">
                                        <?php the_post_thumbnail('medium', ['alt' => get_the_title()]); ?>
                                    </div>
                                <?php else : ?>
                                    <div class="case-image">
                                        <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/img/case-1.jpg'); ?>" alt="<?php echo esc_attr(get_the_title()); ?>">
                                    </div>
                                <?php endif; ?>
                                <div class="case-content">
                                    <h4 class="case-title"><?php the_title(); ?></h4>
                                    <p class="case-description">
                                        <?php 
                                        if (has_excerpt()) {
                                            echo wp_trim_words(get_the_excerpt(), 20, '...');
                                        } else {
                                            echo wp_trim_words(get_the_content(), 20, '...');
                                        }
                                        ?>
                                    </p>
                                    <?php if ($roi || $sharpe) : ?>
                                        <div class="case-metrics">
                                            <?php if ($roi) : ?>
                                                <span class="metric">ROI: <?php echo esc_html($roi); ?></span>
                                            <?php endif; ?>
                                            <?php if ($sharpe) : ?>
                                                <span class="metric">Sharpe: <?php echo esc_html($sharpe); ?></span>
                                            <?php endif; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php
                endwhile;
            else :
                ?>
                <div class="col-12">
                    <div class="alert alert-info text-center">
                        <p><?php esc_html_e('No cases found. Please add some cases from the WordPress admin panel.', 'renaissance'); ?></p>
                    </div>
                </div>
                <?php
            endif;
            ?>
        </div>

        <!-- Pagination -->
        <?php if (get_the_posts_pagination()) : ?>
            <div class="pagination-section">
                <?php
                the_posts_pagination([
                    'mid_size'  => 2,
                    'prev_text' => __('Previous', 'renaissance'),
                    'next_text' => __('Next', 'renaissance'),
                    'screen_reader_text' => __('Cases pagination', 'renaissance'),
                    'before_page_number' => '<span class="meta-nav screen-reader-text">' . __('Page', 'renaissance') . ' </span>',
                ]);
                ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php get_footer(); ?>

