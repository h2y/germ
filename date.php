
<?php get_header(); ?>

<div class="box archive-meta">
	<h3 class="title-meta">
		<?php 
			if(is_day()) echo the_time('Y年m月j日');
			elseif(is_month()) echo the_time('Y年m月');
			elseif(is_year()) echo the_time('Y年'); 
		?>的存档
		</h3>
	<?php echo '<div class="desc-meta"><span class="top">◆</span>文章存档</div>'; ?>
</div>

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
</div></div>

<?php get_sidebar(); ?>


<?php get_footer(); ?>