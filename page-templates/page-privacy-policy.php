<?php
/*
Template Name: Privacy Policy
*/

get_header();
?>

<main>
    <section class="privacy-policy-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-8">
                    <div class="privacy-policy-content">
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
