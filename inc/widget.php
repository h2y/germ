<?php  
// 删除wp自带的小工具
function unregister_default_wp_widgets() {
	unregister_widget('WP_Widget_Pages');
	//unregister_widget('WP_Widget_Calendar');
	unregister_widget('WP_Widget_Archives');
	unregister_widget('WP_Widget_Links');
	unregister_widget('WP_Widget_Meta');
	unregister_widget('WP_Widget_Search');
	unregister_widget('WP_Widget_Text');
	//unregister_widget('WP_Widget_Categories');
	unregister_widget('WP_Widget_Recent_Posts');
	unregister_widget('WP_Widget_Recent_Comments');
	unregister_widget('WP_Widget_RSS');
	unregister_widget('WP_Widget_Tag_Cloud');
	unregister_widget('WP_Nav_Menu_Widget');
}
add_action('widgets_init', 'unregister_default_wp_widgets', 1);


function mzw_sidebar(){
    register_sidebar(array(
        'id'=>'index_sidebar',
        'name'=>'首页边栏',
		'before_title' => '<h3 class="widget-title"><span>',
        'after_title' => '</h3></span>',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
    ));
	
	if( dopt('d_same_sidebar_b') == '' ) {
		register_sidebar(array(
			'id'=>'single_sidebar',
			'name'=>'文章页边栏',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</h3></span>',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
		));
		
		register_sidebar(array(
			'id'=>'page_sidebar',
			'name'=>'页面边栏',
			'before_title' => '<h3 class="widget-title"><span>',
			'after_title' => '</h3></span>',
			'before_widget' => '<aside id="%1$s" class="widget %2$s">',
			'after_widget'  => '</aside>',
		));
	}
}
add_action('widgets_init','mzw_sidebar');

add_action('widgets_init', create_function('', 'return register_widget("mzw_siderbar_post");'));

class mzw_siderbar_post extends WP_Widget {
	function mzw_siderbar_post() {
		global $prename;
		$this->WP_Widget('mzw_siderbar_post', $prename.'文章列表', array( 'description' => '多功能文章列表，可按时间、评论、随机排序' ));
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$title        = apply_filters('widget_name', $instance['title']);
		$limit        = $instance['limit'];
		$cat          = $instance['cat'];
		$orderby      = $instance['orderby'];

		echo $before_title.$title.$after_title; 

		echo mzw_posts_list( $orderby,$limit,$cat );
		
		echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance                 = $old_instance;
		$instance['title']        = strip_tags($new_instance['title']);
		$instance['limit']        = strip_tags($new_instance['limit']);
		$instance['cat']          = strip_tags($new_instance['cat']);
		$instance['orderby']      = strip_tags($new_instance['orderby']);
		return $instance;
	}
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 
			'title'        => '',
			'limit'        => '6',
			'cat'          => '',
			'orderby'      => 'date',
			) 
		);
		$title        = strip_tags($instance['title']);
		$limit        = strip_tags($instance['limit']);
		$cat          = strip_tags($instance['cat']);
		$orderby      = strip_tags($instance['orderby']);
?>

		<p>
			<label>
				标题：
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</label>
		</p>
		<p>
			<label>
				排序：
				<select class="widefat" id="<?php echo $this->get_field_id('orderby'); ?>" name="<?php echo $this->get_field_name('orderby'); ?>" style="width:100%;">
					<option value="comment_count" <?php selected('comment_count', $instance['orderby']); ?>>按评论数</option>
					<option value="date" <?php selected('date', $instance['orderby']); ?>>按发布时间</option>
					<option value="rand" <?php selected('rand', $instance['orderby']); ?>>随机显示</option>
				</select>
			</label>
		</p>
		<p>
			<label>
				分类限制：
				<a style="font-weight:bold;color:#f60;text-decoration:none;" href="javascript:;" title="格式：1,2 &nbsp;表限制ID为1,2分类的文章&#13;格式：-1,-2 &nbsp;表排除分类ID为1,2的文章&#13;也可直接写1或者-1；注意逗号须是英文的">？</a>
				<input class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo attribute_escape($cat); ?>" size="24" />
			</label>
		</p>
		<p>
			<label>
				显示数目：
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo attribute_escape($limit); ?>" size="24" />
			</label>
		</p>
