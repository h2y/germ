<?php
//载入jquery库
wp_enqueue_script( 'jquerylib', get_template_directory_uri() . '/js/jquery-1.10.2.min.js' , array(), '1.10.2', false);    
wp_enqueue_script( 'jquerymigrate', get_template_directory_uri() . '/js/jquery-migrate-1.2.1.js' , array(), '1.2.1', false);    
wp_enqueue_script( 'base', get_template_directory_uri() . '/js/global.js', array(), '1.00', true);
wp_enqueue_script( 'slider', get_template_directory_uri() . '/js/jquery.flexslider-min.js', array(), '1.00', true);
wp_enqueue_script( 'slimbox', get_template_directory_uri() . '/js/slimbox2.min.js', array(), '1.00', true);
wp_enqueue_script( 'jplayer', get_template_directory_uri() . '/js/jquery.jplayer.min.js', array(), '1.00', true);
if( dopt('d_ajax_b') != '' )
	wp_enqueue_script( 'ajax', get_template_directory_uri() . '/js/ajax.js', array(), '1.00', true);
if( dopt('d_autospace_b') != '' )
	wp_enqueue_script( 'autospace', get_template_directory_uri() . '/js/autospace.min.js', array(), '1.00', true);

wp_localize_script('base', 'ajax', array(
	'ajax_url' => admin_url('admin-ajax.php'),
	'home' => home_url()
));


include_once('inc/widget.php');
include_once('inc/themeset.php');

add_action('after_setup_theme', 'my_theme_setup');
function my_theme_setup(){
    load_theme_textdomain('quench', get_template_directory() . '/languages');
}

remove_action('wp_head','wp_generator');//禁止在head泄露wordpress版本号
remove_action('wp_head','rsd_link');//移除head中的rel="EditURI"
remove_action('wp_head','wlwmanifest_link');//移除head中的rel="wlwmanifest"
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );//rel=pre
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );//rel=shortlink 
//隐藏admin Bar
function hide_admin_bar($flag) {
	return false;
}
add_filter('show_admin_bar','hide_admin_bar'); 

//remove_filter ('the_content', 'wpautop');

remove_filter ('comment_text', 'wpautop');

register_nav_menus(array('header-menu' => '顶部导航'));

add_theme_support( 'post-formats', array( 'status', 'image', 'gallery', 'audio' ));

function dopt($e){
    return stripslashes(get_option($e));
}

function no_self_ping( &$links ) {
	$home = get_option( 'home' );
	foreach ( $links as $l => $link )
		if ( 0 === strpos( $link, $home ) )
	unset($links[$l]);
}
if(dopt('d_nopingback_b')){
	add_action( 'pre_ping', 'no_self_ping' );
}

function pagenavi($range = 7){
	global $paged, $wp_query;
	if ( !$max_page ) {$max_page = $wp_query->max_num_pages;}
	if($max_page > 1){if(!$paged){$paged = 1;}
	if($paged>1) echo '<a href="' . get_pagenum_link($paged-1) .'"><</a>';
    if($max_page > $range){
		if($paged < $range){for($i = 1; $i <= ($range + 1); $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " class='current'";echo ">$i</a>";}}
    elseif($paged >= ($max_page - ceil(($range/2)))){
		for($i = $max_page - $range; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
		if($i==$paged)echo " class='current'";echo ">$i</a>";}}
	elseif($paged >= $range && $paged < ($max_page - ceil(($range/2)))){
		for($i = ($paged - ceil($range/2)); $i <= ($paged + ceil(($range/2))); $i++){echo "<a href='" . get_pagenum_link($i) ."'";if($i==$paged) echo " class='current'";echo ">$i</a>";}}}
    else{for($i = 1; $i <= $max_page; $i++){echo "<a href='" . get_pagenum_link($i) ."'";
    if($i==$paged)echo " class='current'";echo ">$i</a>";}}
	if($paged<$max_page) echo '<a href="' . get_pagenum_link($paged+1) .'">></a>';
   }
}

