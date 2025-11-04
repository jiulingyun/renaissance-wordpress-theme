<?php
/**
 * Template for Pages
 * Supports Elementor and other page builders
 */

get_header();

// 检查是否使用 Elementor 构建此页面
$is_elementor_page = (defined('ELEMENTOR_VERSION') && \Elementor\Plugin::$instance->db->is_built_with_elementor(get_the_ID()));

if ($is_elementor_page) {
    // 如果是 Elementor 页面，直接输出内容（全宽）
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
} else {
    // 如果不是 Elementor 页面，使用标准模板布局
    ?>
    <main class="page-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <?php
                    while (have_posts()) :
                        the_post();
                        ?>
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <header class="page-header">
                                <h1 class="page-title"><?php the_title(); ?></h1>
                            </header>
                            <div class="page-entry-content">
                                <?php the_content(); ?>
                            </div>
                        </article>
                        <?php
                    endwhile;
                    ?>
                </div>
            </div>
        </div>
    </main>
    <?php
}

get_footer();
?>

