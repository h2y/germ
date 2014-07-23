<?php get_header(); ?>

<?php 

	if( have_posts() ){ 
		while ( have_posts() ){
			the_post(); 
			get_template_part( 'inc/post-format/content', get_post_format() );
		}
	}

?>

    <div class="pagination clearfix">
       <?php pagenavi($range = 3);?>
    </div>
</div>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>