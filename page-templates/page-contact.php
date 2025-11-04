<?php
/*
Template Name: Contact
*/

get_header();
?>

<script>
// 将页面 ID 和 AJAX 配置传递给 contact.js
window.renaPageId = <?php echo get_the_ID(); ?>;
window.renaAjax = {
    ajaxurl: '<?php echo admin_url('admin-ajax.php'); ?>',
    nonce: '<?php echo wp_create_nonce('rena_contact_form'); ?>'
};
</script>

<main>
    <section class="contact-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-10">
                    <div class="contact-content">
                        <?php
                        while (have_posts()) : the_post();
                            the_content();
                        endwhile;
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php get_footer(); ?>