function comment($comment, $args, $depth) {
   $GLOBALS['comment'] = $comment;
?>
   <li <?php comment_class(); ?><?php if( $depth > 2){ echo ' style="margin-left:-50px;"';} ?> id="li-comment-<?php comment_ID() ?>">	 

<article id="comment-<?php comment_ID(); ?>" class="comment-body">
	<div class="comment-meta clearfix">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, $size = '40'); ?>
		</div>
		<div class="comment-metadata">
			<b class="fn"><?php printf(__('%s'), get_comment_author_link()) ?></b>
			<time datetime="<?php echo time_ago(); ?>"><?php echo time_ago(); ?></time>
		</div>
		<div class="reply">
			<?php comment_reply_link(array_merge( $args, array('reply_text' => '回复','depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
		</div>
	</div>

	<div class="comment-content">
		<?php comment_text() ?>
	</div>
</article>


<?php

}


function time_ago( $type = 'commennt', $day = 30 ) {
	$d = $type == 'post' ? 'get_post_time' : 'get_comment_time';
	$timediff = time() - $d('U');
	if ($timediff <= 60*60*24*$day) {
		echo  human_time_diff($d('U'), strtotime(current_time('mysql', 0))), '前';
	}
	if ($timediff > 60*60*24*$day) {
		echo  date('Y/m/d',get_comment_date('U')), ' ', get_comment_time('H:i');
	};
}

// 新窗口打开评论链接
function hu_popuplinks($text) {
	$text = preg_replace('/<a (.+?)>/i', "<a $1 target='_blank'>", $text);
	return $text;
}
add_filter('get_comment_author_link', 'hu_popuplinks', 6);

function add_nofollow($link, $args, $comment, $post){
	return preg_replace( '/href=\'(.*(\?|&)replytocom=(\d+)#respond)/', 'href=\'#comment-$3', $link );
}
add_filter('comment_reply_link', 'add_nofollow', 420, 4);

function mzw_description() {
    global $s, $post;
    $description = '';
    $blog_name = get_bloginfo('name');
    if ( is_singular() ) {
        $ID = $post->ID;
        $title = $post->post_title;
        $author = $post->post_author;
        $user_info = get_userdata($author);
        $post_author = $user_info->display_name;
        if (!get_post_meta($ID, "meta-description", true)) {$description = $title.' - 作者: '.$post_author.',首发于'.$blog_name;}
        else {$description = get_post_meta($ID, "meta-description", true);}
    } elseif ( is_home () )    { $description = dopt('d_description');
    } elseif ( is_tag() )      { $description = single_tag_title('', false) . " - ". trim(strip_tags(tag_description()));
    } elseif ( is_category() ) { $description = single_cat_title('', false) . " - ". trim(strip_tags(category_description()));
    } elseif ( is_archive() )  { $description = $blog_name . "'" . trim( wp_title('', false) ) . "'";
    } elseif ( is_search() )   { $description = $blog_name . ": '" . esc_html( $s, 1 ) . "' 的搜索結果";
    } else { $description = $blog_name . "'" . trim( wp_title('', false) ) . "'";
    }
    $description = mb_substr( $description, 0, 220, 'utf-8' );
    echo "<meta name=\"description\" content=\"$description\">\n";
}
add_action('wp_head','mzw_description');

function catch_that_image() {
  global $post, $posts;
  $first_img = '';
  ob_start();
  ob_end_clean();
  $output = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $post->post_content, $matches);
  $first_img = $matches [1][0];
  if(empty($first_img)){ //Defines a default image
    return false;
  }
  echo '<a href="'.$first_img.'"><img src="'.$first_img.'"/></a>';
}

//删除内容中的图片
function the_content_nopic($more_link_text = null, $stripteaser = false) {
	$content = get_the_content($more_link_text, $stripteaser);
	$content = apply_filters('the_content', $content);
	$content = str_replace(']]>', ']]&gt;', $content);
	$content = preg_replace('/<\s*img\s+[^>]*?src\s*=\s*(\'|\")(.*?)\\1[^>]*?\/?\s*>/i', "", $content);
	echo $content;
}

function postformat_gallery(){
    global $post;
    ob_start();
    ob_end_clean();
    preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i',$post->post_content,$matches ,PREG_SET_ORDER);
    $cnt = count( $matches );
    if($cnt>0){
        $images = "";
        $nav = "";
        for($i=0; $i<$cnt; $i++){
            $src = $matches[$i][1];
            $images .= '<li data-thumb="'.$src.'"><img src="'.$src.'" /></li>';
        }
		echo $images;
    } else {
		return false;
	}
}


if ( ! function_exists( 'mzw_post_views' ) ) :
function record_visitors(){
	if (is_singular()) 
	{
	  global $post;
	  $post_ID = $post->ID;
	  if($post_ID) 
	  {
		  $post_views = (int)get_post_meta($post_ID, 'views', true);
		  if(!update_post_meta($post_ID, 'views', ($post_views+1))) 
		  {
			add_post_meta($post_ID, 'views', 1, true);
		  }
	  }
	}
}
add_action('wp_head', 'record_visitors');  

function mzw_post_views($after=''){
  global $post;
  $post_ID = $post->ID;
  $views = (int)get_post_meta($post_ID, 'views', true);
  echo $views, $after;
}
endif;


add_action('wp_ajax_nopriv_mzw_like', 'mzw_like');
add_action('wp_ajax_mzw_like', 'mzw_like');
function mzw_like(){
    global $wpdb,$post;
    $id = $_POST["um_id"];
    $action = $_POST["um_action"];
    if ( $action == 'ding'){
    $mzw_raters = get_post_meta($id,'mzw_ding',true);
    $expire = time() + 99999999;
    $domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false; // make cookies work with localhost
    setcookie('mzw_ding_'.$id,$id,$expire,'/',$domain,false);
    if (!$mzw_raters || !is_numeric($mzw_raters)) {
        update_post_meta($id, 'mzw_ding', 1);
    } 
    else {
            update_post_meta($id, 'mzw_ding', ($mzw_raters + 1));
        }
    echo get_post_meta($id,'mzw_ding',true);
    }
    die;
}

add_theme_support( 'post-thumbnails' );



function post_thumbnail( $width = 180,$height = 180 ,$flag = true){
    global $post;
    if( has_post_thumbnail() ){
        $timthumb_src = wp_get_attachment_image_src(get_post_thumbnail_id($post->ID),'full');
        if($flag) {
			$post_timthumb = '<a href="'.get_permalink().'"><img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$timthumb_src[0].'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" title="'.get_the_title().'"/></a>';
		} else {
			$post_timthumb = '<img src="'.get_bloginfo("template_url").'/timthumb.php?src='.$timthumb_src[0].'&amp;h='.$height.'&amp;w='.$width.'&amp;zc=1" alt="'.$post->post_title.'" title="'.get_the_title().'"/>';
		}
        return $post_timthumb;
    } else {
		$content = $post->post_content;
		preg_match_all('/<img.*?(?: |\\t|\\r|\\n)?src=[\'"]?(.+?)[\'"]?(?:(?: |\\t|\\r|\\n)+.*?)?>/sim', $content, $strResult, PREG_PATTERN_ORDER);
		$n = count($strResult[1]);
		if($n > 0){
			if($flag) {
				return '<a href="'.get_permalink().'"><img src="'.get_bloginfo("template_url").'/timthumb.php?w='.$width.'&amp;h='.$height.'&amp;src='.$strResult[1][0].'" title="'.get_the_title().'" alt="'.get_the_title().'"/></a>';
			} else {
				return '<img src="'.get_bloginfo("template_url").'/timthumb.php?w='.$width.'&amp;h='.$height.'&amp;src='.$strResult[1][0].'" title="'.get_the_title().'" alt="'.get_the_title().'"/>';
			}
		} else {
			if($flag) {
				return '<a href="'.get_permalink().'"><img class="rounded" src="'.get_bloginfo('template_url').'/images/random/'.rand(1,7).'.jpg" title="'.get_the_title().'" alt="'.get_the_title().'"/></a>';
			} else {
				return '<img class="rounded" src="'.get_bloginfo('template_url').'/images/random/'.rand(1,7).'.jpg" title="'.get_the_title().'" alt="'.get_the_title().'" width="'.$width.'" height="'.$height.'"/>';
			}
		}
	}
}


/*ajax comment submit*/
add_action('wp_ajax_nopriv_ajax_comment', 'ajax_comment');
add_action('wp_ajax_ajax_comment', 'ajax_comment');
function ajax_comment(){
    global $wpdb;
    //nocache_headers();
    $comment_post_ID = isset($_POST['comment_post_ID']) ? (int) $_POST['comment_post_ID'] : 0;
    $post = get_post($comment_post_ID);
    $post_author = $post->post_author;
    if ( empty($post->comment_status) ) {
        do_action('comment_id_not_found', $comment_post_ID);
        ajax_comment_err('Invalid comment status.');
    }
    $status = get_post_status($post);
    $status_obj = get_post_status_object($status);
    if ( !comments_open($comment_post_ID) ) {
        do_action('comment_closed', $comment_post_ID);
        ajax_comment_err('Sorry, comments are closed for this item.');
    } elseif ( 'trash' == $status ) {
        do_action('comment_on_trash', $comment_post_ID);
        ajax_comment_err('Invalid comment status.');
    } elseif ( !$status_obj->public && !$status_obj->private ) {
        do_action('comment_on_draft', $comment_post_ID);
        ajax_comment_err('Invalid comment status.');
    } elseif ( post_password_required($comment_post_ID) ) {
        do_action('comment_on_password_protected', $comment_post_ID);
        ajax_comment_err('Password Protected');
    } else {
        do_action('pre_comment_on_post', $comment_post_ID);
    }
    $comment_author       = ( isset($_POST['author']) )  ? trim(strip_tags($_POST['author'])) : null;
    $comment_author_email = ( isset($_POST['email']) )   ? trim($_POST['email']) : null;
    $comment_author_url   = ( isset($_POST['url']) )     ? trim($_POST['url']) : null;
    $comment_content      = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
	$edit_id              = ( isset($_POST['edit_id']) ) ? $_POST['edit_id'] : null; // 提取 edit_id
    $user = wp_get_current_user();
    if ( $user->exists() ) {
        if ( empty( $user->display_name ) )
            $user->display_name=$user->user_login;
        $comment_author       = $wpdb->escape($user->display_name);
        $comment_author_email = $wpdb->escape($user->user_email);
        $comment_author_url   = $wpdb->escape($user->user_url);
        $user_ID			  = $wpdb->escape($user->ID);
        if ( current_user_can('unfiltered_html') ) {
            if ( wp_create_nonce('unfiltered-html-comment_' . $comment_post_ID) != $_POST['_wp_unfiltered_html_comment'] ) {
                kses_remove_filters();
                kses_init_filters();
            }
        }
    } else {
        if ( get_option('comment_registration') || 'private' == $status )
            ajax_comment_err('Sorry, you must be logged in to post a comment.');
    }
    $comment_type = '';
    if ( get_option('require_name_email') && !$user->exists() ) {
        if ( 6 > strlen($comment_author_email) || '' == $comment_author )
            ajax_comment_err( 'Error: please fill the required fields (name, email).' );
        elseif ( !is_email($comment_author_email))
            ajax_comment_err( 'Error: please enter a valid email address.' );
    }
    if ( '' == $comment_content )
        ajax_comment_err( 'Error: please type a comment.' );
    $dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
    if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
    $dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
    if ( $wpdb->get_var($dupe) ) {
        ajax_comment_err('Duplicate comment detected; it looks as though you&#8217;ve already said that!');
    }
    if ( $lasttime = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) {
        $time_lastcomment = mysql2date('U', $lasttime, false);
        $time_newcomment  = mysql2date('U', current_time('mysql', 1), false);
        $flood_die = apply_filters('comment_flood_filter', false, $time_lastcomment, $time_newcomment);
        if ( $flood_die ) {
            ajax_comment_err('You are posting comments too quickly.  Slow down.');
        }
    }
    $comment_parent = isset($_POST['comment_parent']) ? absint($_POST['comment_parent']) : 0;
    $commentdata = compact('comment_post_ID', 'comment_author', 'comment_author_email', 'comment_author_url', 'comment_content', 'comment_type', 'comment_parent', 'user_ID');

    if ( $edit_id )
	{
		$comment_id = $commentdata['comment_ID'] = $edit_id;
		if( ihacklog_user_can_edit_comment($commentdata,$comment_id) )
		{  
			wp_update_comment( $commentdata );
		}
		else
		{
			ajax_comment_err( 'Cheatin&#8217; uh?' );
		}

	}
	else
	{
	$comment_id = wp_new_comment( $commentdata );
	}

    $comment = get_comment($comment_id);
    do_action('set_comment_cookies', $comment, $user);
    $comment_depth = 1;
    $tmp_c = $comment;
    while($tmp_c->comment_parent != 0){
        $comment_depth++;
        $tmp_c = get_comment($tmp_c->comment_parent);
    }
    $GLOBALS['comment'] = $comment;
    ?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
	<article id="comment-<?php comment_ID(); ?>" class="comment-body">
		<div class="comment-meta clearfix">
			<div class="comment-author vcard">
				<?php echo get_avatar( $comment, $size = '40'); ?>
			</div>
			<div class="comment-metadata">
				<b class="fn"><?php printf(__('%s'), get_comment_author_link()) ?></b>
				<time datetime="<?php echo time_ago(); ?>"><?php echo time_ago(); ?></time>
			</div>
		</div>
		<?php if ( '0' == $comment->comment_approved ) : ?>
			<p class="comment-awaiting-moderation">您的评论正在排队等待审核，请稍后再来！</p>
		<?php endif; ?>

		<div class="comment-content">
			<?php comment_text() ?>
		</div>
	</article>

    <?php die();

}
function ajax_comment_err($a) {
    header('HTTP/1.0 500 Internal Server Error');
    header('Content-Type: text/plain;charset=UTF-8');
    echo $a;
    exit;
}

function ihacklog_user_can_edit_comment($new_cmt_data,$comment_ID = 0) {
    if(current_user_can('edit_comment', $comment_ID)) {
        return true;
    }
    $comment = get_comment( $comment_ID );
    $old_timestamp = strtotime( $comment->comment_date);
    $new_timestamp = current_time('timestamp');
    // 不用get_comment_author_email($comment_ID) , get_comment_author_IP($comment_ID)
    $rs = $comment->comment_author_email === $new_cmt_data['comment_author_email']
            && $comment->comment_author_IP === $_SERVER['REMOTE_ADDR']
                && $new_timestamp - $old_timestamp < 3600;
    return $rs;
}


add_action('wp_ajax_nopriv_ajax_comment_page_nav', 'ajax_comment_page_nav');
add_action('wp_ajax_ajax_comment_page_nav', 'ajax_comment_page_nav');

function ajax_comment_page_nav(){
    global $post,$wp_query, $wp_rewrite;
    $postid = $_POST["um_post"];
    $pageid = $_POST["um_page"];
    $comments = get_comments('post_id='.$postid.'&status=approve');
    $post = get_post($postid);
    if( 'desc' != get_option('comment_order') ){
        $comments = array_reverse($comments);
    }
    $wp_query->is_singular = true;
    $baseLink = '';
    if ($wp_rewrite->using_permalinks()) {
        $baseLink = '&base=' . user_trailingslashit(get_permalink($postid) . 'comment-page-%#%', 'commentpaged');
    }
    echo '<ol class="comments-list">';
    wp_list_comments('type=comment&style=ol&callback=comment&page=' . $pageid . '&per_page=' . get_option('comments_per_page'), $comments);
    echo '</ol>';
    echo '<nav class="commentnav" data-postid="'.$postid.'">';
    paginate_comments_links('current=' . $pageid . '&prev_text=«&next_text=»');
    echo '</nav>';
    die;
}

function specs_getfirstchar($s0){  
    $fchar = ord($s0{0});
    if($fchar >= ord("A") and $fchar <= ord("z") )return strtoupper($s0{0});
    $s1 = iconv("UTF-8","gb2312", $s0);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $s0){$s = $s1;}else{$s = $s0;}
    $asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
    if($asc >= -20319 and $asc <= -20284) return "A";
    if($asc >= -20283 and $asc <= -19776) return "B";
    if($asc >= -19775 and $asc <= -19219) return "C";
    if($asc >= -19218 and $asc <= -18711) return "D";
    if($asc >= -18710 and $asc <= -18527) return "E";
    if($asc >= -18526 and $asc <= -18240) return "F";
    if($asc >= -18239 and $asc <= -17923) return "G";
    if($asc >= -17922 and $asc <= -17418) return "I";
    if($asc >= -17417 and $asc <= -16475) return "J";
    if($asc >= -16474 and $asc <= -16213) return "K";
    if($asc >= -16212 and $asc <= -15641) return "L";
    if($asc >= -15640 and $asc <= -15166) return "M";
    if($asc >= -15165 and $asc <= -14923) return "N";
    if($asc >= -14922 and $asc <= -14915) return "O";
    if($asc >= -14914 and $asc <= -14631) return "P";
    if($asc >= -14630 and $asc <= -14150) return "Q";
    if($asc >= -14149 and $asc <= -14091) return "R";
    if($asc >= -14090 and $asc <= -13319) return "S";
    if($asc >= -13318 and $asc <= -12839) return "T";
    if($asc >= -12838 and $asc <= -12557) return "W";
    if($asc >= -12556 and $asc <= -11848) return "X";
    if($asc >= -11847 and $asc <= -11056) return "Y";
    if($asc >= -11055 and $asc <= -10247) return "Z";
    return null;
}
function specs_pinyin($zh){
    $ret = "";
    $s1 = iconv("UTF-8","gb2312", $zh);
    $s2 = iconv("gb2312","UTF-8", $s1);
    if($s2 == $zh){$zh = $s1;}
    $s1 = substr($zh,$i,1);
    $p = ord($s1);
    if($p > 160){
        $s2 = substr($zh,$i++,2);
        $ret .= specs_getfirstchar($s2);
    }else{
        $ret .= $s1;
    }
    return strtoupper($ret);
}

