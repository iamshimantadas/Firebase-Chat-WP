<?php if (have_comments()) : ?>
    <h3><?php comments_number(); ?></h3>
    <ul>
        <?php wp_list_comments(); ?>
    </ul>
<?php endif; ?>

<?php comment_form(); ?>
