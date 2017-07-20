<span class="meta-item meta-item-likes small <?php (is_user_logged_in()) ? is_liked() : '' ?>">
    <a class="post-like-attributes  meta-item-like
        <?= (is_user_logged_in()) ? '' : 'login_modal' ?>"
        data-on-post="<?php echo get_the_id(); ?>"
        data-author="<?php echo encode_by_salt('user_id', get_the_author_meta('ID')); ?>"
        >
        <span class="like-text"><?php echo (ifLike(get_the_id())) ? "unlike" : "like"; ?></span>
        <?php
            $likes = get_post_meta(get_the_id(), "likes_count", true);
            if (!$likes) {
                $likes = 0;
            }
        ?>
        <span class="likes-quant" style="display: inline;">
            <?php echo $likes; ?>
        </span>
    </a>
</span>