function specs_show_tags() {
    //if(!$output = get_option('specs_tags_list')){
        $categories = get_terms( 'post_tag', array(
            'orderby'    => 'count',
            'hide_empty' => 1
         ) );
        foreach($categories as $v){
            for($i = 65; $i <= 90; $i++){
                if(specs_pinyin($v->name) == chr($i)){
                    $r[chr($i)][] = $v;
                }
            }
            for($i=48;$i<=57;$i++){
                if(specs_pinyin($v->name) == chr($i)){
                    $r[chr($i)][] = $v;
                }
            }
        }
        ksort($r);
        $output = "<ul id='tag-letter'>";
        for($i=65;$i<=90;$i++){
            $tagi = $r[chr($i)];
            if(is_array($tagi)){
                $output .= "<li><a href='#".chr($i)."'>".chr($i)."</a></li>";
            }else{
                $output .= "<li><a class='none' href='javascript:;'>".chr($i)."</a></li>";
            }
        }
        for($i=48;$i<=57;$i++){
            $tagi = $r[chr($i)];
            if(is_array($tagi)){
                $output .= "<li><a href='#".chr($i)."'>".chr($i)."</a></li>";
            }else{
                $output .= "<li><a class='none' href='javascript:;'>".chr($i)."</a></li>";
            }
        }
        $output .= "</ul>";
        $output .= "<ul id='all-tags'>";
        for($i=65;$i<=90;$i++){
            $tagi = $r[chr($i)];
            if(is_array($tagi)){
                $output .= "<li id='".chr($i)."'><h4 class='tag-name'>".chr($i)."</h4><div class='tag-list'>";
                foreach($tagi as $tag){
                    $output .= "<a href='".get_tag_link($tag->term_id)."'>".$tag->name."<span class='number'>(".specs_post_count_by_tag($tag->term_id).")</span></a>";
                }
				$output .= '</div>';
            }
        }
        for($i=48;$i<=57;$i++){
            $tagi = $r[chr($i)];
            if(is_array($tagi)){
                $output .= "<li id='".chr($i)."'><h4 class='tag-name'>".chr($i)."</h4><div class='tag-list'>";
                foreach($tagi as $tag){
                    $output .= "<a href='".get_tag_link($tag->term_id)."'>".$tag->name."<span class='number'>(".specs_post_count_by_tag($tag->term_id).")</span></a>";
                }
				$output .= '</div>';
            }
        }
        $output .= "</ul>";
        update_option('specs_tags_list', $output);
    //}
    echo $output;
}

