<?php
$footer_class = '';
global $themestek_mainchimp_formrow;
if( !empty($themestek_mainchimp_formrow) && $themestek_mainchimp_formrow == 'yes' ){
	$footer_class .= 'themestek-mailchimp-exists';
}

$show_footer_instagram = themestek_get_option('show_footer_instagram');
if( $show_footer_instagram=='1' ){
	$footer_class .= ' themestek-instagram-exists';
}

?>

				</div><!-- .site-content-inner -->
			</div><!-- .site-content -->
		</div><!-- .site-content-wrapper -->

		<footer id="colophon" class="site-footer <?php echo themestek_sanitize_html_classes($footer_class); ?>">
			<?php 
			if( $show_footer_instagram == true && shortcode_exists('instagram-feed') ){?>
				<div class="themestek-footer-instagram-wrapper"><?php echo do_shortcode('[instagram-feed]'); ?></div>
			<?php } ?>
			<div class="footer_inner_wrapper footer<?php echo themestek_sanitize_html_classes(themestek_footer_row_class( 'full' )); ?>">
				<div class="site-footer-bg-layer themestek-bg-layer"></div>
				<div class="site-footer-w">
					<div class="footer-rows">
						<div class="footer-rows-inner">

							<?php themestek_footer_social_links(); ?>
							<?php get_sidebar( 'footer-widgets' ); ?>
						</div><!-- .footer-inner -->
					</div><!-- .footer -->
					<?php get_sidebar( 'footer' ); ?>
				</div><!-- .footer-inner-wrapper -->
			</div><!-- .site-footer-inner -->
		</footer><!-- .site-footer -->

	</div><!-- #page .site -->

</div><!-- .main-holder -->
<?php if ( !is_front_page() ) {	?>
<style> 
.animatedfs_menu_list li>a:before, .animatedfsmenu .animatedfsmenu-navbar-toggler .bar {
    background: #000 !important;
}
</style>
<?php } ?>
<?php 
// Hide Totop Button
$hide_totop_button  = themestek_get_option('hide_totop_button');
if($hide_totop_button != 1 ){ ?>
	<!-- To Top -->
	<a id="totop" href="#top"><i class="themestek-vihan-icon-angle-up"></i></a>
<?php } ?>
<?php wp_footer(); ?>
</body>
</html>
