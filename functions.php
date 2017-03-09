<?php
function my_enqueue_scripts_frontpage() {
    $theme_ver = "1.2.3.21";
    $theme_dir = get_template_directory_uri();

    //载入css
    wp_enqueue_style( 'FA', $theme_dir.'/css/font-awesome.min.css', false, $theme_ver);
    wp_enqueue_style( 'Germ-style', $theme_dir.'/style.min.css', array('FA'), $theme_ver);

    //载入JS
    wp_enqueue_script( 'FlexSlider', $theme_dir.'/js/jquery.flexslider-min.js', array('jquery'), '2.6.3', true);
    wp_enqueue_script( 'base', $theme_dir.'/js/global.min.js', array('jquery', 'FlexSlider'), $theme_ver, true);

    if( dopt('d_slimbox_b') != '' )
        wp_enqueue_script( 'slimbox', $theme_dir.'/js/slimbox2.min.js', array('jquery'), $theme_ver, true);
    if( dopt('d_ajax_b') != '' )
        wp_enqueue_script( 'ajax', $theme_dir.'/js/ajax.min.js', array('jquery'), $theme_ver, true);
    if( dopt('d_autospace_b') != '' )
        wp_enqueue_script( 'autospace', $theme_dir.'/js/autospace.min.js', array('jquery'), $theme_ver, true);
}
add_action( 'wp_enqueue_scripts', 'my_enqueue_scripts_frontpage' );


//remove jquery_migrate
function cedaro_dequeue_jquery_migrate( $scripts ) {
	if ( ! is_admin() && ! empty( $scripts->registered['jquery'] ) ) {
		$jquery_dependencies = $scripts->registered['jquery']->deps;
		$scripts->registered['jquery']->deps = array_diff( $jquery_dependencies, array( 'jquery-migrate' ) );
	}
}
add_action( 'wp_default_scripts', 'cedaro_dequeue_jquery_migrate' );


//后端设置写入前端js变量
add_action( 'wp_enqueue_scripts', 'echoJSvar' );
function echoJSvar() {
    $data = array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'home' => home_url()
    );
    //侧边栏飞行设置
    if( is_single() && dopt('d_sideroll_single_b') ){
        $sr_1 = dopt('d_sideroll_single_1');
        $sr_2 = dopt('d_sideroll_single_2');
    }elseif( is_home() && dopt('d_sideroll_index_b') ){
        $sr_1 = dopt('d_sideroll_index_1');
        $sr_2 = dopt('d_sideroll_index_2');
    }elseif( dopt('d_sideroll_page_b') ){
        $sr_1 = dopt('d_sideroll_page_1');
        $sr_2 = dopt('d_sideroll_page_2');
    }else{
        $sr_1 = -24;
        $sr_2 = -38;
    }
    $data['fly1'] = $sr_1;
    $data['fly2'] = $sr_2;

    wp_localize_script('jquery', 'ajax', $data);
}


//让WP自动添加页面title
add_theme_support( 'title-tag' );


//禁用谷歌字体
function remove_open_sans() {
    wp_deregister_style( 'open-sans' );
    wp_register_style( 'open-sans', false );
    wp_enqueue_style('open-sans','');
}
add_action( 'init', 'remove_open_sans' );


//编辑器添加按钮
function enable_more_buttons($buttons) {
    $buttons[] = 'sub';
    $buttons[] = 'sup';
    $buttons[] = 'fontselect';
    $buttons[] = 'fontsizeselect';
    $buttons[] = 'cleanup';
    $buttons[] = 'styleselect';
    $buttons[] = 'wp_page';
    $buttons[] = 'anchor';
    $buttons[] = 'backcolor';
    return $buttons;
}
add_filter("mce_buttons_3", "enable_more_buttons");

function reset_emojis() {
    remove_action('wp_head', 'print_emoji_detection_script', 7);
    remove_action('admin_print_scripts', 'print_emoji_detection_script');
    remove_action('wp_print_styles', 'print_emoji_styles');
    remove_action('admin_print_styles', 'print_emoji_styles');
    add_filter('the_content', 'wp_staticize_emoji');
    add_filter('comment_text', 'wp_staticize_emoji',50);
}
add_action('init', 'reset_emojis');

function fixed_activity_widget_avatar_style(){
echo '<style type="text/css">
        #activity-widget #the-comment-list .avatar {
        position: absolute;
        top: 13px;
        width: 50px;
        height: 50px;
      }
      </style>';
}
add_action('admin_head', 'fixed_activity_widget_avatar_style' );