function clear_tags_cache() {
    update_option('specs_tags_list', '');
}
add_action('save_post', 'clear_tags_cache');

function specs_post_count_by_tag ( $arg ,$type = 'include'){
    $args=array(
        $type => $arg,
    );
    $tags = get_tags($args);
    if ($tags) {
        foreach ($tags as $tag) {
            return $tag->count;
        }
    }
}

function comment_mail_notify($comment_id) {
  $comment = get_comment($comment_id);
  $parent_id = $comment->comment_parent ? $comment->comment_parent : '';
  $spam_confirmed = $comment->comment_approved;
  if (($parent_id != '') && ($spam_confirmed != 'spam')) {
    $wp_email = 'no-reply@' . preg_replace('#^www\.#', '', strtolower($_SERVER['SERVER_NAME'])); //e-mail 发出点, no-reply 可改为可用的 e-mail.
    $to = trim(get_comment($parent_id)->comment_author_email);
    $subject = '您在 [' . get_option("blogname") . '] 的留言有了回复';
	$message = '
	<div style="background-color:#eef2fa; border:1px solid #d8e3e8; color:#111; padding:0 15px; -moz-border-radius:5px; -webkit-border-radius:5px; -khtml-border-radius:5px; border-radius:5px;">
	<p><strong>' . trim(get_comment($parent_id)->comment_author) . ', 你好!</strong></p>
	<p><strong>您曾在《' . get_the_title($comment->comment_post_ID) . '》的留言为:</strong><br />'
	. trim(get_comment($parent_id)->comment_content) . '</p>
	<p><strong>' . trim($comment->comment_author) . ' 给你的回复是:</strong><br />'
	. trim($comment->comment_content) . '<br /></p>
	<p>你可以点击此链接 <a href="' . htmlspecialchars(get_comment_link($parent_id)) . '">查看完整内容</a></p><br />
	<p>欢迎再次来访<a href="' . get_option('home') . '">' . get_option('blogname') . '</a></p>
	<p>(此邮件为系统自动发送，请勿直接回复.)</p>
	</div>';
    $from = "From: \"" . get_option('blogname') . "\" <$wp_email>";
    $headers = "$from\nContent-Type: text/html; charset=" . get_option('blog_charset') . "\n";
    wp_mail( $to, $subject, $message, $headers );
  }
}
add_action('comment_post','comment_mail_notify'); 

