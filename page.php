<?php get_header(); ?>

<h2><?php get_the_title(); ?></h2>

<div class="container">
    <div class="row">
        <?php the_content(); ?>
    </div>
</div>

<?php get_footer(); ?>