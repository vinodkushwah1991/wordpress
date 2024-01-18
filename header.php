<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
	<meta charset="UTF-8">
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
	<?php endif; ?>
	<?php wp_head(); ?>
	<!--<script type="text/javascript" src="/AMU/wp-content/themes/vihan-child/assets/sidemenu/jquery.airmenu.js"></script>
	<link rel="stylesheet" type="text/css" href="/AMU/wp-content/themes/vihan-child/assets/sidemenu/airmenu.css">
	<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery(".airmenu").airMenu();
			});
		</script>-->
		<script type="text/javascript">
		
	jQuery(document).ready(function(){

	if(window.location.href != 'http://172.201.248.65')
		{
			var element = jQuery('.animatedfsmenu-navbar-toggler');
			element.click(function() {
				// jQuery(this).addClass('open');
			if (element.hasClass("open")) {
			 window.location.href = '<?php echo home_url();?>';
		}
			jQuery(this).addClass('open');
			}); 
		}


	 jQuery(".site_search_btn_click").click(function() {
    jQuery("#site_search").slideToggle("slow", function() {     
    });
  });
	

	});

	</script>
</head>
<body <?php body_class(); ?>>
<div class="sidemenu_social">
	<?php wp_body_open(); ?>
	<?php  if (is_front_page()) {
    // This is the homepage; you can add other content or condition here if needed
} else {
    echo do_shortcode('[themestek-social-links tooltip="no"]');
} ?>
</div>
<?php
// We are not escaping / sanitizing as we are expecting any (CSS/JS/html) code. 
themestek_body_start_code();

// correction for The Events Calendar
themestek_events_calendar_correction();
?>

<div id="themestek-home"></div>
<div class="main-holder">

	<div id="page" class="hfeed site">
	
			
		<?php get_template_part( 'theme-parts/header/headerstyle', esc_html(themestek_get_headerstyle()) ); ?>

		<div id="content-wrapper" class="site-content-wrapper">
			<?php if( is_404() ): ?>
			<div class="themestek-bg-layer"></div>
			<?php endif; ?>
			<div id="content" class="site-content <?php echo themestek_sanitize_html_classes(themestek_sidebar_class('container')); ?>">
				<div id="content-inner" class="site-content-inner <?php echo themestek_sanitize_html_classes(themestek_sidebar_class('row')); ?>">
			