include_once('inc/widget.php');
include_once('inc/themeset.php');

add_action('after_setup_theme', 'my_theme_setup');
function my_theme_setup(){
    load_theme_textdomain('quench', get_template_directory() . '/languages');
}


//head信息精简
remove_action('wp_head', 'feed_links_extra', 3 ); //去除评论feed
remove_action('wp_head', 'feed_links', 2 ); //去除文章feed
remove_action('wp_head','wp_generator');//禁止在head泄露wordpress版本号
remove_action('wp_head','rsd_link');//移除head中的rel="EditURI"
remove_action('wp_head','wlwmanifest_link');//移除head中的rel="wlwmanifest"
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );//rel=pre
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0 );//rel=shortlink


//禁用REST API
/*
add_filter('rest_enabled', '_return_false');
add_filter('rest_jsonp_enabled', '_return_false');
remove_action( 'wp_head', 'rest_output_link_wp_head', 10 );
remove_action( 'wp_head', 'wp_oembed_add_discovery_links', 10 );
*/

//禁用embeds功能
function disable_embeds_init() {
    global $wp;
    $wp->public_query_vars = array_diff( $wp->public_query_vars, array(
        'embed',
    ) );
    remove_action( 'rest_api_init', 'wp_oembed_register_route' );
    add_filter( 'embed_oembed_discover', '__return_false' );
    remove_filter( 'oembed_dataparse', 'wp_filter_oembed_result', 10 );
    remove_action( 'wp_head', 'wp_oembed_add_discovery_links' );
    remove_action( 'wp_head', 'wp_oembed_add_host_js' );
    add_filter( 'tiny_mce_plugins', 'disable_embeds_tiny_mce_plugin' );
    add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
}
add_action( 'init', 'disable_embeds_init', 9999 );
function disable_embeds_tiny_mce_plugin( $plugins ) {
    return array_diff( $plugins, array( 'wpembed' ) );
}
function disable_embeds_rewrites( $rules ) {
    foreach ( $rules as $rule => $rewrite ) {
        if ( false !== strpos( $rewrite, 'embed=true' ) ) {
            unset( $rules[ $rule ] );
        }
    }
    return $rules;
}
function disable_embeds_remove_rewrite_rules() {
    add_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
    flush_rewrite_rules();
}
register_activation_hook( __FILE__, 'disable_embeds_remove_rewrite_rules' );
function disable_embeds_flush_rewrite_rules() {
    remove_filter( 'rewrite_rules_array', 'disable_embeds_rewrites' );
    flush_rewrite_rules();
}
register_deactivation_hook( __FILE__, 'disable_embeds_flush_rewrite_rules' );
//禁用embeds功能 END


// 结果只有一篇文章时自动跳转到文章
add_action('template_redirect', 'redirect_single_post');
function redirect_single_post() {
    if (is_search()) {
        global $wp_query;
        if ($wp_query->post_count == 1 && $wp_query->max_num_pages == 1) {
            wp_redirect( get_permalink( $wp_query->posts['0']->ID ) );
            exit;
}	}   }


register_nav_menus(array('header-menu' => '顶部导航'));

add_theme_support( 'post-formats', array( 'status', 'gallery' ));

