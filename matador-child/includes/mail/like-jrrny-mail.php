<?php
$authorName = get_user_meta( $authorId, 'first_name', true );
?>
<html>
<body>
<div>
    <p>
        <b><?= $user->user_login ?></b> recently liked your post <a href="<?= get_permalink($post->ID)?>"><?=$post->post_title?></a>
    </p>
    <p>
        Check out their tips <a href="<?= get_author_posts_url($user->ID, $user->user_login)?>">here</a> -- maybe you can return the favor?
    </p>
    <p>
        Or make a comment if their tip.
    </p>
    <p>
        Enjoy the coachestribune!
    </p>
    <p>
        <a href="http://www.coachestribune.com">www.coachestribune.com</a>
        <br>
        Check out what's trending <a href="<?=site_url( '/trending')?>"><?=site_url( '/trending')?></a>
        <br>
        Add a new tip <a href="<?=site_url( '/upload')?>"><?=site_url( '/upload')?></a>
        <br>
        Click here to turn off these notifications <a href="<?=get_author_posts_url($author->ID, $author->user_login)?>"><?=get_author_posts_url($author->ID, $author->user_login)?></a>
    </p>
</div>
</body>
</html>