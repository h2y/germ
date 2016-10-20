<?php
get_header('full-width');
?>

<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<article <?php post_class('box full-width page'); ?>>
    <header class="entry-header detail-page">
        <h2 class="entry-name">
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>
        <ul class="entry-meta">
            <li class="time_meta"><i class="fa fa-clock-o"></i> <?php the_time('Y-m-d H:i');?></li>
        </ul>
    </header>
    <div class="entry-content att-page" itemprop="description">
        <?php the_attachment_link( $post->ID, true ); ?>
        <?php the_content(); ?>
    </div>
    <footer class="entry-footer clearfix">
        <div class="post-share">
            <a href="javascript:;"><i class="fa fa-share-alt"></i><?php _e('share', 'quench');?></a>
            <ul>
                <li><a href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank"><i class="fa fa-qq"></i></a></li>
                <li><a href="http://service.weibo.com/share/share.php?title=<?php the_title(); ?>&url=<?php the_permalink(); ?>" target="_blank"><i class="fa fa-weibo"></i></a></li>
                <li><a href="http://share.renren.com/share/buttonshare?link=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank"><i class="fa fa-renren"></i></a></li>
                <li><a href="http://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
            </ul>
        </div>
        <?php if( dopt('d_ding_b') != '' ) : ?>
            <div class="post-love">
                <a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="favorite post-love-link <?php if(isset($_COOKIE['mzw_ding_'.$post->ID])) echo ' done';?>" title="点个赞"><i class="fa fa-heart-o"></i>
                <span class="love-count">
                    <?php
                      $ding_num = get_post_meta($post->ID,'mzw_ding',true);
                      echo $ding_num? $ding_num : '0';
                    ?>
                </span></a>
            </div>
        <?php endif; ?>
    </footer>
</article>

<?php endwhile; endif;?>
</div></div>

<?php get_sidebar(); ?>
<?php get_footer(); ?>