function dopt($e){
    return stripslashes(get_option($e));
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
   <li <?php comment_class(); ?><?php //if( $depth > 2){ echo ' style="left:0;"';} ?> id="li-comment-<?php comment_ID() ?>">

<article id="comment-<?php comment_ID(); ?>" class="comment-body">
    <div class="comment-author vcard">
        <?php
            if(dopt('d_defaultavatar_b'))
                echo get_avatar( $comment, '40');
            else
                echo get_random_avatar( $comment, '40');
        ?>
    </div>
    <div class="comment-content">
        <div class="reply">
            <?php comment_reply_link(array_merge( $args, array('reply_text' => '回复','depth' => $depth, 'max_depth' => $args['max_depth']))); ?>
        </div>
        <div class="comment-metadata">
            <b class="fn"><?php printf(__('%s'), get_comment_author_link()) ?></b>
            <?php if(dopt('d_showreplayT_b') != '') : ?>
                <time datetime="<?php echo time_ago(); ?>"><?php echo time_ago(); ?></time>
            <?php endif; ?>
        </div>
        <div class="comment_text">
            <?php comment_text() ?>
        </div>
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
        $keywords = "";
    $blog_name = get_bloginfo('name');
    if ( is_singular() ) {
        if ($post->post_excerpt)
          $description = $post->post_excerpt;
            else
                $description = mb_substr(strip_tags($post->post_content),0,210,'utf-8') .'......';
            $tags = wp_get_post_tags($post->ID);
        foreach ($tags as $tag )
            $keywords .= $tag->name . ",";
    } elseif ( is_home () )    {
            $description = dopt('d_description');
            $keywords = dopt('d_keywords');
    } elseif ( is_tag() )      {
            $keywords = single_tag_title('', false);
            $description = "标签: [$keywords] | $blog_name";
    } elseif ( is_category() ) {
            $keywords = single_cat_title('', false);
            $description = "分类: [$keywords] | $blog_name";
    } elseif ( is_archive() )  {
        if ($post->post_excerpt)
          $description = $post->post_excerpt;
            else
                $description = mb_substr(strip_tags($post->post_content),0,210,'utf-8') .'......';
    } elseif ( is_search() )   {
            $keywords = esc_html( $s, 1 );
            $description = $blog_name . ": [$keywords] 的搜索結果";
    }
    $description = mb_substr( $description, 0, 220, 'utf-8' );
        if($description!="")
    	echo "<meta name='description' content='$description'>\n";
        if($keywords!="")
            echo "<meta name='keywords' content='$keywords'>\n";
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

/*
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
add_action('wp_head', 'record_visitors'); */

//打印访问量
function mzw_post_views($after=''){
  global $post;
  $views = (int)get_post_meta($post->ID, 'views', true);
  echo $views, $after;
}

// 自动添加views属性
add_action( 'save_post', 'views_add_postdata' );
function views_add_postdata( $post_id ){
    //add_post_meta($post_id, 'views', $post_id % 17 , true);
    add_post_meta($post_id, 'views', 1 , true);
}

//响应ajax增加访问量
function ajax_add_views() {
    $post_ID = intval( $_POST['post_ID'] );
    if($post_ID) {
        $post_views = (int)get_post_meta($post_ID, 'views', true);
        $add_views = (dopt('d_moreviews_b') != '')? 3:1;
        if( !update_post_meta($post_ID, 'views', $post_views+$add_views) ) {
            add_post_meta($post_ID, 'views', 1, true);
        }
    }
    wp_die();
}
add_action('wp_ajax_add_views', 'ajax_add_views');
add_action('wp_ajax_nopriv_add_views', 'ajax_add_views');


add_action('wp_ajax_nopriv_mzw_like', 'mzw_like');
add_action('wp_ajax_mzw_like', 'mzw_like');
function mzw_like() {
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

/*
    return random <img> head by author name
*/
function get_random_avatar($comment, $size=40) {
    $comment_writer = $comment->comment_author;
    if (strtoupper($comment_writer) == "MOSHEL" || $comment_writer == "Mσѕнєℓ") {
        //custom for hzy.pw
        $rnd_src = dopt('d_myavatar');
    }
    else if( $comment->comment_author_email && $comment->comment_author_email == get_the_author_email() ) {
        //writer's reply
        if(dopt('d_myavatar') != '')
            $rnd_src = dopt('d_myavatar');
        else
            $rnd_src = "https://q.qlogo.cn/qqapp/100229475/F1260A6CECA521F6BE517A08C4294D8A/100";
    }
    else if ($comment->comment_type == "pingback")
        $rnd_src = get_template_directory_uri().'/images/robot.jpg';
    else { //normal comments use svg
        $rnd_src = "https://api.hzy.pw/avatar/v1/$size/$comment_writer";
        return "<embed src='$rnd_src' class='avatar avatar-$size photo' width='$size' height='$size' type='image/svg+xml'/>";
    }

    return "<img src='$rnd_src' class='avatar avatar-$size photo' height='$size' width='$size'>";
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
        ajax_comment_err('评论的状态无效');
    }
    $status = get_post_status($post);
    $status_obj = get_post_status_object($status);
    if ( !comments_open($comment_post_ID) ) {
        do_action('comment_closed', $comment_post_ID);
        ajax_comment_err('抱歉, 此文章已不允许新增评论');
    } elseif ( 'trash' == $status ) {
        do_action('comment_on_trash', $comment_post_ID);
        ajax_comment_err('评论的状态无效');
    } elseif ( !$status_obj->public && !$status_obj->private ) {
        do_action('comment_on_draft', $comment_post_ID);
        ajax_comment_err('评论的状态无效');
    } elseif ( post_password_required($comment_post_ID) ) {
        do_action('comment_on_password_protected', $comment_post_ID);
        ajax_comment_err('密码保护中');
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
            ajax_comment_err('抱歉, 在评论前必须登录');
    }
    $comment_type = '';
    if ( get_option('require_name_email') && !$user->exists() ) {
        if ( 6 > strlen($comment_author_email) || '' == $comment_author )
            ajax_comment_err( '失败, 发表留言不能没有署名~' );
        elseif ( !is_email($comment_author_email))
            ajax_comment_err( '错误: 请输入有效的电子邮箱地址~' );
    }
    if ( '' == $comment_content )
        ajax_comment_err( '失败, 还没有开始写任何评论呢~' );
    $dupe = "SELECT comment_ID FROM $wpdb->comments WHERE comment_post_ID = '$comment_post_ID' AND ( comment_author = '$comment_author' ";
    if ( $comment_author_email ) $dupe .= "OR comment_author_email = '$comment_author_email' ";
    $dupe .= ") AND comment_content = '$comment_content' LIMIT 1";
    if ( $wpdb->get_var($dupe) ) {
        ajax_comment_err('检测到重复的评论, 似乎你已经这样评论过了');
    }
    if ( $lasttime = $wpdb->get_var( $wpdb->prepare("SELECT comment_date_gmt FROM $wpdb->comments WHERE comment_author = %s ORDER BY comment_date DESC LIMIT 1", $comment_author) ) ) {
        $time_lastcomment = mysql2date('U', $lasttime, false);
        $time_newcomment  = mysql2date('U', current_time('mysql', 1), false);
        $flood_die = apply_filters('comment_flood_filter', false, $time_lastcomment, $time_newcomment);
        if ( $flood_die ) {
            ajax_comment_err('你发表评论太快了, 慢点儿吧~');
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
                <?php
                    if(dopt('d_defaultavatar_b'))
                        echo get_avatar( $comment, '40');
                    else
                        echo get_random_avatar( $comment, '40');
                ?>
            </div>
            <div class="comment-metadata">
                <b class="fn"><?php printf(__('%s'), get_comment_author_link()) ?></b>
                <time datetime="<?php echo time_ago(); ?>"><?php echo time_ago(); ?></time>
            </div>
        </div>
        <?php if ( '0' == $comment->comment_approved ) : ?>
            <p class="comment-awaiting-moderation">您的评论已提交, 正在排队等待审核.</p>
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
    //wp_list_comments('style=ol&callback=comment&page=' . $pageid . '&per_page=' . get_option('comments_per_page'), $comments);
    my_wp_list_comments('style=ol&callback=comment&page=' . $pageid . '&per_page=' . get_option('comments_per_page'), $comments);
    echo '</ol>';
    echo '<nav class="commentnav" data-postid="'.$postid.'">';
    paginate_comments_links('total=' . get_comment_pages_count($comments). '&current=' . $pageid . '&prev_text=«&next_text=»');
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
    if($asc >= -17922 and $asc <= -17418) return "H";
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

function get_ssl_avatar($avatar) {
    $avatar = str_replace(array("www.gravatar.com", "0.gravatar.com", "1.gravatar.com", "2.gravatar.com"), "o0skf43s7.qnssl.com", $avatar);
    return $avatar;
}
add_filter('get_avatar', 'get_ssl_avatar');

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


function my_wp_list_comments( $args = array(), $comments = null) {
    global $wp_query, $comment_alt, $comment_depth, $comment_thread_alt, $overridden_cpage, $in_comment_loop;

    $in_comment_loop = true;

    $comment_alt = $comment_thread_alt = 0;
    $comment_depth = 1;

    $defaults = array(
        'walker'            => null,
        'max_depth'         => '',
        'style'             => 'ul',
        'callback'          => null,
        'end-callback'      => null,
        'type'              => 'all',
        'page'              => '',
        'per_page'          => '',
        'avatar_size'       => 32,
        'reverse_top_level' => null,
        'reverse_children'  => '',
        'format'            => current_theme_supports( 'html5', 'comment-list' ) ? 'html5' : 'xhtml',
        'short_ping'        => false,
        'echo'              => true,
    );

    $r = wp_parse_args( $args, $defaults );

    /**
     * Filter the arguments used in retrieving the comment list.
     *
     * @since 4.0.0
     *
     * @see wp_list_comments()
     *
     * @param array $r An array of arguments for displaying comments.
     */
    $r = apply_filters( 'wp_list_comments_args', $r );

    /*
     * If 'page' or 'per_page' has been passed, and does not match what's in $wp_query,
     * perform a separate comment query and allow Walker_Comment to paginate.
     */
    //var_dump($r);
    /*if ( is_singular() && ( $r['page'] || $r['per_page'] ) ) {
        $current_cpage = get_query_var( 'cpage' );
        if ( ! $current_cpage ) {
            $current_cpage = 'newest' === get_option( 'default_comments_page' ) ? 1 : $wp_query->max_num_comment_pages;
        }

        $current_per_page = get_query_var( 'comments_per_page' );
        if ( $r['page'] != $current_cpage || $r['per_page'] != $current_per_page ) {

            $comments = get_comments( array(
                'post_id' => $id,
                'orderby' => 'comment_date_gmt',
                'order' => 'ASC',
                'status' => 'all',
            ) );
        }
    }*/

    // Figure out what comments we'll be looping through ($_comments)
    if ( null !== $comments ) {
        $comments = (array) $comments;
        if ( empty($comments) )
            return;
        if ( 'all' != $r['type'] ) {
            $comments_by_type = separate_comments($comments);
            if ( empty($comments_by_type[$r['type']]) )
                return;
            $_comments = $comments_by_type[$r['type']];
        } else {
            $_comments = $comments;
        }
    } else {
        if ( empty($wp_query->comments) )
            return;
        if ( 'all' != $r['type'] ) {
            if ( empty($wp_query->comments_by_type) )
                $wp_query->comments_by_type = separate_comments($wp_query->comments);
            if ( empty($wp_query->comments_by_type[$r['type']]) )
                return;
            $_comments = $wp_query->comments_by_type[$r['type']];
        } else {
            $_comments = $wp_query->comments;
        }

        // Pagination is already handled by `WP_Comment_Query`, so we tell Walker not to bother.
        if ( $wp_query->max_num_comment_pages ) {
            $default_comments_page = get_option( 'default_comments_page' );
            $cpage = get_query_var( 'cpage' );
            if ( 'newest' === $default_comments_page ) {
                $r['cpage'] = $cpage;

            // When first page shows oldest comments, post permalink is the same as the comment permalink.
            } elseif ( $cpage == 1 ) {
                $r['cpage'] = '';
            } else {
                $r['cpage'] = $cpage;
            }

            $r['page'] = 0;
            $r['per_page'] = 0;
        }
    }

    if ( '' === $r['per_page'] && get_option( 'page_comments' ) ) {
        $r['per_page'] = get_query_var('comments_per_page');
    }

    if ( empty($r['per_page']) ) {
        $r['per_page'] = 0;
        $r['page'] = 0;
    }

    if ( '' === $r['max_depth'] ) {
        if ( get_option('thread_comments') )
            $r['max_depth'] = get_option('thread_comments_depth');
        else
            $r['max_depth'] = -1;
    }

    if ( '' === $r['page'] ) {
        if ( empty($overridden_cpage) ) {
            $r['page'] = get_query_var('cpage');
        } else {
            $threaded = ( -1 != $r['max_depth'] );
            $r['page'] = ( 'newest' == get_option('default_comments_page') ) ? get_comment_pages_count($_comments, $r['per_page'], $threaded) : 1;
            set_query_var( 'cpage', $r['page'] );
        }
    }
    // Validation check
    $r['page'] = intval($r['page']);
    if ( 0 == $r['page'] && 0 != $r['per_page'] )
        $r['page'] = 1;

    if ( null === $r['reverse_top_level'] )
        $r['reverse_top_level'] = ( 'desc' == get_option('comment_order') );

    if ( empty( $r['walker'] ) ) {
        $walker = new Walker_Comment;
    } else {
        $walker = $r['walker'];
    }

    $output = $walker->paged_walk( $_comments, $r['max_depth'], $r['page'], $r['per_page'], $r );

    $in_comment_loop = false;

    if ( $r['echo'] ) {
        echo $output;
    } else {
        return $output;
    }
}

?>
