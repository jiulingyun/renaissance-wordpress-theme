<?php
/*
Template Name: Research
*/

get_header();

// 检查是否使用 Elementor 构建
$is_elementor = (defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->db->is_built_with_elementor(get_the_ID()));

if ($is_elementor) {
    // Elementor 页面，直接输出内容
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
} else {
    // 非 Elementor 页面，显示提示
    ?>
    <main style="min-height: 80vh; display: flex; align-items: center; justify-content: center;">
        <div class="container">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <h1 style="color: #fff; margin-bottom: 1rem;">Research Page</h1>
                    <p style="color: rgba(255,255,255,0.7); margin-bottom: 2rem;">
                        Please use Elementor to design this page with Renaissance Dynamic widgets.
                    </p>
                    <?php if (current_user_can('administrator')) : ?>
                        <a href="<?php echo admin_url('post.php?post=' . get_the_ID() . '&action=elementor'); ?>" class="btn btn-primary">
                            Edit with Elementor
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <?php
}

get_footer();
?>