function dimox_breadcrumbs() {
  $delimiter = '&raquo;';
  $name = 'Home';
  $currentBefore = '<span>';
  $currentAfter = '</span>';
  if ( !is_home() && !is_front_page() || is_paged() ) {
    echo '<div id="crumbs">';
    global $post;
    $home = get_bloginfo('url');
    echo '' . $name . ' ' . $delimiter . ' ';
    if ( is_category() ) {
      global $wp_query;
      $cat_obj = $wp_query->get_queried_object();
      $thisCat = $cat_obj->term_id;
      $thisCat = get_category($thisCat);
      $parentCat = get_category($thisCat->parent);
      if ($thisCat->parent != 0) echo(get_category_parents($parentCat, TRUE, ' ' . $delimiter . ' '));
      echo $currentBefore . 'Archive by category &#39;';
      single_cat_title();
      echo '&#39;' . $currentAfter;
    } elseif ( is_day() ) {
      echo '' . get_the_time('Y') . ' ' . $delimiter . ' ';
      echo '' . get_the_time('F') . ' ' . $delimiter . ' ';
      echo $currentBefore . get_the_time('d') . $currentAfter;
    } elseif ( is_month() ) {
      echo '' . get_the_time('Y') . ' ' . $delimiter . ' ';
      echo $currentBefore . get_the_time('F') . $currentAfter;
    } elseif ( is_year() ) {
      echo $currentBefore . get_the_time('Y') . $currentAfter;
    } elseif ( is_single() ) {
      $cat = get_the_category(); $cat = $cat[0];
      echo get_category_parents($cat, TRUE, ' ' . $delimiter . ' ');
      echo $currentBefore;
      the_title();
      echo $currentAfter;
    } elseif ( is_page() && !$post->post_parent ) {
      echo $currentBefore;
      the_title();
      echo $currentAfter;
    } elseif ( is_page() && $post->post_parent ) {
      $parent_id  = $post->post_parent;
      $breadcrumbs = array();
      while ($parent_id) {
        $page = get_page($parent_id);
        $breadcrumbs[] = '' . get_the_title($page->ID) . '';
        $parent_id  = $page->post_parent;
      }
      $breadcrumbs = array_reverse($breadcrumbs);
      foreach ($breadcrumbs as $crumb) echo $crumb . ' ' . $delimiter . ' ';
      echo $currentBefore;
      the_title();
      echo $currentAfter;
    } elseif ( is_search() ) {
      echo $currentBefore . 'Search results for &#39;' . get_search_query() . '&#39;' . $currentAfter;
    } elseif ( is_tag() ) {
      echo $currentBefore . 'Posts tagged &#39;';
      single_tag_title();
      echo '&#39;' . $currentAfter;
    } elseif ( is_author() ) {
       global $author;
      $userdata = get_userdata($author);
      echo $currentBefore . 'Articles posted by ' . $userdata->display_name . $currentAfter;
    } elseif ( is_404() ) {
      echo $currentBefore . 'Error 404' . $currentAfter;
    }
    if ( get_query_var('paged') ) {
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ' (';
      echo __('Page') . ' ' . get_query_var('paged');
      if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() ) echo ')';
    }
    echo '</div>';
  }
}