<?php
	}
}

function mzw_posts_list($orderby,$limit,$cat) {

	$args = array(
		'order'            => DESC,
		'cat'              => $cat,
		'orderby'          => $orderby,
		'showposts'        => $limit,
		'caller_get_posts' => 1
	);

	query_posts($args);
	echo '<div class="smart_post"><ul>';
	while (have_posts()) : 
		the_post(); 
		global $post;
		echo '<li class="clearfix">';
		echo '<div class="post-thumb">';
		echo post_thumbnail(45, 45, false); 
		echo '</div>';
		echo '<div class="post-right">';
		echo '<h3><a href="'.get_permalink().'">';
		the_title();
		echo '</a></h3><div class="post-meta"><span>';
		comments_popup_link('No Reply', '1 Reply', '% Replies');
		echo '</span> | <span>';
		mzw_post_views(' Views');
		echo '</span></div></div>';
		echo '</li>';
    endwhile; wp_reset_query();
	echo '</ul></div>';
}



add_action('widgets_init', create_function('', 'return register_widget("mzw_siderbar_tags");'));

class mzw_siderbar_tags extends WP_Widget {
	function mzw_siderbar_tags() {
		global $prename;
		$this->WP_Widget('mzw_siderbar_tags', $prename.'标签云', array( 'description' => '适配主题的标签云' ));
	}
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		echo $before_widget;
		$tag_title        = apply_filters('widget_name', $instance['tag_title']);
		$tag_limit        = $instance['tag_limit'];

		echo $before_title.$tag_title.$after_title; 

		$tag_args = array(
		'order'         => DESC,		
		'orderby'       => count,
		'number'        => $tag_limit,		
		);
		$tags_list = get_tags($tag_args);
		if ($tags_list) {
			echo '<div class="tagcloud">';
			foreach($tags_list as $tag) {
				echo '<a href="'.get_tag_link($tag).'">'. $tag->name .'</a>'; 
			} 
			echo '</div>';
		} 
		
		
		echo $after_widget;
	}
	function update($new_instance, $old_instance) {
		$instance                 = $old_instance;
		$instance['tag_title']        = strip_tags($new_instance['tag_title']);
		$instance['tag_limit']        = strip_tags($new_instance['tag_limit']);
		return $instance;
	}
	function form($instance) {
		$instance = wp_parse_args( (array) $instance, array( 
			'tag_title'        => '',
			'tag_limit'        => '15'
			) 
		);
		$tag_title        = strip_tags($instance['tag_title']);
		$tag_limit        = strip_tags($instance['tag_limit']);
?>

		<p>
			<label>
				标题：
				<input class="widefat" id="<?php echo $this->get_field_id('tag_title'); ?>" name="<?php echo $this->get_field_name('tag_title'); ?>" type="text" value="<?php echo $instance['tag_title']; ?>" />
			</label>
		</p>	
		<p>
			<label>
				显示数目：
				<input class="widefat" id="<?php echo $this->get_field_id('tag_limit'); ?>" name="<?php echo $this->get_field_name('tag_limit'); ?>" type="number" value="<?php echo attribute_escape($tag_limit); ?>" size="24" />
			</label>
		</p>

<?php
	}
}

add_action( 'widgets_init', create_function('', 'return register_widget("mzw_reader");'));

