<?php get_header(); ?>

<div class="box archive-meta">
    <h3 class="title-meta"><?php _e('Article Archive', 'quench')?></h3>
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
