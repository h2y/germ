<?php
/*
Template Name: 标签模板
*/
get_header(); 
?>
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

<article <?php post_class('box'); ?>>
    <header class="entry-header">
		<h2 class="entry-name">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>
		<ul class="entry-meta">
			<li><i class="fa fa-clock-o"></i> <?php the_time('d,m,Y');?></li>
			<li><i class="fa fa-eye"></i> <?php mzw_post_views(' Views');?></li>
		</ul>
    </header>
    <div class="entry-content tags-page" itemprop="description">
        <?php specs_show_tags(); ?>
    </div>
	<footer class="entry-footer clearfix">
		<div class="post-share">
			<a href="javascript:;"><i class="fa fa-share-alt"></i> 分享</a>
			<ul>
				<li><a href="http://service.weibo.com/share/share.php?title=<?php the_title(); ?>&url=<?php the_permalink(); ?>" target="_blank"><i class="fa fa-weibo"></i></a></li>
				<li><a href="http://share.renren.com/share/buttonshare?link=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank"><i class="fa fa-renren"></i></a></li>
				<li><a href="http://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
			</ul>
		</div>
		<div class="post-love">
			<a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="favorite post-love-link <?php if(isset($_COOKIE['mzw_ding_'.$post->ID])) echo ' done';?>" title="Love this">
			<span class="love-count">
				<?php if( get_post_meta($post->ID,'mzw_ding',true) ){            
                    echo get_post_meta($post->ID,'mzw_ding',true);
                 } else {
                    echo '0';
                 }?>
			</span> <i class="fa fa-heart-o"></i></a>
		</div>
	</footer>
</article>

	
<?php endwhile; endif;?>
</div></div>
	<?php get_sidebar(); ?>
<?php get_footer(); ?>