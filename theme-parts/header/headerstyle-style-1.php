    <header id="masthead" class="<?php echo themestek_header_style_class(); ?>">
    	<div class="themestek-header-block <?php echo themestek_headerclass(); ?>">
    		<?php get_template_part('theme-parts/header/header','search-form'); ?>
    		<?php get_template_part('theme-parts/header/header','topbar'); ?>

    		<div id="themestek-stickable-header-w" class="themestek-stickable-header-w themestek-bgcolor-<?php echo themestek_get_option('header_bg_color'); ?>" style="min-height:<?php echo themestek_get_option('header_height'); ?>px">
    			<div id="site-header" class="site-header themestek-bgcolor-<?php echo themestek_get_option('header_bg_color'); ?> <?php echo themestek_sanitize_html_classes(themestek_sticky_header_class()); ?>" data-sticky-height="<?php echo esc_html(themestek_get_option('header_height_sticky')); ?>">
    				<div class="site-header-main container">

    					<div class="themestek-table">
    						<div class="themestek-header-left">
    							<div class="site-branding">
    								<?php echo themestek_site_logo(); ?>
    							</div><!-- .site-branding -->
    						</div>	
    						<div class="themestek-header-right">

    							<div id="site-header-menu" class="site-header-menu">
    								<nav id="site-navigation" class="main-navigation" aria-label="Primary Menu" >
    									<?php get_template_part('theme-parts/header/header','menu'); ?>
    								</nav>
    							</div> <!--.site-header-menu -->
                                dsfsdf
    							<?php
    								if( shortcode_exists('themestek-social-links') && themestek_get_option('header_social_show')===true ){
    									echo do_shortcode('[themestek-social-links tooltip="no"]');
    								}
    							?>
    							<?php echo themestek_wp_kses( themestek_header_links(), 'header_links' ); ?>
    						</div>
    					</div>

    				</div><!-- .themestek-header-top-wrapper -->
    			</div>
    		</div>

    		<?php get_template_part('theme-parts/header/header','titlebar'); ?>
    		<?php get_template_part('theme-parts/header/header','slider'); ?>
    	</div>
    </header><!-- .site-header -->
   
 
 

    <style type="text/css">
    .submenu {
      display: none;
      position: absolute;
      top: 0;
      left: 47%;
      min-width: 200px;
      background-color: #fff;
      box-shadow: 0 0 5px rgba(0, 0, 0, 0.3);
      opacity: 0;
      transition: opacity 0.3s, max-height 0.3s;
      max-height: 0;
      overflow: hidden;
    }
    .menu > li > a{
    	color: #fff;
        font-size: 25px;
        font-weight: bold;
    }
    .menu > li > a:hover{
    	color: #21b685;
    }
    .menu > li.active > a{
    	color: #21b685;
    }    
    .menu > li.active .submenu {
        display: block;
        opacity: 1;
        max-height: 400px;
        background: transparent;
        box-shadow: none;
        margin: 0;
    }

    .submenu > li > a {
        display: block;
        color: #fff;
        font-size: 12px;

    }
    .submenu > li > a:hover{text-decoration: underline;}
    .submenu li {
            display: inline-block;
        width: 98px;
        text-align: center;
    }
    .scroll-holder {
        position: relative;
    }
    h3.submenu_head {
       font-size: 25px;
        font-weight: 400;
        color: #b5aea9;
        border-bottom: none;
        max-width: none;
        margin: 10px 0 0;
        padding-left: 13px;
    }
    img.menu-image.menu-image-title-after {
        margin: 20px;
    }

    </style>
    <script type="text/javascript">
    const menuItems = document.querySelectorAll('.menu > li');

    menuItems.forEach(item => {
      const submenu = item.querySelector('.submenu');

      item.addEventListener('click', function(event) {
        const isActive = this.classList.contains('active');

        // Close all submenus
        menuItems.forEach(item => {
          item.classList.remove('active');
        });

        if (!isActive) {
          this.classList.add('active');
        }

        // Prevent the click event from bubbling up to the parent menu item
        event.stopPropagation();
      });

      // Add click event listener to submenu items
      const submenuItems = submenu.querySelectorAll('li');
      submenuItems.forEach(submenuItem => {
        submenuItem.addEventListener('click', function(event) {
          // Prevent the click event from bubbling up to the parent submenu
          event.stopPropagation();
        });
      });
    });
    </script>