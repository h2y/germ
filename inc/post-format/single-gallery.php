<article <?php post_class(); ?>>
    <header class="entry-header">
		<h2 class="entry-name">
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>
		<ul class="entry-meta">
			<li><i class="fa fa-clock-o"></i> <?php the_time('d,m,Y');?></li>
			<li><?php echo'<i class="fa fa-pencil-square-o"></i> ';the_category(','); ?></li>
			<li><i class="fa fa-comments-o"></i> <?php comments_popup_link('No Reply', '1 Reply', '% Replies'); ?></li>
			<li><i class="fa fa-eye"></i> <?php mzw_post_views(' Views');?></li>
		</ul>
    </header>
	<div class="flexslider">
		<ul class="slides">
			<?php if(postformat_gallery()) postformat_gallery();?>
		</ul>
	</div>
    <div class="entry-content" itemprop="description">
        <?php the_content_nopic(); ?>
    </div>
	<footer class="entry-footer clearfix">
		<div class="post-share">
			<a href="javascript:;"><i class="fa fa-share-alt"></i> 分享</a>
			<ul>
				<li><a href="http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank"><i class="fa fa-qq"></i></a></li>
				<li><a href="http://service.weibo.com/share/share.php?title=<?php the_title(); ?>&url=<?php the_permalink(); ?>" target="_blank"><i class="fa fa-weibo"></i></a></li>
				<li><a href="http://share.renren.com/share/buttonshare?link=<?php the_permalink(); ?>&title=<?php the_title(); ?>" target="_blank"><i class="fa fa-renren"></i></a></li>
				<li><a href="http://twitter.com/share?url=<?php the_permalink(); ?>&text=<?php the_title(); ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
			</ul>
		</div>
		<div class="post-love">
			<a href="javascript:;" data-action="ding" data-id="<?php the_ID(); ?>" class="favorite post-love-link <?php if(isset($_COOKIE['mzw_ding_'.$post->ID])) echo ' done';?>" title="Love this"><i class="fa fa-heart-o"></i> 
			<span class="love-count">
				<?php if( get_post_meta($post->ID,'mzw_ding',true) ){            
                    echo get_post_meta($post->ID,'mzw_ding',true);
                 } else {
                    echo '0';
                 }?>
			</span></a>
		</div>
		<div class="post-tags">
			<?php if ( get_the_tags() ) { echo 'Tags: '; the_tags('', ', ', ' ');}?>
		</div>
	</footer>
	<div class="prev-next clearfix">
		<?php if (get_previous_post()) {echo '<span class="prev">'; previous_post_link('<i class="fa fa-angle-left"></i>%link'); echo '</span>'; }?>
		<?php if (get_next_post()) {echo '<span class="next">'; next_post_link('%link<i class="fa fa-angle-right"></i>'); echo '</span>';}?>
	</div>
</article>

<div class="post-author box clearfix">
	<?php echo get_avatar( get_the_author_email(), $size = '80' , '' );?>
	<div class="author-meta">
		<p class="name"><?php the_author(); ?></p>
		<p class="description"><?php the_author_description(); ?></p>
		
	</div>
	
</div>

<?php include_once('relatedpost.php')?>

<?php comments_template('', true); ?>