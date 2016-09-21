<article <?php post_class(); ?>>
    <header class="entry-header">
        <h2 class="entry-name">
        <span class="post-prefix type-gallery">[图册]</span>
            <a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a>
        </h2>
        <ul class="entry-meta">
            <li class="time_meta"><i class="fa fa-clock-o"></i> <?php the_time('Y-m-d H:i');?></li>
                <?php if( dopt('d_showcategory_b')!="" ) : ?>
                <li class="cat_meta"><i class="fa fa-pencil-square-o"></i> <?php the_category(','); ?></li>
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
    <div class="entry-content" itemprop="description">
      <?php
    		$pc = $post->post_content;
    		$st = strip_tags(apply_filters('the_content',$pc));
    		if(has_excerpt())
    			the_excerpt();
    		elseif( preg_match('/<!--more.*?-->/',$pc) || mb_strwidth($st)<500 )
    			the_content('');
    		else
    			echo mb_strimwidth($st,0,500,' ......');
    	?>
    </div>
    <footer class="entry-footer clearfix">
    <span class="tag-links in-list"><?php the_tags( '', '', '' ); ?></span>
        <div class="post-more">
            <a href="<?php the_permalink(); ?>">阅读全文</a>
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
