<?php
/**
 * Template Name: Getting Started
 *
 * The template for the affiliate Getting Started page.
 * Renders the full post_content which contains all sections.
 *
 * @package microDOS4U
 */

get_header();
?>

<main id="primary" class="site-main">
    <section class="py-20" style="background-color: #0a0514; min-height: 60vh;">
        <div class="container mx-auto px-4 sm:px-6" style="max-width: 900px;">
            <?php
            if (have_posts()) :
                while (have_posts()) :
                    the_post();
                    the_content();
                endwhile;
            endif;
            ?>
        </div>
    </section>
</main>

<?php
get_footer();
