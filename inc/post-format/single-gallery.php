<article <?php post_class(); ?>>
    <header class="entry-header detail-page">
        <h2 class="entry-name">
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
    <div class="flexslider">
        <ul class="slides">
            <?php postformat_gallery(); ?>
      <?php wp_link_pages( array( 'before' => '<div class="content-pager"><span class="pager_text">分页 : </span>', 'after' => '</div>', 'link_before' => '<span class="page_link">', 'link_after' => '</span>' ) ); ?>
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
                  $ding_num = get_post_meta($post->ID,'mzw_ding',true);
                  echo $ding_num? $ding_num : '0';
                ?>
            </span></a>
        </div>
    <?php endif; ?>
    </footer>
    <div class="prev-next clearfix">
    <?php if (get_previous_post()) : ?>
    <div class="prev">
      <span class="prev_text"><?php previous_post_link('%link', '上一篇'); ?></span>
      <span class="prev_link"><?php previous_post_link('%link'); ?></span>
    </div>
    <?php endif;
          if (get_next_post()) : ?>
    <div class="next">
      <span class="next_link"><?php next_post_link('%link'); ?></span>
      <span class="next_text"><?php next_post_link('%link', '下一篇'); ?></span>
    </div>
    <?php endif; ?>
    </div>
</article>

<div class="post-author box clearfix">
    <?php
        if(dopt('d_defaultavatar_b'))
            echo get_avatar( get_the_author_meta('email'), $size = '80' , '' );
        else {
            $head_src = dopt('d_myavatar') ? dopt('d_myavatar') : "http://q.qlogo.cn/qqapp/100229475/F1260A6CECA521F6BE517A08C4294D8A/100";
            echo '<img src="'.$head_src.'" class="avatar avatar-80 photo" height="80" width="80">';
        }
    ?>
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

<?php if(comments_open()) comments_template('', true); ?>
