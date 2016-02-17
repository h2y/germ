<?php
/*
Template Name: 网站地图页面
*/

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
			<li class="comments_meta"><i class="fa fa-comments-o"></i> <?php
        if(comments_open())
          comments_popup_link('暂无评论', '1 条评论', '% 条评论');
        else
          echo '<a href="javascript:;">评论关闭</a>';
      ?></li>
			<li class="views_meta"><i class="fa fa-eye"></i> <a><?php mzw_post_views(' 访问量');?></a></li>
		</ul>
    </header>
    <div class="entry-content" itemprop="description">
        <div class="archives-content clearfix">

				<div class="ordered-list">
					<h3>最新文章</h3>
					<ol>
					<?php
						$myposts = get_posts('numberposts=20&orderby=post_date&order=DESC');

						foreach($myposts as $post) :
							setup_postdata($post);
					?>
						<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php
						endforeach;
					?>
					</ol>
				</div>

				<div class="ordered-list">
					<h3>按月查看</h3>
					<ol>
						<?php wp_get_archives(apply_filters('widget_archives_args', array('type' => 'monthly', 'limit' => 24))); ?>
					</ol>
				</div>

				<div class="ordered-list">
					<h3>按年查看</h3>
					<ul>
						<?php wp_get_archives(apply_filters('widget_archives_args', array('type' => 'yearly', 'limit' => 10))); ?>
					</ul>
				</div>

				<div class="ordered-list">
					<h3>分类</h3>
					<ul>
					<?php
						$terms = get_terms('category', 'orderby=name&hide_empty=0' );
						$count = count($terms);
						if($count > 0){
							foreach ($terms as $term) {
								echo '<li><a href="'.get_term_link($term, $term->slug).'" title="'.$term->name.'">'.$term->name.'</a></li>';
							}
						}
					?>
					</ul>
				</div>

				<div class="ordered-list">
					<h3>独立页面</h3>
					<ul>
					<?php
						$myposts = get_posts('numberposts=-1&orderby=post_date&order=DESC&post_type=page');

						foreach($myposts as $post) :
							setup_postdata($post);
					?>
						<li><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></li>
					<?php
						endforeach;
					?>
					</ul>
				</div>

		</div>
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
