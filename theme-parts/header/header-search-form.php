<?php
global $vihan_theme_options;

$search_input     = ( !empty($vihan_theme_options['search_input']) ) ? esc_html($vihan_theme_options['search_input']) :  esc_html_x("WRITE SEARCH WORD...", 'Search placeholder word', 'vihan');
$searchform_title = ( isset($vihan_theme_options['searchform_title']) ) ? esc_html($vihan_theme_options['searchform_title']) :  esc_html_x("Hi, How Can We Help You?", 'Search form title word', 'vihan');
$search_logo = ( !empty($vihan_theme_options['logoimg_search']['full-url']) ) ? '<div class="themestek-search-logo"><img src="' . esc_url($vihan_theme_options['logoimg_search']['full-url']) . '" alt="' . esc_attr(get_bloginfo('name')) . '" /></div>' : '' ;
if( !empty($searchform_title) ){
	$searchform_title = '<div class="themestek-form-title">' . $searchform_title . '</div>';
}

if( !empty( $vihan_theme_options['header_search'] ) && $vihan_theme_options['header_search'] == true ){
	?>

<div class="themestek-search-overlay">
	<div class="themestek-bg-layer"></div>
	<div class="themestek-icon-close"></div>
	<div class="themestek-search-outer">
		<?php echo themestek_wp_kses($search_logo); ?>
		<?php echo themestek_wp_kses($searchform_title); ?>
<div class="serchbtn">search</div>
		<form method="get" class="themestek-site-searchform ttt" action="<?php echo esc_url( home_url() ); ?>">
			<input type="search" class="field searchform-s" name="s" placeholder="<?php echo esc_attr($search_input); ?>" />
			<button type="submit"><?php esc_html_e('SEARCH','vihan'); ?></button>
		</form>
	</div>
</div>
<?php } ?>