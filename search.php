<?php get_header();?>

<?php 
	if( have_posts() ){
?>

<div class="box archive-meta">
	<h3 class="title-meta"><?php _e('Search Results', 'quench')?></h3>
	<?php echo '<div class="desc-meta"><span class="top">◆</span>'; printf(__('Searching for %s', 'quench' ), $s); echo '</div>'; ?>
</div>
<?php	
		while ( have_posts() ){
			the_post(); 
			get_template_part( 'inc/post-format/content', get_post_format() );
		}
	} else {
?>

<div class="box archive-meta">
	<h3 class="title-meta"><?php _e('Apologies, but no results were found.', 'quench')?></h3>
</div>
<?php
	}

?>

<?php if($wp_query->max_num_pages > 1 ) { ?>
    <div class="pagination clearfix">
       <?php pagenavi($range = 3);?>
    </div>
<?php } ?>
</div>
</div>

<?php get_sidebar(); ?>

<?php get_footer(); ?>