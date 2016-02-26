<article <?php post_class(); ?>>
    <div class="entry-content" itemprop="description">
        <?php echo mb_strimwidth(strip_tags(apply_filters('the_content', $post->post_content)), 0, 400,"……"); ?>
    </div>
</article>
