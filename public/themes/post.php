<?php themeInclude('header'); ?>

<section class="post">
    <h1><?= postTitle(); ?></h1>
    <article>
        <?= postContent(); ?>
    </article>
</section>

<?php if (commentsOpen()) {?>
<section class="comments">
    ZE COMMENTS

    COMMENT FORM
</section>
<?php }?>

<?php themeInclude('footer'); ?>