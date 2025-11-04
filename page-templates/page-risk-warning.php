<?php
/*
Template Name: Risk Warning
*/

get_header();
?>

<main>
    <section class="risk-warning-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-12 col-xl-8">
                    <div class="risk-warning-content">
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
