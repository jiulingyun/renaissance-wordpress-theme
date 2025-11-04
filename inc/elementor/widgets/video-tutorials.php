<?php
/**
 * Elementor Video Tutorials Widget
 * 显示视频教程列表
 */

if (!defined('ABSPATH')) {
    exit;
}

class Renaissance_Video_Tutorials_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'rena-video-tutorials';
    }

    public function get_title() {
        return __('Video Tutorials', 'renaissance');
    }

    public function get_icon() {
        return 'eicon-youtube';
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
                'label' => __('Number of Videos', 'renaissance'),
                'type' => \Elementor\Controls_Manager::NUMBER,
                'default' => 4,
                'min' => 1,
                'max' => 10,
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        $args = [
            'post_type' => 'video',
            'posts_per_page' => $settings['posts_per_page'],
            'orderby' => 'menu_order ID', // 使用 menu_order 排序，ID 作为次要排序
            'order' => 'ASC',
        ];

        $query = new \WP_Query($args);

        if ($query->have_posts()) :
            ?>
            <div class="tutorials-list">
                <?php
                while ($query->have_posts()) : $query->the_post();
                    $video_url = rena_get_first_video_url(get_the_ID());
                    $duration = rena_get_video_duration(get_the_ID(), $video_url);
                    
                    if (has_excerpt()) {
                        $raw_description = get_the_excerpt();
                    } else {
                        $raw_description = get_the_content();
                        $raw_description = strip_shortcodes($raw_description);
                        $raw_description = wp_strip_all_tags($raw_description);
                    }
                    
                    $raw_description = preg_replace('/\s+/', ' ', $raw_description);
                    $raw_description = trim($raw_description);
                    
                    if (preg_match('/^[\x00-\x7F\s]+$/', $raw_description)) {
                        $words = explode(' ', $raw_description);
                        if (count($words) > 7) {
                            $description = implode(' ', array_slice($words, 0, 7)) . '...';
                        } else {
                            $description = implode(' ', $words);
                        }
                    } else {
                        if (mb_strlen($raw_description) > 30) {
                            $description = mb_substr($raw_description, 0, 30) . '...';
                        } else {
                            $description = $raw_description;
                        }
                    }
                    ?>
                    <button class="tutorial-item video-btn" data-video-url="<?php echo esc_attr($video_url); ?>" data-video-title="<?php echo esc_attr(get_the_title()); ?>">
                        <div class="tutorial-date"><?php echo esc_html($duration); ?></div>
                        <div class="tutorial-content">
                            <h4><?php the_title(); ?></h4>
                            <p><?php echo esc_html($description); ?></p>
                        </div>
                        <div class="play-icon">
                            <svg width="20" height="20" viewBox="0 0 24 24" fill="none">
                                <path d="M8 5V19L19 12L8 5Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </div>
                    </button>
                    <?php
                endwhile;
                wp_reset_postdata();
                ?>
            </div>
            <?php
        else :
            ?>
            <p style="color: rgba(255,255,255,0.6);"><?php _e('No video tutorials found.', 'renaissance'); ?></p>
            <?php
        endif;
        
        // 添加视频弹窗
        ?>
        <!-- Video Modal -->
        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="videoModalLabel"><?php _e('Video Tutorial', 'renaissance'); ?></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body p-0">
                        <div class="video-container">
                            <video id="modalVideo" controls width="100%" height="400">
                                <source src="" type="video/webm">
                                <?php _e('Your browser does not support the video tag.', 'renaissance'); ?>
                            </video>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            const videoButtons = document.querySelectorAll('.video-btn');
            const videoModal = document.getElementById('videoModal');
            const modalVideo = document.getElementById('modalVideo');
            const modalTitle = document.getElementById('videoModalLabel');
            const videoSource = modalVideo ? modalVideo.querySelector('source') : null;

            if (videoModal && modalVideo) {
                videoButtons.forEach(button => {
                    button.addEventListener('click', function() {
                        const videoUrl = this.getAttribute('data-video-url');
                        const videoTitle = this.getAttribute('data-video-title');
                        
                        if (videoUrl && videoSource) {
                            modalTitle.textContent = videoTitle;
                            videoSource.src = videoUrl;
                            modalVideo.load();
                            
                            const modal = new bootstrap.Modal(videoModal);
                            modal.show();
                        }
                    });
                });

                videoModal.addEventListener('hidden.bs.modal', function() {
                    modalVideo.pause();
                    modalVideo.currentTime = 0;
                });
            }
        });
        </script>
        <?php
    }
}

