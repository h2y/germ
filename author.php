
<?php get_header();
    global $wp_query;
    $curauth = $wp_query->get_queried_object();?>

<div class="box archive-meta">
    <p class="title-meta">
        <i class="fa fa-user"></i>
        来自 [<?php echo $curauth->display_name; ?>] 的全部文章
    </p>
</div>

<?php
    if( have_posts() ){
        while ( have_posts() ){
            the_post();
            get_template_part( 'inc/post-format/content', get_post_format() );
        }
    }
?>

<?php if($wp_query->max_num_pages > 1 ) { ?>
    <div class="pagination clearfix">
       <?php pagenavi($range = 3);?>
    </div>
<?php } ?>
</div></div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
