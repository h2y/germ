<?php
get_header();
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<article <?php post_class('box'); ?>>
    <header class="entry-header detail-page">
		<h2 class="entry-name">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>
		<ul class="entry-meta">
			<li><i class="fa fa-clock-o"></i> <?php the_time('Y-m-d H:i');?></li>
			<li class="comments_meta"><i class="fa fa-comments-o"></i> <?php comments_popup_link('暂无评论', '1 条评论', '% 条评论'); ?></li>
			<li class="views_meta"><i class="fa fa-eye"></i> <a><?php mzw_post_views(' 访问量');?></a></li>
		</ul>
    </header>
    <div class="entry-content" itemprop="description">
        <?php the_content();; ?>
    </div>
	<footer class="entry-footer clearfix">
    <span class="tag-links"><?php the_tags( '', '', '' ); ?></span>
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
          if( get_post_meta($post->ID,'mzw_ding',true) )
            echo get_post_meta($post->ID,'mzw_ding',true);
          else
            echo '0';
        ?>
			</span></a>
		</div>
    <?php endif; ?>
	</footer>
</article>

<?php comments_template('', true); ?>

<?php endwhile; endif;?>
</div></div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>
