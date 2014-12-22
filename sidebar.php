<div id="sidebar">
	<?php
		if( dopt('d_same_sidebar_b') != '' ) {
			if(is_dynamic_sidebar()) 
				dynamic_sidebar('index_sidebar');
		} else {
			if(is_dynamic_sidebar() && is_home()) 
				dynamic_sidebar('index_sidebar');
			elseif(is_dynamic_sidebar() && is_single()) 
				dynamic_sidebar('single_sidebar');
			else 
				dynamic_sidebar('page_sidebar');
		}
	?>
</div>