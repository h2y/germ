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
    <div class="entry-content" itemprop="description">
        <?php
		$pc=$post->post_content;
		$st=strip_tags(apply_filters('the_content',$pc));
		if(has_excerpt())
			the_excerpt();
		elseif(preg_match('/<!--more.*?-->/',$pc) || mb_strwidth($st)<500)
			the_content('');
		elseif(function_exists('mb_strimwidth'))
			echo'<p>'.mb_strimwidth($st,0,500,' ...').'</p>';
		else the_content('');
		?>
    </div>
    <footer class="entry-footer clearfix">
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
		<div class="post-more">
			<a href="<?php the_permalink(); ?>">Read More</a>
		</div>
	</footer>
</article>