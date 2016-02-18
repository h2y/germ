<article <?php post_class(); ?>>
    <header class="entry-header detail-page">
		<h2 class="entry-name">
			<a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
		</h2>
		<ul class="entry-meta">
			<li><i class="fa fa-clock-o"></i> <?php the_time('Y-m-d H:i');?></li>
      <?php if( dopt('d_showcategory_b')!="" ) : ?>
			   <li><i class="fa fa-pencil-square-o"></i> <?php the_category(','); ?></li>
      <?php endif; ?>
			<li class="comments_meta"><i class="fa fa-comments-o"></i> <?php
        if(comments_open())
          comments_popup_link('暂无评论', '1 条评论', '% 条评论');
        else {
          echo '<a href="';
          the_permalink();
          echo '">评论关闭</a>';
        }
      ?></li>
			<li class="views_meta"><i class="fa fa-eye"></i> <a><?php mzw_post_views(' 访问量');?></a></li>
		</ul>
    </header>
	<div class="flexslider">
		<ul class="slides">
			<?php postformat_gallery(); ?>
		</ul>
	</div>
    <div class="entry-content" itemprop="description">
        <?php the_content_nopic(); ?>
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
	<div class="prev-next clearfix">
		<?php if (get_previous_post()) {echo '<span class="prev">'; previous_post_link('<i class="fa fa-angle-left"></i>%link'); echo '</span>'; }?>
		<?php if (get_next_post()) {echo '<span class="next">'; next_post_link('%link<i class="fa fa-angle-right"></i>'); echo '</span>';}?>
	</div>
</article>

<div class="post-author box clearfix">
	<?php echo get_avatar( get_the_author_email(), $size = '80' , '' );?>
	<div class="author-meta">
		<?php
      if(dopt('d_post_bottom')!='')
        echo dopt('d_post_bottom');
      else
        echo '<p>本站所有文章均采用 <a href="http://creativecommons.org/licenses/by-sa/4.0/deed.zh_TW" target="_blank" rel="nofollow">CC BY-SA 4.0</a> 进行许可, 转载请保留链接并遵守许可协议.</p>';
    ?>
    <p class="post-buttom-link">本页固定链接: <a href="<?php the_permalink(); ?>"><?php the_permalink(); ?></a></p>
	</div>
</div>

<?php include_once('relatedpost.php')?>

<?php comments_template('', true); ?>
