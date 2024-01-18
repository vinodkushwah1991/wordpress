<?php 
    add_action('wp_ajax_get_region_list_onChange', 'get_region_list_onChange'); // For logged-in users
    add_action('wp_ajax_nopriv_get_region_list_onChange', 'get_region_list_onChange'); // For non-logged-in users

    function get_region_list_onChange(){
    	global $wpdb;
    	$locateRegionId = $_POST['locateRegionId'];
$result = $wpdb->get_results ( "SELECT * FROM  amu_localities_list  where region_Code='".$locateRegionId."'" ); ?>
<option value="">Select Locality</option>
<?php 
foreach ($result as $key => $value) { ?>
	 <option value="<?php echo $value->locality_Code;?>"><?php echo $value->Locality_Name;?></option>
<?php } 
?>
<?php 
    die;
}

add_action('wp_ajax_get_locality_list_onChange', 'get_locality_list_onChange'); // For logged-in users
add_action('wp_ajax_nopriv_get_locality_list_onChange', 'get_locality_list_onChange'); // For non-logged-in users

    function get_locality_list_onChange(){
    	global $wpdb;
    	$locateLocality = $_POST['locateLocality'];
    	 $result = $wpdb->get_results("SELECT * FROM  amu_localities_list   where locality_Code='".$locateLocality."'" );
    	
//$result = $wpdb->get_results ( "SELECT * FROM  amu_local_council_list  where local_council_name='".$rowregionsList->local_council_name."'" ); ?>
<option value="">Select Local Council</option>
<?php foreach ($result as $key => $value) { ?>
	 <option value="<?php echo $value->local_council_name;?>"><?php echo $value->local_council_name;?></option>
<?php } 
?>
<?php 
    die;
}

//research_spot_table_data
add_action('wp_ajax_research_spot_table_data', 'research_spot_table_data'); // For logged-in users
add_action('wp_ajax_nopriv_research_spot_table_data', 'research_spot_table_data'); // For non-logged-in users

function research_spot_table_data(){
global $wpdb;


     $sqlQuery = "SELECT * FROM amu_research_spot ";
	if(!empty($_POST["search"]["value"])){
		$sqlQuery .= 'where id LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= ' OR street_name LIKE "%'.$_POST["search"]["value"].'%" ';			
		$sqlQuery .= ' OR locality_name LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= ' OR date LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= ' OR notice_number LIKE "%'.$_POST["search"]["value"].'%" ';
		$sqlQuery .= ' OR pdf_name LIKE "%'.$_POST["search"]["value"].'%" ';	
		$sqlQuery .= ' OR description LIKE "%'.$_POST["search"]["value"].'%" ';	
     
         $count_query = "SELECT count(*) FROM amu_research_spot ";
         $count_query .= 'where id LIKE "%'.$_POST["search"]["value"].'%" ';
		 $count_query .= ' OR street_name LIKE "%'.$_POST["search"]["value"].'%" ';			
		 $count_query .= ' OR locality_name LIKE "%'.$_POST["search"]["value"].'%" ';
		 $count_query .= ' OR date LIKE "%'.$_POST["search"]["value"].'%" ';
		 $count_query .= ' OR notice_number LIKE "%'.$_POST["search"]["value"].'%" ';
		 $count_query .= ' OR pdf_name LIKE "%'.$_POST["search"]["value"].'%" ';	
		 $count_query .= ' OR description LIKE "%'.$_POST["search"]["value"].'%" ';	
         $numRows = $wpdb->get_var($count_query);

	}
	if(!empty($_POST["order"])){
		$sqlQuery .= 'ORDER BY '.$_POST['order']['0']['column'].' '.$_POST['order']['0']['dir'].' ';
	} else {
		$sqlQuery .= 'ORDER BY id DESC ';
	}
	if($_POST["length"] != -1){
		$sqlQuery .= 'LIMIT ' . $_POST['start'] . ', ' . $_POST['length'];
	}	
	 $result = $wpdb->get_results($sqlQuery);
	 
	 if(empty($_POST["search"]["value"])){
		    $count_query = "select count(*) from amu_research_spot";
		    $numRows = $wpdb->get_var($count_query);
		}
    $employeeData = array();
    $count =1;
    foreach ($result as $key => $value) {
		$empRows = array();			
		$empRows[] =$count;
		$empRows[] = $value->street_name;
		$empRows[] = $value->locality_name;
		$empRows[] = $value->date;	
		$empRows[] = $value->notice_number;
		$empRows[] = $value->pdf_name;
		$empRows[] = $value->description;

		$empRows[] = '<a   href="'.home_url().'/wp-admin/admin.php?page=research-import&editPost='.$value->id.'" class="edit"><i class="material-icons" data-toggle="tooltip" title="Edit "></i></a>';
		$empRows[] = '<a  href="'.home_url().'/wp-admin/admin.php?page=research-import&delId='.$value->id.'" class="edit" data-toggle="modal" data-backdrop="false"><i class="material-icons" data-toggle="tooltip" title="Delete"></i>';
		$employeeData[] = $empRows;
	 $count++; }
	$output = array(
		"draw"				=>	intval($_POST["draw"]),
		"recordsTotal"  	=>  $numRows,
		"recordsFiltered" 	=> 	$numRows,
		"data"    			=> 	$employeeData
	);
	echo json_encode($output);
   

die;	
}

