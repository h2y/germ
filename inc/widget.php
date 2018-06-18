<?php
// 删除wp自带的小工具
function unregister_default_wp_widgets() {
    unregister_widget('WP_Widget_Pages');
    unregister_widget('WP_Widget_Recent_Posts');
    unregister_widget('WP_Widget_Archives');
    unregister_widget('WP_Widget_Search');
    //unregister_widget('WP_Widget_Calendar');
    //unregister_widget('WP_Widget_Meta');
    //unregister_widget('WP_Widget_Text');
    //unregister_widget('WP_Widget_Categories');
    //unregister_widget('WP_Widget_Recent_Comments');
    //unregister_widget('WP_Widget_RSS');
    //unregister_widget('WP_Widget_Tag_Cloud');
    //unregister_widget('WP_Nav_Menu_Widget');
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
            'name'=>'其他位置边栏',
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
    function __construct() {
        parent::__construct('mzw_siderbar_post', 'Germ 文章列表', array( 'description' => '多功能文章列表，可按时间、评论、随机排序') );
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
                <input class="widefat" id="<?php echo $this->get_field_id('cat'); ?>" name="<?php echo $this->get_field_name('cat'); ?>" type="text" value="<?php echo esc_attr($cat); ?>" size="24" />
            </label>
        </p>
        <p>
            <label>
                显示数目：
                <input class="widefat" id="<?php echo $this->get_field_id('limit'); ?>" name="<?php echo $this->get_field_name('limit'); ?>" type="number" value="<?php echo esc_attr($limit); ?>" size="24" />
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
        echo '<div class="post-thumb"><a href="'.get_permalink().'">';
        echo post_thumbnail(45, 45, false);
        echo '</a></div>';
        echo '<div class="post-right">';
        echo '<h3><a href="'.get_permalink().'">';
        the_title();
        echo '</a></h3><div class="post-meta"><span>';
        comments_popup_link('暂无评论', '1 条评论', '% 条评论');
        echo '</span> | <span>';
        mzw_post_views(' 访问量');
        echo '</span></div></div>';
        echo '</li>';
    endwhile;
    wp_reset_query();
    echo '</ul></div>';
}



add_action('widgets_init', create_function('', 'return register_widget("mzw_siderbar_tags");'));

class mzw_siderbar_tags extends WP_Widget {
    function __construct() {
	      parent::__construct('mzw_siderbar_tags', 'Germ 标签云', array( 'description' => '适配主题的标签云' ));
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
                <input class="widefat" id="<?php echo $this->get_field_id('tag_limit'); ?>" name="<?php echo $this->get_field_name('tag_limit'); ?>" type="number" value="<?php echo esc_attr($tag_limit); ?>" size="24" />
            </label>
        </p>
        <p>会优先显示文章数量最多的标签.</p>

<?php
    }
}



add_action( 'widgets_init', create_function('', 'return register_widget("mzw_search");'));

class mzw_search extends WP_Widget {
    function __construct() {
        $widget_ops = array( 'classname' => 'mzw_search', 'description' => '站内搜索' );
	      parent::__construct( 'mzw_search', 'Germ 站内搜索', $widget_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );
        echo $before_widget;
        ?>
        <form id="searchform" class="searchform" action="<?php echo get_bloginfo ('url'); ?>" method="GET">
            <div>
                <input name="s" id="s" size="15" placeholder="输入关键字" type="text">
                <input value="站内搜索" type="submit">
            </div>
        </form>
<?php
        echo $after_widget;
    }
    function form($instance) {
?>
        <p>
            搜索小工具已启用, 无需额外设置.
        </p>
<?php
    }
}

add_action( 'widgets_init', create_function('', 'return register_widget("mzw_admin");'));

class mzw_admin extends WP_Widget {
    function __construct() {
        $widget_ops = array( 'classname' => 'mzw_admin', 'description' => '显示作者的信息机个人简介' );
	      parent::__construct( 'mzw_admin', 'Germ 作者信息', $widget_ops );
    }

    function widget( $args, $instance ) {
        extract( $args );
        echo $before_widget;
        ?>
        <img src="<?= esc_url( get_template_directory_uri() ) ?>/images/bg_small.jpg">
        <div class="author-body">
            <div class="author_img">
            <?php
                if(dopt('d_defaultavatar_b'))
                    echo get_avatar( get_the_author_meta('email'), $size = '80' , '' );
                else {
                    $head_src = dopt('d_myavatar') ? dopt('d_myavatar') : "http://q.qlogo.cn/qqapp/100229475/F1260A6CECA521F6BE517A08C4294D8A/100";
                    echo '<img src="'.$head_src.'" class="avatar avatar-80 photo" height="80" width="80">';
                }
            ?>
            </div>
            <div class="author_bio">
                <h3><?php the_author_meta('nickname');?> </h3>
                <p class="muted"><?php the_author_meta('user_description');?> </p>
            </div>
        </div>
        <?php if( dopt('d_sns_open') ) {
            echo '<div class="social">';
            if( dopt('d_rss_b') ) echo '<a target="_blank" class="rss" href="'.dopt('d_rss').'"><i class="fa fa-rss"></i></a>';
            if( dopt('d_mail_b') ) echo '<a rel="nofollow" target="_blank" class="mail" href="'.dopt('d_mail').'"><i class="fa fa-envelope"></i></a>';
            if( dopt('d_rss_sina_b') ) echo '<a rel="nofollow" target="_blank" class="weibo" href="'.dopt('d_rss_sina').'"><i class="fa fa-weibo"></i></a>';
            if( dopt('d_rss_twitter_b') ) echo '<a rel="nofollow" target="_blank" class="twitter" href="'.dopt('d_rss_twitter').'"><i class="fa fa-twitter"></i></a>';
            if( dopt('d_rss_google_b') ) echo '<a rel="nofollow" target="_blank" class="google" href="'.dopt('d_rss_google').'"><i class="fa fa-google-plus "></i></a>';
            if( dopt('d_rss_facebook_b') ) echo '<a rel="nofollow" target="_blank" class="facebook" href="'.dopt('d_rss_facebook').'"><i class="fa fa-facebook"></i></a>';
            if( dopt('d_rss_github_b') ) echo '<a rel="nofollow" target="_blank" class="github" href="'.dopt('d_rss_github').'"><i class="fa fa-github"></i></a>';
            if( dopt('d_rss_tencent_b') ) echo '<a rel="nofollow" target="_blank" class="tweibo" href="'.dopt('d_rss_tencent').'"><i class="fa fa-tencent-weibo"></i></a>';
            if( dopt('d_rss_linkedin_b') ) echo '<a rel="nofollow" target="_blank" class="linkedin" href="'.dopt('d_rss_linkedin').'"><i class="fa fa-linkedin"></i></a>';
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