class mzw_reader extends WP_Widget {
	function mzw_reader() {
		$widget_ops = array( 'classname' => 'mzw_reader', 'description' => '显示近期评论频繁的网友头像等' );
		$this->WP_Widget( 'mzw_reader', '活跃读者', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );

		$title = apply_filters('widget_name', $instance['title']);
		$limit = $instance['limit'];
		$outer = $instance['outer'];
		$timer = $instance['timer'];
		$addlink = $instance['addlink'];

		echo $before_widget;
		echo $before_title.$title.$after_title; 
		echo '<div class="sidebar_readers">';
		echo mzw_readers_list( $out=$outer, $tim=$timer, $lim=$limit, $addlink );
		echo '</div>';
		echo $after_widget;
	}
	function form($instance) {

?>
		<p>
			<label>
				标题：
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</label>
		</p>
		<p>
			<label>
				显示数目：
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
			</label>
		</p>
		<p>
			<label>
				排除某人：
				<input class="widefat" id="<?php echo $this->get_field_id('outer'); ?>" name="<?php echo $this->get_field_name('outer'); ?>" type="text" value="<?php echo $instance['outer']; ?>" />
			</label>
		</p>
		<p>
			<label>
				几天内：
				<input class="widefat" id="<?php echo $this->get_field_id('timer'); ?>" name="<?php echo $this->get_field_name('timer'); ?>" type="number" value="<?php echo $instance['timer']; ?>" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['addlink'], 'on' ); ?> id="<?php echo $this->get_field_id('addlink'); ?>" name="<?php echo $this->get_field_name('addlink'); ?>">加链接
			</label>
		</p>

<?php
	}
}

function mzw_readers_list($out,$tim,$lim,$addlink){
	global $wpdb;
	$counts = $wpdb->get_results("select count(comment_author) as cnt, comment_author, comment_author_url, comment_author_email from (select * from $wpdb->comments left outer join $wpdb->posts on ($wpdb->posts.id=$wpdb->comments.comment_post_id) where comment_date > date_sub( now(), interval $tim day ) and user_id='0' and comment_author != '".$out."' and post_password='' and comment_approved='1' and comment_type='') as tempcmt group by comment_author order by cnt desc limit $lim");
	foreach ($counts as $count) {
		$c_url = $count->comment_author_url;
		if ($c_url == '') $c_url = 'javascript:;';

		if($addlink == 'on'){
			$c_urllink = ' href="'. $c_url . '"';
		}else{
			$c_urllink = '';
		}
		$type .= '<a title="['.$count->comment_author.']" target="_blank"'.$c_urllink.'>'.get_avatar( $count->comment_author_email, $size = '36' , '' ) .'</a>';
	}
	return $type;
}



add_action( 'widgets_init', create_function('', 'return register_widget("mzw_recent_comment");'));

