
<?php get_header(); ?>

<div class="box archive-meta">
	<p class="title-meta">
		<i class="fa fa-tag"></i>
		标签为 [<span class="title-name"><?php single_cat_title( '', true ) ?></span>] 的文章
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
