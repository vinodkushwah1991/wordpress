    <?php
    /*
     * Custom PHP code for child theme will be here
     */

    /** Adding child theme's style.css **/
    function themestek_vihan_child_style_css(){
    	wp_enqueue_style( 'vihan-child-style', get_stylesheet_directory_uri() . '/style.css' );

    }
    add_action( 'wp_enqueue_scripts', 'themestek_vihan_child_style_css', 20 );



    function enqueue_jquery_ui() {
      wp_enqueue_script('jquery-ui-core');
      wp_enqueue_script('jquery-ui-autocomplete');
    }
    add_action('wp_enqueue_scripts', 'enqueue_jquery_ui');

    function enqueue_jquery_ui_styles() {
      wp_enqueue_style('jquery-ui-autocomplete');
    }
    add_action('wp_enqueue_scripts', 'enqueue_jquery_ui_styles');


    function search_custom_posts() {
      // Retrieve the search queries from the AJAX request
     $searchQuery1 = isset($_POST['search_query_1']) ? sanitize_text_field($_POST['search_query_1']) : '';
     $searchQuery2 = isset($_POST['search_query_2']) ? sanitize_text_field($_POST['search_query_2']) : '';
      
    global $wpdb;

    $desired_field_value_1 = stripslashes($searchQuery1); // Replace with the desired value for field 1
    $desired_field_value_2 = stripslashes($searchQuery2); // Replace with the desired value for field 2

    // print_r($desired_field_value_1);
    // print_r($desired_field_value_2);

    if ($desired_field_value_1 && $desired_field_value_2) {
        // $desired_field_value_2 = stripslashes($desired_field_value_2);
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT post_id
            FROM $wpdb->postmeta AS pm1
            WHERE pm1.meta_key LIKE %s
            AND pm1.meta_value = %s
            AND EXISTS (
                SELECT post_id
                FROM $wpdb->postmeta AS pm2
                WHERE pm2.meta_key LIKE %s
                AND pm2.meta_value = %s
                AND pm2.post_id = pm1.post_id
            )",
            'street_details_%_locality',
             wp_specialchars_decode($desired_field_value_1),
            'street_details_%_street_name',
           wp_specialchars_decode($desired_field_value_2) 
        )
    );

        $post_ids = wp_list_pluck($results, 'post_id');
        $args = array(
            'post_type' => 'research-spot',
            'post__in' => $post_ids,
            'meta_key' => 'publication_date',
            'orderby' => 'meta_value',
            'order' => 'DESC', // Change to 'DESC' for descending order
            'posts_per_page' => -1
        );
  
    }

    elseif($desired_field_value_2 && !$desired_field_value_1){
        //print_r("two");
        $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT post_id
            FROM $wpdb->postmeta
            WHERE (meta_key LIKE 'street_details_%%_locality' AND meta_value = %s)
            OR (meta_key LIKE 'street_details_%%_street_name' AND meta_value = %s)",
             wp_specialchars_decode($desired_field_value_1),
            wp_specialchars_decode($desired_field_value_2)
        )
    );
        $post_ids = wp_list_pluck($results, 'post_id');
        $args = array(
            'post_type' => 'research-spot',
            'post__in' => $post_ids,
            'meta_key' => 'publication_date',
            'orderby' => 'meta_value',
            'order' => 'DESC', // Change to 'DESC' for descending order
            'posts_per_page' => -1
        );
    }
    if (!$desired_field_value_1 && !$desired_field_value_2) {
    $post_ids = wp_list_pluck($results, 'post_id');
    $args = array(
        'post_type' => 'research-spot',
        'post__in' => $post_ids,
        'meta_key' => 'publication_date',
        'orderby' => 'meta_value',
        'order' => 'DESC', // Change to 'DESC' for descending order
        'posts_per_page' => -1
    );
    }
    $query = new WP_Query($args);

    if(isset($_POST['onChangeHandler1'])){
    	 if ($query->have_posts()) {
    	    $data_array = array();

      while ($query->have_posts()) {
      	 $query->the_post();
      	 
     if (have_rows('street_details')) {
        while (have_rows('street_details')) {
            the_row();

            $customFieldValue = get_sub_field('locality'); // Get the value of the subfield
            array_push($data_array, $customFieldValue); // Push the value into the array
        }
    }
    }

    $response = json_encode($data_array, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS);

    header('Content-Type: application/json; charset=utf-8');
    echo $response;
    exit();
    }
    }

    if(isset($_POST['onChangeHandler2'])){
    	 if ($query->have_posts()) {
    	    $data_array2 = array();

      while ($query->have_posts()) {
      	 $query->the_post();
      	 
     if (have_rows('street_details')) {
        while (have_rows('street_details')) {
            the_row();
             $locality2 = get_sub_field('locality');
            $customFieldValue2 = get_sub_field('street_name');
            $data_array2[] = array(
                'locality' => $locality2,
                'street' => $customFieldValue2
            );
        }
    }
    }

    $response2 = json_encode($data_array2, JSON_UNESCAPED_UNICODE | JSON_HEX_APOS);

    header('Content-Type: application/json; charset=utf-8');
    echo $response2;
    exit();
    }
    }

    else{
    	// Check if any posts were found

      if ($query->have_posts()) {
      	?>
      	<tr>
        <th width="25%">Notice No.</th>
        <th width="25%">Publication Date</th>
        <th width="25%">Description</th>
        <th width="25%">Links</th>
      </tr>
      	<?php
     while ($query->have_posts()) {
        $query->the_post();

        // Display post information here

        $streetCount = 0;

        if (have_rows('street_details')):
            while (have_rows('street_details')): the_row();
                if (get_sub_field('street_name') == $desired_field_value_2) {
                    $streetCount++;
                }
            endwhile;
        endif;

        for ($i = 0; $i < $streetCount; $i++) {
            $noticeNumber = '';
            $description = '';

            if (have_rows('street_details')):
                while (have_rows('street_details')): the_row();
                    if (get_sub_field('street_name') == $desired_field_value_2) {
                        $noticeNumber = get_sub_field('notice_no');
                        $description = get_sub_field('description');
                        break;
                    }
                endwhile;
            endif;
            ?>
            <tr>
                <td class="notice_number"><?php echo $noticeNumber; ?></td>
                <td class="publication_date"><?php echo the_field('publication_date'); ?></td>
                <td class="description"><?php echo $description; ?></td>
                <td class="download_record"><a target="_blank" href="<?php the_field('pdf_links'); ?>">Download <i class="themestek-vihan-icon-download"></i></a></td>
            </tr>
            <?php
        }


    }


        // Reset the post data
        wp_reset_postdata();
      } else {
        // No posts found
        echo 'No posts found.';
      }
    }
      die(); // End the AJAX request
    }
    add_action('wp_ajax_search_custom_posts', 'search_custom_posts'); // For logged-in users
    add_action('wp_ajax_nopriv_search_custom_posts', 'search_custom_posts'); // For non-logged-in users

    //custom widget start
    function custom_widget_area() {
        register_sidebar(array(
            'name'          => __( 'Custom Widget Area', 'your-theme' ),
            'id'            => 'custom-widget-area',
            'description'   => __( 'Add widgets here to display in the custom widget area.', 'your-theme' ),
            'before_widget' => '<div class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h3 class="widget-title">',
            'after_title'   => '</h3>',
        ));
    }
    add_action( 'widgets_init', 'custom_widget_area' );

     //custom widget end



    // AJAX handler to get dropdown options