function xmmusic($atts, $content=null, $code=""){
    return '<div class="sb-xiami" songid="'.$content.'"><div class="sb-player"><div class="sb-cover"></div><div class="sb-info clearfix"><div class="sb-title left"></div><div class="play-timer right">--:--</div></div><div class="play-button"> </div><div class="play-prosess"><div class="play-prosess-bar"></div></div></div><div class="sb-jplayer"></div></div>';
}
add_shortcode('xiami','xmmusic');

add_filter('the_content', 'addhighslideclass_replace');
function addhighslideclass_replace ($content) {
	global $post;
	$pattern = "/<a(.*?)href=('|\")([^>]*).(bmp|gif|jpeg|jpg|png|swf)('|\")(.*?)>(.*?)<\/a>/i";
	$replacement = '<a$1href=$2$3.$4$5 class="slimbox2" $6>$7</a>';
	$content = preg_replace($pattern, $replacement, $content);
	return $content;
}


add_filter('pre_get_posts','search_filter');
function search_filter($query) {
	if ($query->is_search) {
		$query->set('post_type', 'post');
	}
	return $query;
}

/*
add_filter( 'author_link', 'my_author_link' );
 
function my_author_link() {
    return home_url( 'about' );
}*/

function is_mobile() {
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$mobile_browser = Array(
		"mqqbrowser", //手机QQ浏览器
		"opera mobi", //手机opera
		"juc","iuc",//uc浏览器
		"fennec","ios","applewebKit/420","applewebkit/525","applewebkit/532","ipad","iphone","ipaq","ipod",
		"iemobile", "windows ce",//windows phone
		"240x320","480x640","acer","android","anywhereyougo.com","asus","audio","blackberry","blazer","coolpad" ,"dopod", "etouch", "hitachi","htc","huawei", "jbrowser", "lenovo","lg","lg-","lge-","lge", "mobi","moto","nokia","phone","samsung","sony","symbian","tablet","tianyu","wap","xda","xde","zte"
	);
	$is_mobile = false;
	foreach ($mobile_browser as $device) {
		if (stristr($user_agent, $device)) {
			$is_mobile = true;
			break;
		}
	}
	return $is_mobile;
}

