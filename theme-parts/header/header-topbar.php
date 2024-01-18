<?php if( themestek_topbar_show() ) : ?>

<div class="themestek-pre-header-wrapper <?php echo themestek_sanitize_html_classes(themestek_topbar_classes()); ?>">
	<div class="themestek-pre-header-inner">
		<div class="<?php echo themestek_sanitize_html_classes(themestek_topbar_container_class()); ?>">
			<?php echo themestek_wp_kses( themestek_topbar_content(), 'topbar' ); ?>
			<form role="search" method="get" class="search-form" id="site_search" action="<?php echo home_url( '/' ); ?>">
    <label>
        <span class="search_for"><?php echo _x( 'What are you looking for?', 'label' ) ?></span>

        <input type="search" class="search-field"
            placeholder="<?php echo esc_attr_x( 'Search Term', 'placeholder' ) ?>"
            value="<?php echo get_search_query() ?>" name="s"
            title="<?php echo esc_attr_x( 'Search for:', 'label' ) ?>" />
    </label>
    <input type="submit" class="search-submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ) ?>" />
</form>
		</div>
	</div>
</div>

<?php endif;  // themestek_topbar_show() ?>
