<!DOCTYPE html>
<html <?php if( dopt('d_autospace_b') != '' ) echo 'class="han-la"';?>>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="<?php bloginfo('template_directory');?>/style.min.css" rel="stylesheet" media="screen">
    <link href="<?php bloginfo('template_directory');?>/css/font-awesome.min.css" rel="stylesheet" media="screen">
    <?php wp_head(); ?>
		<?php if( dopt('d_headcode_b') != '' ) echo dopt('d_headcode');?>
</head>

<body class="nav-open">
<?php
	if( is_mobile() ) {
	echo '<div id="mobile-nav" class="f12 yahei"><form class="mm-search" action="'.get_bloginfo('url').'" method="get" role="search"><input type="text" autocomplete="off" placeholder="Search" name="s" value=""><input id="mobilesubmit" type="submit" value="Search"></form> ';
	if(function_exists('wp_nav_menu')) {
						wp_nav_menu(array( 'theme_location' => 'header-menu','container' => 'ul', 'menu_class' => 'nav'));
					}
	echo '</div>';
	}
?>

	<div id="wrap">
		<div id="bg-over"></div>
        <div id="preheader"></div>
        <header id="header" class="clearfix">
				<div class="head">
					<h1><a href="<?php bloginfo('url'); ?>"><?php bloginfo('name')?></a></h1>
					<p class="desc yahei"><span><?php bloginfo('description')?></span></p>
				</div>

			<?php
				if( !is_mobile() ) {
					echo '<nav id="main-nav" class="clearfix yahei">';
					if(function_exists('wp_nav_menu')) {
						wp_nav_menu(array( 'theme_location' => 'header-menu','container' => 'ul', 'menu_class' => 'nav'));
					}
					echo '</nav>';
				} else {
				  echo '<a href="javascript:;" class="open-nav icon-list"><i class="fa fa-bars"></i></a>';
				}
			?>

			<div id="announcement">
				<a href="#" class="openpre close" title="Announcement"><i class="fa fa-star"></i></a>
			</div>
		</header>

        <div id="container" class="clearfix">
            <div id="content">
				<div <?php body_class();?>>