//get_resources_spot_dropwown_data

add_action('wp_ajax_get_resources_spot_dropwown_data', 'get_resources_spot_dropwown_data'); // For logged-in users
add_action('wp_ajax_nopriv_get_resources_spot_dropwown_data', 'get_resources_spot_dropwown_data'); // For non-logged-in users

    function get_resources_spot_dropwown_data(){
    	global $wpdb;

    	$locateLocality = $_POST['locateRegionId'];
$result = $wpdb->get_results ( "SELECT DISTINCT street_name,notice_number FROM  amu_research_spot  where locality_name='".$locateLocality."'" ); 
$resarr = array();
foreach ($result as $key => $value) {
$resarr[] = $value->street_name;
}
$resarr1 = array_unique($resarr);
?>
<option value="">Select Street</option>
<?php foreach ($resarr1 as $key => $street_name) { ?>
	 <option value="<?php echo $street_name;?>"><?php echo $street_name;?></option>
<?php } 
?>
<?php 
    die;
}


add_action('wp_ajax_filter_res_spot_formData', 'filter_res_spot_formData'); // For logged-in users
add_action('wp_ajax_nopriv_filter_res_spot_formData', 'filter_res_spot_formData'); // For non-logged-in users

    function filter_res_spot_formData(){
    	$uploads = wp_get_upload_dir();
    	global $wpdb;
    	$locatestreets = $_POST['locatestreets'];
    	$researh_spot_locality = $_POST['researh_spot_locality'];
    	if($researh_spot_locality!=""){
           $result = $wpdb->get_results ( "SELECT * FROM  amu_research_spot  where locality_name='".$researh_spot_locality."'" );
      }

      if($locatestreets!=""){
           $result = $wpdb->get_results ( "SELECT * FROM  amu_research_spot  where locality_name='".$researh_spot_locality."' AND street_name='".$locatestreets."'" );
      }

    foreach ($result as $key => $value) { ?>
	 <tr>
                <td class="notice_number"><?php echo $value->notice_number; ?></td>
                <td class="publication_date"><?php echo $value->date; ?></td>
                <td class="description"><?php echo $value->description; ?></td>
                <td class="download_record"><a target="_blank" href="<?php echo $uploads['baseurl']?>/pdf/<?php echo $value->pdf_name;?>">Download <i class="themestek-vihan-icon-download"></i></a></td>
            </tr>
<?php } 
?>
<?php 
    die;
}




add_action('wp_ajax_get_street_live_data', 'get_street_live_data'); // For logged-in users
add_action('wp_ajax_nopriv_get_street_live_data', 'get_street_live_data'); // For non-logged-in users

    function get_street_live_data(){
    	global $wpdb;

 
  if (isset($_POST['query'])) {
       $query = "SELECT * FROM amu_postmeta  WHERE `meta_key` LIKE 'locate_street_all_0_locate_street' AND `meta_value` LIKE '%{$_POST['query']}%' LIMIT 10";
      $result = $wpdb->get_results ($query);

    if ($wpdb->num_rows > 0) {
       foreach ($result as $key => $res) {
          
          echo '<li tabindex = "'.$key.'" class=list-group-item style=cursor:pointer value="'.$res->meta_value.'">'.$res->meta_value.'</li>';

      }
    } else {
      echo "
          Data not found
     
      ";
    }
  }



    die;
}


?>