add_action('wp_ajax_get_dropdown_options', 'get_dropdown_options');
add_action('wp_ajax_nopriv_get_dropdown_options', 'get_dropdown_options');
function get_dropdown_options() {
$locateRegion = isset($_POST['locateRegionVal']) ? sanitize_text_field($_POST['locateRegionVal']) : '';
$locateLocality = isset($_POST['locateLocalityVal']) ? sanitize_text_field($_POST['locateLocalityVal']) : '';
$locatLocalCouncil = isset($_POST['locatLocalCouncilVal']) ? sanitize_text_field($_POST['locatLocalCouncilVal']) : '';

$locateLocalityOnchange = isset($_POST['locateLocalityValOnchange']) ? sanitize_text_field($_POST['locateLocalityValOnchange']) : '';
$localCouncilOnchange = isset($_POST['localCouncilOnchange']) ? sanitize_text_field($_POST['localCouncilOnchange']) : '';
$onchangeLocality = isset($_POST['localCouncilOnchange']) ? sanitize_text_field($_POST['localCouncilOnchange']) : '';

$locateRegion = str_replace('\\', '', $locateRegion);
$locateLocality = str_replace('\\', '', $locateLocality);
$locatLocalCouncil = str_replace('\\', '', $locatLocalCouncil);
$locateLocalityOnchange = str_replace('\\', '', $locateLocalityOnchange);
$localCouncilOnchange = str_replace('\\', '', $localCouncilOnchange);
$onchangeLocality = str_replace('\\', '', $onchangeLocality);


$args = array(
    'post_type' => 'locate-street', // Replace with your custom post type name
    'posts_per_page' => -1,
);

$meta_query = array();

if ($locateRegion && $locateLocality && $locatLocalCouncil) {
    // All three fields are selected
    $meta_query['relation'] = 'AND';
    $meta_query[] = array(
        'key' => 'locate_region',
        'value' => $locateRegion,
        'compare' => '='
    );
    $meta_query[] = array(
        'key' => 'locate_locality',
        'value' => $locateLocality,
        'compare' => '='
    );
    $meta_query[] = array(
        'key' => 'locate_local_council',
        'value' => $locatLocalCouncil,
        'compare' => '='
    );
} 
elseif ($locateRegion && $locateLocality) {
    // Both Region and Locality fields are selected
    $meta_query['relation'] = 'AND';
    $meta_query[] = array(
        'key' => 'locate_region',
        'value' => $locateRegion,
        'compare' => '='
    );
    $meta_query[] = array(
        'key' => 'locate_locality',
        'value' => $locateLocality,
        'compare' => '='
    );
} 
elseif ($locateRegion && $locatLocalCouncil) {
    // Region and Local Council fields are selected
    $meta_query['relation'] = 'AND';
    $meta_query[] = array(
        'key' => 'locate_region',
        'value' => $locateRegion,
        'compare' => '='
    );
    $meta_query[] = array(
        'key' => 'locate_local_council',
        'value' => $locatLocalCouncil,
        'compare' => '='
    );
} elseif ($locateRegion) {
    // Only Region field is selected
    $meta_query[] = array(
        'key' => 'locate_region',
        'value' => $locateRegion,
        'compare' => '='
    );
} 
elseif ($locateLocality) {
    // Only Locality field is selected
    $meta_query[] = array(
        'key' => 'locate_locality',
        'value' => $locateLocality,
        'compare' => '='
    );
} 
elseif ($locatLocalCouncil) {
    // Only Local Council field is selected
    $meta_query[] = array(
        'key' => 'locate_local_council',
        'value' => $locatLocalCouncil,
        'compare' => '='
    );
}
elseif ($locateLocalityOnchange) {
    // Only Region field is selected
    $meta_query[] = array(
        'key' => 'locate_region',
        'value' => $locateLocalityOnchange,
        'compare' => '='
    );
} 
elseif ($onchangeLocality) {
    // Only Region field is selected
    $meta_query[] = array(
        'key' => 'locate_locality',
        'value' => $onchangeLocality,
        'compare' => '='
    );
}

if (empty($meta_query)) {
    $args['meta_query'] = $meta_query;
}
    $query = new WP_Query($args);
    $data_array = array();

      if (isset($_POST['onChangeHandlerlocateRegion'])) {
        if ($query->have_posts()) {
            $data_array_region = array();
          while ($query->have_posts()) {
                $query->the_post();
                 if (have_rows('locate_street_all')) { // Assuming 'locate_street_all' is your repeater field
                while (have_rows('locate_street_all')) {
                    the_row();
                    $localityVal = get_sub_field('locate_locality'); // Get the subfield value
                    $regionValue = get_sub_field('locate_region'); // Get the subfield value
                    $Enddate = get_sub_field('end_date');
                        $data_array_region[] = array(
                            'region' => $regionValue,
                            'locality' => $localityVal,
                            'Enddate' => $Enddate
                        );
                }
            }
            }
            wp_reset_postdata(); // Reset the post data
             header('Content-Type: application/json; charset=utf-8');
            echo json_encode($data_array_region, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
            exit();
        }
    }

if (isset($_POST['onChangeHandlerlocateLocality'])) {
    if ($query->have_posts()) {
        $data_array_locality = array();

        while ($query->have_posts()) {
            $query->the_post();
            if (have_rows('locate_street_all')) { // Assuming 'locate_street_all' 
                while (have_rows('locate_street_all')) {
                    the_row();
                    $regionVal = get_sub_field('locate_region');
                    $localityValue = get_sub_field('locate_locality'); // Get the subfield value
                    $localCounciVal = get_sub_field('locate_local_council');
                    $Enddate = get_sub_field('end_date');
                        $data_array_locality[] = array(
                            'localty' => $localityValue,
                            'region' => $regionVal,
                            'council' => $localCounciVal,
                            'Enddate' => $Enddate

                        );  
                }
            }
        }
        
        wp_reset_postdata(); // Reset the post data
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data_array_locality, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }
}


if (isset($_POST['onChangeHandlerlocatecouncil'])) {
    if ($query->have_posts()) {
        $data_array_council = array();

        while ($query->have_posts()) {
            $query->the_post();
            if (have_rows('locate_street_all')) {
                while (have_rows('locate_street_all')) {
                    the_row();
                    $councilValue = get_sub_field('locate_local_council');
                    $regionVal = get_sub_field('locate_region');
                    $localVal = get_sub_field('locate_locality');
                    $Enddate = get_sub_field('end_date');
                    $data_array_council[] = array(
                        'councl' => $councilValue,
                        'region' => $regionVal,
                        'localty' => $localVal,
                        'Enddate' => $Enddate
                );
                }
            }
        }
        wp_reset_postdata();
        if (empty($data_array_council)) {
            wp_die('No data found');
        }
         header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data_array_council, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        exit();
    }
}




    // Check if any posts were found
    if ($query->have_posts()) {
        ?>
        <tr>
            <th width="25%">Region</th>
            <th width="25%">Locality</th>
            <th width="25%">Local Council</th>
            <th width="25%">Street</th>
        </tr>
        <?php
        while ($query->have_posts()) {
            $query->the_post();

            // Initialize an array to store the matching rows
            $matchingRows = [];

            if (have_rows('locate_street_all')) {
                while (have_rows('locate_street_all')) {
                    the_row();
                    $streetRegion = get_sub_field('locate_region');
                    $streetLocality = get_sub_field('locate_locality');
                    $streetCouncil = get_sub_field('locate_local_council');
                    $street = get_sub_field('locate_street');
                    // Check if the current row matches the conditions
                    if (
                        (!$locateRegion || $streetRegion == $locateRegion) &&
                        (!$locateLocality || $streetLocality == $locateLocality) &&
                        (!$locatLocalCouncil || $streetCouncil == $locatLocalCouncil)
                    ) {
                        $matchingRows[] = [
                            'region' => $streetRegion,
                            'locality' => $streetLocality,
                            'local_council' => $streetCouncil,
                            'street' => $street,
                        ];
                    }
                }
            }

            // Display the matching rows
            foreach ($matchingRows as $row) {
                ?>
                <tr>
                    <td class="locat_region"><?php echo $row['region']; ?></td>
                    <td class="locat_locality"><?php echo $row['locality']; ?></td>
                    <td class="locate_council"><?php echo $row['local_council']; ?></td>
                    <td class="locate_street"><?php echo $row['street']; ?></td>
                </tr>
                <?php
            }
        }
        wp_reset_postdata();
    } else {
        // No posts found
        echo 'No posts found.';
    }
     
  }




  function custom_field_search_callback() {
   $search_term = sanitize_text_field($_GET['term']);

// Initialize an empty array to store the results
$results = array();

// Query all posts of 'locate-street' post type
$args = array(
    'post_type' => 'locate-street',
    'posts_per_page' => -1, // Retrieve all posts
);

$query = new WP_Query($args);

if ($query->have_posts()) {
    while ($query->have_posts()) {
        $query->the_post();

        // Get the repeater field data for the current post
        $repeater_field = get_field('locate_street_all'); // Replace with your repeater field name/key

        if ($repeater_field) {
            foreach ($repeater_field as $row) {
                // Check if any subfield within the repeater matches the search term
                if (
                    strpos(strtolower($row['locate_street']), strtolower($search_term)) !== false ||
                    strpos(strtolower($row['locate_region']), strtolower($search_term)) !== false ||
                    strpos(strtolower($row['locate_locality']), strtolower($search_term)) !== false ||
                    strpos(strtolower($row['locate_local_council']), strtolower($search_term)) !== false
                ) {
                    // If a match is found, add it to the results
                    $results[] = array(
                        'label' => $row['locate_street'],
                        'region' => $row['locate_region'],
                        'locality' => $row['locate_locality'],
                        'localCouncil' => $row['locate_local_council'],
                    );
                }
            }
        }
    }
    wp_reset_postdata();
}

// Return the results as JSON
echo json_encode($results);
wp_die();

}
add_action('wp_ajax_custom_field_search', 'custom_field_search_callback');
add_action('wp_ajax_nopriv_custom_field_search', 'custom_field_search_callback');


//door-numbering
add_shortcode( 'doornumbering-post', 'doornumbering' );
function doornumbering( $args ) {
?>
<div class="post-row">
<?php 
	$args =  array(
		'tax_query' => array(
			array(
				'taxonomy' => 'category',  
                'field' => 'slug',
				'terms' => array( 'door-numbering' )   
			),
		),
		'post_type' => 'post',  
        'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'posts_per_page' => 2
	);
	$the_query = new WP_Query( $args ); 
	if ( $the_query->have_posts() ) :
	while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	<div class="post-col">
		<a href="<?php the_permalink();?>" tabindex="-1">
			<h3> <?php echo get_the_title(); ?></h3>
		</a>	
		<p>
			<?php the_time('j/m/Y') ?>
		</p>
	</div>
	<?php 
	endwhile;
	endif;?>
</div>
<?php
}


//street-numbering
add_shortcode( 'streetnumbering-post', 'streetnumbering' );
function streetnumbering( $args ) {
?>
<div class="post-row">
<?php 
	$args =  array(
		'tax_query' => array(
			array(
				'taxonomy' => 'category',  
                'field' => 'slug',
				'terms' => array( 'street-numbering' )   
			),
		),
		'post_type' => 'post',  
        'post_status' => 'publish',
		'orderby' => 'date',
		'order' => 'DESC',
		'posts_per_page' => 2
	);
	$the_query = new WP_Query( $args ); 
	if ( $the_query->have_posts() ) :
	while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	<div class="post-col">
		<a href="<?php the_permalink();?>" tabindex="-1">
			<h3> <?php echo get_the_title(); ?></h3>
		</a>	
		<p>
			<?php the_time('j/m/Y') ?>
		</p>
	</div>
	<?php 
	endwhile;
	endif;?>
</div>
<?php
}


require_once( get_stylesheet_directory() . '/include/acf_settings_page.php' );
require_once( get_stylesheet_directory() . '/include/acf_regios_ajax.php' );
require_once( get_stylesheet_directory() . '/include/import_script.php' );

require_once( get_stylesheet_directory() . '/excel/vendor/autoload.php' );

require_once( get_stylesheet_directory() . '/include/import_research_post_type.php' );
require_once( get_stylesheet_directory() . '/include/upload_pdf_page.php' );
require_once( get_stylesheet_directory() . '/include/custom_ajax_file.php' );
//shortcode.php
require_once( get_stylesheet_directory() . '/include/shortcode.php' );


//acf/load_field/type={$type} Applies to all fields of a region value
function region_acf_load_field( $field ) {
global $wpdb;
	$result = $wpdb->get_results ( "SELECT * FROM  amu_regions order by region_name_mt ASC" );

    $field['required'] = true;
    $regionChoice = array();
      foreach ($result as $key => $regionsArr) {
       $regionChoice[$regionsArr->region_name_mt]=$regionsArr->region_name_mt;
      }

    $field['choices'] = $regionChoice;
    return $field;
}
add_filter('acf/load_field/name=locate_region', 'region_acf_load_field');

//acf/load_field/type={$type} Applies to all fields of a region value
function locality_acf_load_field( $field ) {
global $wpdb;
	$result = $wpdb->get_results ( "SELECT * FROM  amu_localities_list order by Locality_Name ASC" );

    $field['required'] = true;
    $localityChoice = array();
      foreach ($result as $key => $regionsArr) {
       $localityChoice[$regionsArr->Locality_Name]=$regionsArr->Locality_Name;
      }

    $field['choices'] = $localityChoice;
    return $field;
}
add_filter('acf/load_field/name=locate_locality', 'locality_acf_load_field');


function locality_local_acf_load_field( $field ) {
global $wpdb;
	$result = $wpdb->get_results ( "SELECT * FROM  amu_local_council_list order by local_council_name ASC" );

    $field['required'] = true;
    $localityChoiceLocal = array();
      foreach ($result as $key => $regionsArr) {
       $localityChoiceLocal[$regionsArr->local_council_name]=$regionsArr->local_council_name;
      }

    $field['choices'] = $localityChoiceLocal;
    return $field;
}
add_filter('acf/load_field/name=locate_local_council', 'locality_local_acf_load_field');


    /*Custom Post type start*/
    function cw_post_type_news() {
    $supports = array(
    'title', // post title
    'editor', // post content
    'author', // post author
    'thumbnail', // featured images
    'excerpt', // post excerpt
    'custom-fields', // custom fields
    'comments', // post comments
    'revisions', // post revisions
    'post-formats', // post formats
    );
    $labels = array(
    'name' => _x('news', 'plural'),
    'singular_name' => _x('news', 'singular'),
    'menu_name' => _x('news', 'admin menu'),
    'name_admin_bar' => _x('news', 'admin bar'),
    'add_new' => _x('Add New', 'add new'),
    'add_new_item' => __('Add New news'),
    'new_item' => __('New news'),
    'edit_item' => __('Edit news'),
    'view_item' => __('View news'),
    'all_items' => __('All news'),
    'search_items' => __('Search news'),
    'not_found' => __('No news found.'),
    );
    $args = array(
    'supports' => $supports,
    'labels' => $labels,
    'public' => true,
    'query_var' => true,
    'rewrite' => array('slug' => 'news'),
    'has_archive' => true,
    'hierarchical' => false,
    );
    register_post_type('news', $args);
    }
    add_action('init', 'cw_post_type_news');
    /*Custom Post type end*/


require_once(ABSPATH . "wp-admin" . '/includes/image.php');
require_once(ABSPATH . "wp-admin" . '/includes/file.php');
require_once(ABSPATH . "wp-admin" . '/includes/media.php');



add_action('wp_ajax_filter_locate_a_streets', 'filter_locate_a_streets');
add_action('wp_ajax_nopriv_filter_locate_a_streets', 'filter_locate_a_streets');
function filter_locate_a_streets() {
 global $wpdb;


$locateRegion = isset($_POST['locateRegionVal']) ? sanitize_text_field($_POST['locateRegionVal']) : '';
$locateLocality = isset($_POST['locateLocalityVal']) ? sanitize_text_field($_POST['locateLocalityVal']) : '';
$locatLocalCouncil = isset($_POST['locatLocalCouncilVal']) ? sanitize_text_field($_POST['locatLocalCouncilVal']) : '';
$searchstreet = isset($_POST['searchstreet']) ? sanitize_text_field($_POST['searchstreet']) : '';


$locateLocalityOnchange = isset($_POST['locateLocalityValOnchange']) ? sanitize_text_field($_POST['locateLocalityValOnchange']) : '';
$localCouncilOnchange = isset($_POST['localCouncilOnchange']) ? sanitize_text_field($_POST['localCouncilOnchange']) : '';
$onchangeLocality = isset($_POST['localCouncilOnchange']) ? sanitize_text_field($_POST['localCouncilOnchange']) : '';

$locateRegion = str_replace('\\', '', $locateRegion);
$locateLocality = str_replace('\\', '', $locateLocality);
$locatLocalCouncil = str_replace('\\', '', $locatLocalCouncil);
$searchstreet =  str_replace('\\', '', $searchstreet);


if($locateRegion!=""){
    $amu_regions = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM amu_regions WHERE id = $locateRegion" ) );
    $locateRegion = $amu_regions->region_name_mt;
}else{
$locateRegion = "";

}

if($locateLocality!=""){
    $result = $wpdb->get_row( "SELECT * FROM  amu_localities_list where locality_Code='".$locateLocality."'" );

   $locateLocality = $result->Locality_Name;
}else{
$locateLocality = "";

}



$draw = $_POST['draw'];
$row = $_POST['start'];
$rowperpage = $_POST['length']; // Rows display per page
$columnIndex = $_POST['order'][0]['column']; // Column index
$columnName = $_POST['columns'][$columnIndex]['data']; // Column name
$columnSortOrder = $_POST['order'][0]['dir']; // asc or desc

/*
$args = array(
    'post_type' => 'locate-street', // Replace with your custom post type name
    'posts_per_page' => -1,
);

$meta_query = array();

if ($locateRegion && $locateLocality && $locatLocalCouncil) {
    // All three fields are selected
    $meta_query['relation'] = 'AND';
    $meta_query[] = array(
        'key' => 'locate_region',
        'value' => $locateRegion,
        'compare' => '='
    );
    $meta_query[] = array(
        'key' => 'locate_locality',
        'value' => $locateLocality,
        'compare' => '='
    );
    $meta_query[] = array(
        'key' => 'locate_local_council',
        'value' => $locatLocalCouncil,
        'compare' => '='
    );
} 
elseif ($locateRegion && $locateLocality) {
    // Both Region and Locality fields are selected
    $meta_query['relation'] = 'AND';
    $meta_query[] = array(
        'key' => 'locate_region',
        'value' => $locateRegion,
        'compare' => '='
    );
    $meta_query[] = array(
        'key' => 'locate_locality',
        'value' => $locateLocality,
        'compare' => '='
    );
} 
elseif ($locateRegion && $locatLocalCouncil) {
    // Region and Local Council fields are selected
    $meta_query['relation'] = 'AND';
    $meta_query[] = array(
        'key' => 'locate_region',
        'value' => $locateRegion,
        'compare' => '='
    );
    $meta_query[] = array(
        'key' => 'locate_local_council',
        'value' => $locatLocalCouncil,
        'compare' => '='
    );
} elseif ($locateRegion) {
    // Only Region field is selected
    $meta_query[] = array(
        'key' => 'locate_region',
        'value' => $locateRegion,
        'compare' => '='
    );
} 
elseif ($locateLocality) {
    // Only Locality field is selected
    $meta_query[] = array(
        'key' => 'locate_locality',
        'value' => $locateLocality,
        'compare' => '='
    );
} 
elseif ($locatLocalCouncil) {
    // Only Local Council field is selected
    $meta_query[] = array(
        'key' => 'locate_local_council',
        'value' => $locatLocalCouncil,
        'compare' => '='
    );
}


if (empty($meta_query)) {
    $args['meta_query'] = $meta_query;
}
    $query = new WP_Query($args);
    $data_array = array();
$total = $query->found_posts;
    */
$meta_query = array();
 if($locateRegion!=""){
       $meta_query[] = array( 'key' => 'locate_street_all_$_locate_region', 'value' => $locateRegion); 
  }
  if($locateLocality!=""){
        $meta_query[] = array( 'key' => 'locate_street_all_$_locate_locality', 'value' => $locateLocality ); 
  }
   if($locatLocalCouncil!=""){
        $meta_query[] = array( 'key' => 'locate_street_all_$_locate_local_council', 'value' => $locatLocalCouncil ); 
  }
  
 if($searchstreet!=""){
        $meta_query[] = array( 'key' => 'locate_street_all_$_locate_street', 'value' => $searchstreet ); 
  }



$query_args = array(
        'post_type' => 'locate-street',
        'post_status' => 'publish',
        'posts_per_page' => -1,
         'meta_query' => array('relation' => 'AND',$meta_query,
	   ),
    );
    $query = new WP_Query( $query_args );
    if ($query->have_posts()) {
        ?>
       
        <?php
         $matchingRows = [];
        while ($query->have_posts()) {
            $query->the_post();
           
               $matchingRows = [];
            if (have_rows('locate_street_all')) {
                while (have_rows('locate_street_all',get_the_ID())) {
                    the_row();
                    $streetRegion = get_sub_field('locate_region');
                    $streetLocality = get_sub_field('locate_locality');
                    $streetCouncil = get_sub_field('locate_local_council');
                    $street = get_sub_field('locate_street');
                     $end_date = get_sub_field('end_date');
                  
                    // Check if the current row matches the conditions
                    if( $end_date!=""){
                      $end_date= date("Y-m-d", strtotime( $end_date) );
                      $currentDate = strtotime(date("Y-m-d"));
                    
                       $olddate = strtotime($end_date);
                     }
                 if($end_date=="" || $currentDate >= $olddate){
                   // if (
                       // (!$locateRegion || $streetRegion == $locateRegion) &&
                       // (!$locateLocality || $streetLocality == $locateLocality) &&
                      //  (!$locatLocalCouncil || $streetCouncil == $locatLocalCouncil)
                   // ) {
                        $matchingRows[] = [
                            'region' => $streetRegion,
                            'locality' => $streetLocality,
                            'local_council' => $streetCouncil,
                            'street' => $street,
                        ];
                    //}
                }
              }
            }

            // Display the matching rows

            foreach ($matchingRows as $row) {
	            	 $data[] = array( 
				      "emp_name"=>$row['region'],
				      "email"=>$row['locality'],
				      "gender"=>$row['local_council'],
				      "salary"=>$row['street'],
				     
				   );
                ?>
               
                <?php
            }
            }
        wp_reset_postdata();
          } 
           
            if(empty($data)){
            	$data= [];
            }
				$response = array(
				  "draw" => intval($draw),
				  "iTotalRecords" => $total ,
				  "iTotalDisplayRecords" => $total,
				  "aaData" => $data
				);

				echo json_encode($response);
        
   
     die;
  }



add_filter('posts_where', 'yanco_posts_where');
function yanco_posts_where( $where ) {
    $where = str_replace( "meta_key = 'locate_street_all_$", "meta_key LIKE 'locate_street_all_%", $where );

	return $where;
}