add_filter ('the_content', 'lazyload');
function lazyload($content) {
	$loadimg_url=get_bloginfo('template_directory').'/loading.gif';
	if(!is_feed()||!is_robots) {
		$content=preg_replace('/<img(.+)src=[\'"]([^\'"]+)[\'"](.*)>/i',"<img data-unveil='true' \$1data-src=\"\$2\" src=\"$loadimg_url\"\$3>\n<noscript>\$0</noscript>",$content);
	}
	return $content;
}



function my_avatar($avatar) {
  $tmp = strpos($avatar, 'http');
  $g = substr($avatar, $tmp, strpos($avatar, "'", $tmp) - $tmp);
  $tmp = strpos($g, 'avatar/') + 7;
  $f = substr($g, $tmp, strpos($g, "?", $tmp) - $tmp);
  $w = get_bloginfo('wpurl');
  $e = ABSPATH .'avatar/'. $f .'.jpg';
  $t = 1209600; 
  if ( !is_file($e) || (time() - filemtime($e)) > $t ) { 
    copy(htmlspecialchars_decode($g), $e);
  } else  $avatar = strtr($avatar, array($g => $w.'/avatar/'.$f.'.jpg'));
  if (filesize($e) < 500) copy($w.'/avatar/default.jpg', $e);
  return $avatar;
}
add_filter('get_avatar', 'my_avatar');

/*shortcodes*/
function boxattention($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxattention">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('attention' , 'boxattention' );

function boxbag($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxbag">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('bag' , 'boxbag' );

function boxbonus($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxbonus">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('bonus' , 'boxbonus' );

function boxcalendar($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxcalendar">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('calendar' , 'boxcalendar' );

function boxcheck($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxcheck">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('check' , 'boxcheck' );

function boxdelete($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxdelete">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('delete' , 'boxdelete' );

function boxedit($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxedit">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('edit' , 'boxedit' );

function boxflag($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxflag">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('flag' , 'boxflag' );

function boxhelp($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxhelp">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('help' , 'boxhelp' );

function boxinformation($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxinformation">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('information' , 'boxinformation' );

function boxlove($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxlove">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('love' , 'boxlove' );

function boxtag($atts, $content=null, $code="") {
	$return = '<div class="shortbox boxtag">';
	$return .= $content;
	$return .= '</div>';
	return $return;
}
add_shortcode('tag' , 'boxtag' );



?>