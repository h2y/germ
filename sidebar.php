<div id="sidebar">
	<?php 
		if(is_dynamic_sidebar() && is_home()) 
			dynamic_sidebar('index_sidebar');
		elseif(is_dynamic_sidebar() && is_single()) 
			dynamic_sidebar('single_sidebar');
		else 
			dynamic_sidebar('page_sidebar');
	?>
</div>