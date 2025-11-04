<?php
/**
 * Elementor Media Reports Widget
 * 显示媒体报道文章列表
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Media_Reports_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-media-reports';
    }

    public function get_title() {
        return __('Media Reports', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return ['renaissance-dynamic'];
    }

    protected function register_controls() {
        // 内容设置
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
                'label' => __('Number of Posts', 'renaissance'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 3,
                'min' => 1,
                'max' => 12,
            ]
        );

        $this->add_control(
            'category',
            [
                'label' => __('Category', 'renaissance'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $this->get_post_categories(),
                'default' => '',
            ]
        );

        $this->add_control(
            'orderby',
            [
                'label' => __('Order By', 'renaissance'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'date' => __('Date', 'renaissance'),
                    'title' => __('Title', 'renaissance'),
                    'rand' => __('Random', 'renaissance'),
                ],
                'default' => 'date',
            ]
        );

        $this->end_controls_section();
    }

    private function get_post_categories() {
        $categories = get_categories(['hide_empty' => false]);
        $options = ['' => __('All Categories', 'renaissance')];
        
        foreach ($categories as $category) {
            $options[$category->term_id] = $category->name;
        }
        
        return $options;
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $theme_uri = get_template_directory_uri();

        $args = [
            'post_type' => 'post',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => $settings['orderby'],
            'order' => 'DESC',
        ];

        if (!empty($settings['category'])) {
            $args['cat'] = $settings['category'];
        }

        $query = new \WP_Query($args);

        // 默认占位图
        $default_images = [
            $theme_uri . '/assets/img/award-trophy-01.jpg',
            $theme_uri . '/assets/img/award-trophy-02.jpg',
            $theme_uri . '/assets/img/award-trophy-03.jpg',
        ];

        if ($query->have_posts()) :
            $image_index = 0;
            $unique_id = 'mediaCarousel' . uniqid();
            $image_carousel_id = 'mediaImageCarousel' . uniqid();
            ?>
            <div class="row align-items-center">
                <!-- 左侧轮播图 -->
                <div class="col-lg-6">
                    <div id="<?php echo $unique_id; ?>" class="carousel slide media-carousel" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <?php
                            $slide_index = 0;
                            $query->rewind_posts();
                            while ($query->have_posts()) : $query->the_post();
                                $active_class = ($slide_index === 0) ? 'active' : '';
                                $excerpt = get_the_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 30);
                                ?>
                                <div class="carousel-item <?php echo $active_class; ?>">
                                    <div class="media-item">
                                        <div class="media-content">
                                            <h3 class="media-title"><?php the_title(); ?></h3>
                                            <p class="media-subtitle"><?php echo esc_html($excerpt); ?></p>
                                        </div>
                                    </div>
                                </div>
                                <?php
                                $slide_index++;
                            endwhile;
                            $query->rewind_posts();
                            ?>
                        </div>
                        <button class="carousel-control-prev" type="button" data-bs-target="#<?php echo $unique_id; ?>" data-bs-slide="prev">
                            <i class="bi bi-chevron-left"></i>
                        </button>
                    </div>
                </div>

                <!-- 右侧图片轮播 -->
                <div class="col-lg-6 position-relative">
                    <div class="media-image">
                        <div id="<?php echo $image_carousel_id; ?>" class="carousel slide h-100" data-bs-ride="carousel">
                            <div class="carousel-inner h-100">
                                <?php
                                $slide_index = 0;
                                while ($query->have_posts()) : $query->the_post();
                                    $active_class = ($slide_index === 0) ? 'active' : '';
                                    $thumbnail_url = get_the_post_thumbnail_url(get_the_ID(), 'large');
                                    if (!$thumbnail_url) {
                                        $thumbnail_url = $default_images[$image_index % 3];
                                    }
                                    ?>
                                    <div class="carousel-item <?php echo $active_class; ?> h-100">
                                        <img src="<?php echo esc_url($thumbnail_url); ?>" class="d-block w-100 h-100" alt="<?php echo esc_attr(get_the_title()); ?>" style="object-fit: cover; border-radius: 1rem;">
                                    </div>
                                    <?php
                                    $slide_index++;
                                    $image_index++;
                                endwhile;
                                ?>
                            </div>
                        </div>
                    </div>
                    <!-- 右箭头 -->
                    <button class="carousel-control-next" type="button" data-bs-target="#<?php echo $unique_id; ?>" data-bs-slide="next" style="position: absolute; top: 50%; right: -30px; transform: translateY(-50%);">
                        <i class="bi bi-chevron-right"></i>
                    </button>
                </div>
            </div>

            <script>
            // 同步两个轮播图
            document.addEventListener('DOMContentLoaded', function() {
                const mediaCarousel = document.getElementById('<?php echo $unique_id; ?>');
                const mediaImageCarousel = document.getElementById('<?php echo $image_carousel_id; ?>');
                
                if (mediaCarousel && mediaImageCarousel && typeof bootstrap !== 'undefined') {
                    const mainCarousel = new bootstrap.Carousel(mediaCarousel);
                    const imageCarousel = new bootstrap.Carousel(mediaImageCarousel);
                    
                    mediaCarousel.addEventListener('slide.bs.carousel', function(e) {
                        imageCarousel.to(e.to);
                    });
                    
                    setInterval(function() {
                        mainCarousel.next();
                    }, 5000);
                }
            });
            </script>
            <?php
            wp_reset_postdata();
        else :
            // 如果没有文章，显示占位内容
            ?>
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <img src="<?php echo esc_url($default_images[0]); ?>" class="img-fluid" alt="Placeholder">
                </div>
                <div class="col-lg-6">
                    <p style="color: rgba(255,255,255,0.6);"><?php _e('No posts found. Please add some posts to display here.', 'renaissance'); ?></p>
                </div>
            </div>
            <?php
        endif;
    }
}