class mzw_recent_comment extends WP_Widget {
	function mzw_recent_comment() {
		$widget_ops = array( 'classname' => 'mzw_recent_comment', 'description' => '显示近期评论' );
		$this->WP_Widget( 'mzw_recent_comment', '最近评论', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title = apply_filters('widget_name', $instance['title']);
		$limit = $instance['limit'];
		$addlink = $instance['addlink'];

		echo $before_widget;
		echo $before_title.$title.$after_title; 
		echo '<ul id="recentcomments">';
		mzw_recent_comment_list($lim=$limit, $addlink );
		echo '</ul>';
		echo $after_widget;
	}
	function form($instance) {

?>
		<p>
			<label>
				标题：
				<input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $instance['title']; ?>" />
			</label>
		</p>
		<p>
			<label>
				显示数目：
				<input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo $instance['limit']; ?>" />
			</label>
		</p>
		<p>
			<label>
				<input style="vertical-align:-3px;margin-right:4px;" class="checkbox" type="checkbox" <?php checked( $instance['addlink'], 'on' ); ?> id="<?php echo $this->get_field_id('addlink'); ?>" name="<?php echo $this->get_field_name('addlink'); ?>">加链接
			</label>
		</p>

<?php
	}
}

function mzw_recent_comment_list($lim,$addlink){
	$my_email = get_bloginfo ('admin_email');
	$counts = get_comments('number=200&status=approve&type=comment'); 
	$i = 1;
	foreach ($counts as $count) {
		if ($count->comment_author_email != $my_email) {
			$c_url = $count->comment_author_url;
			if ($c_url == '') $c_url = 'javascript:;';
			if($addlink == 'on'){
				$c_urllink = ' href="'. $c_url . '"';
			}else{
				$c_urllink = ' href="javascript:;"';
			}
			echo '<li class="recentcomments clearfix"><div class="alignleft">'.get_avatar( $count->comment_author_email, $size = '45' , '' ).
			'</div><div class="comment-right"><span class="comment-author"><a'.$c_urllink.'>'.$count->comment_author.'</a>: </span><div class="comment-c"><a href="'.
			get_permalink($count->comment_post_ID).'#comment-'.$count->comment_ID.'">'.$count->comment_content.
			'</a></div></div></li>';
			if ($i == $lim) break;
			$i++;
		}
	}
}

add_action( 'widgets_init', create_function('', 'return register_widget("mzw_search");'));

class mzw_search extends WP_Widget {
	function mzw_search() {
		$widget_ops = array( 'classname' => 'mzw_search', 'description' => '站内搜索' );
		$this->WP_Widget( 'mzw_search', '站内搜索', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		?>
		<form id="searchform" class="searchform" action="<?php echo get_bloginfo ('url'); ?>" method="GET">
			<div>
				<input name="s" id="s" size="15" placeholder="Enter Keywords..." type="text">
				<input value="Search" type="submit">
			</div>
		</form>
<?php
		echo $after_widget;
	}
	function form($instance) {
?>
		<p>
			<label>
			无选项
			</label>
		</p>

<?php
	}
}

add_action( 'widgets_init', create_function('', 'return register_widget("mzw_admin");'));

class mzw_admin extends WP_Widget {
	function mzw_admin() {
		$widget_ops = array( 'classname' => 'mzw_admin', 'description' => '显示作者的信息机个人简介' );
		$this->WP_Widget( 'mzw_admin', '作者信息', $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		echo $before_widget;
		?>
		<img src="<?php bloginfo('template_directory'); ?>/images/bg_small.jpg">
		<div class="author-body">
			<div class="author_img">
			<?php echo get_avatar( get_the_author_email(), $size = '80' , '' );?>
			</div>
			<div class="author_bio">
				<h3><?php the_author_meta('nickname');?> </h3>
				<p class="muted"><?php the_author_meta('user_description');?> </p>
			</div>
		</div>
		<?php if( dopt('d_sns_open') ) {
			echo '<div class="social">';
			if( dopt('d_rss_b') ) echo '<a class="rss" href="'.dopt('d_rss').'"><i class="fa fa-rss"></i></a>';
			if( dopt('d_mail_b') ) echo '<a class="mail" href="'.dopt('d_mail').'"><i class="fa fa-envelope"></i></a>';
			if( dopt('d_rss_sina_b') ) echo '<a class="weibo" href="'.dopt('d_rss_sina').'"><i class="fa fa-weibo"></i></a>';
			if( dopt('d_rss_twitter_b') ) echo '<a class="twitter" href="'.dopt('d_rss_twitter').'"><i class="fa fa-twitter"></i></a>';
			if( dopt('d_rss_google_b') ) echo '<a class="google" href="'.dopt('d_rss_google').'"><i class="fa fa-google-plus "></i></a>';
			if( dopt('d_rss_facebook_b') ) echo '<a class="facebook" href="'.dopt('d_rss_facebook').'"><i class="fa fa-facebook"></i></a>';
			if( dopt('d_rss_github_b') ) echo '<a class="github" href="'.dopt('d_rss_github').'"><i class="fa fa-github"></i></a>';
			if( dopt('d_rss_tencent_b') ) echo '<a class="tweibo" href="'.dopt('d_rss_tencent').'"><i class="fa fa-tencent-weibo"></i></a>';
			if( dopt('d_rss_linkedin_b') ) echo '<a class="linkedin" href="'.dopt('d_rss_linkedin').'"><i class="fa fa-linkedin"></i></a>';
			//if( dopt('d_rss_b') ) echo '<a class="weixin" href="'.dopt('d_rss').'"><i class="fa fa-weixin"></i></a>';
			echo '</div>';
		}
		?>
<?php
		echo $after_widget;
	}
	function form($instance) {
?>
		<p>
			<label>
			无选项
			</label>
		</p>

<?php
	}
}


?>