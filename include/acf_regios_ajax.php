<?php 


add_action('acf/render_field', 'my_acf_render_field');
function my_acf_render_field( $field ) {
	//print_r( $field);
   // echo '<p>Some extra HTML.</p>';
}
function my_acf_input_admin_footer() {
    
?>
<script type="text/javascript">
    
 jQuery(function($){
	$(document).on('change', '[data-key="field_64f96b9d9fac4"] .acf-input select', function(e) {
       // alert($(this).val());
    var selectEleId=  $(this).closest('td').next('td').find('select').attr('id');
     var selectEleId2=  $(this).closest('td').next('td').next('td').find('select').attr('id');
     var region= $(this).val();
    // alert(selectEleId2);
    jQuery.ajax({
         type : "post",
         dataType : "html",
         url : '<?php echo admin_url( 'admin-ajax.php' )?>',
         data : {action: "locate_locality", regionName : $(this).val()},
         success: function(response) {
                $('#'+selectEleId2).html(response);
                getlocality(selectEleId,region);
         }
      }) 

});

 function getlocality(selectEleId2,region){
 jQuery.ajax({
         type : "post",
         dataType : "html",
         url : '<?php echo admin_url( 'admin-ajax.php' )?>',
         data : {action: "loadoCateLocalCouncil", regionName : region},
         success: function(response) {         	
                $('#'+selectEleId2).html(response);
              
         }
      }) 
 }
//filter select value onchange localities

$(document).on('change', '[data-key="field_6524fcc5cd850"] .acf-input select', function(e) {
       // alert($(this).val());
    var selectEleId=  $(this).closest('td').next('td').find('select').attr('id');
     var localityName= $(this).val();
    // alert(selectEleId2);
    jQuery.ajax({
         type : "post",
         dataType : "html",
         url : '<?php echo admin_url( 'admin-ajax.php' )?>',
         data : {action: "locate_locality_council", localityName : $(this).val()},
         success: function(response) {
                $('#'+selectEleId).html(response);
         }
      }) 

});
});
    
</script>
<?php
        
}

add_action('acf/input/admin_footer', 'my_acf_input_admin_footer');

add_action('wp_ajax_loadoCateLocalCouncil', 'loadoCateLocalCouncil');
add_action('wp_ajax_nopriv_loadoCateLocalCouncil', 'loadoCateLocalCouncil');

function loadoCateLocalCouncil(){
global $wpdb;
$regionName=  $_POST['regionName'];
 $amu_regions = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM amu_regions WHERE region_name_mt = '".$regionName."'" ) );
echo $amu_regions->id;
$result = $wpdb->get_results ( "SELECT * FROM  amu_localities_list where region_Code = '".$amu_regions->id."'" );

foreach ($result as $key => $value) { ?>
	<option value="<?php echo $value->Locality_Name;?>"><?php echo $value->Locality_Name;?></option>
<?php }
die;	
}


add_action('wp_ajax_locate_locality', 'locate_locality');
add_action('wp_ajax_nopriv_locate_locality', 'locate_locality');
function locate_locality(){
global $wpdb;
$localityname=  $_POST['regionName'];
 $amu_locality = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM amu_localities_list WHERE Locality_Name = '".$localityname."'" ) );
$result = $wpdb->get_results ( "SELECT * FROM  amu_local_council_list where locality_code = '".$amu_locality->locality_Code."'" );

foreach ($result as $key => $value) { ?>
	<option value="<?php echo $value->local_council_name;?>"><?php echo $value->local_council_name;?></option>
<?php }
die;	
}

add_action('wp_ajax_locate_locality_council', 'locate_locality_council');
add_action('wp_ajax_nopriv_locate_locality_council', 'locate_locality_council');
function locate_locality_council(){
global $wpdb;
$localityName=  $_POST['localityName'];
 $result = $wpdb->get_results("SELECT * FROM amu_localities_list  WHERE Locality_Name = '".$localityName."'");

//$result = $wpdb->get_results( "SELECT * FROM  amu_local_council_list where locality_code = '".$amu_locality->locality_Code."'" );

 if($wpdb->num_rows > 0){


 


foreach ($result as $key => $value) {

 ?>
	<option value="<?php echo $value->local_council_name;?>"><?php echo $value->local_council_name;?></option>
<?php }
}else{ ?>

<option value="Il-Fontana">Il-Fontana</option>
<?php }

die;	
}




add_action('wp_ajax_save_regions_data', 'save_regions_data');
add_action('wp_ajax_nopriv_save_regions_data', 'save_regions_data');
function save_regions_data(){
global $wpdb;
$region_name=  $_POST['region_name'];
$region_start_date=  $_POST['region_start_date'];
$region_name_en=  $_POST['region_name_en'];
$add_region_code=  $_POST['add_region_code'];
$tablename=$wpdb->prefix.'regions';

    $data=array(
        'region_name_mt' => $region_name,
        'regionen' =>$region_name_en,
        'start_date' =>$region_start_date,
        'region_code' =>$add_region_code,
);
$wpdb->insert( $tablename, $data);

echo json_encode(array('message'=>'saved'));
die;	
}

//delete_resource_pdf
add_action('wp_ajax_delete_resource_pdf', 'delete_resource_pdf');
add_action('wp_ajax_nopriv_delete_resource_pdf', 'delete_resource_pdf');
function delete_resource_pdf(){
global $wpdb; 
$region_id=  $_POST['region_id'];
    $tablename=$wpdb->prefix.'pdf_lifes';
    $wpdb->delete( $tablename, array( 'id' => $region_id ) );
    chmod($_POST['pdfname'], 0777);
    @unlink($_POST['pdfname']);
echo json_encode(array('message'=>'saved'));
die;	
}


add_action('wp_ajax_deleteRecordSingle_regions_data', 'deleteRecordSingle_regions_data');
add_action('wp_ajax_nopriv_deleteRecordSingle_regions_data', 'deleteRecordSingle_regions_data');
function deleteRecordSingle_regions_data(){
global $wpdb;
$region_id=  $_POST['region_id'];
    $tablename=$wpdb->prefix.'regions';
    $wpdb->delete( $tablename, array( 'id' => $region_id ) );

    $wpdb->delete( 'amu_local_council_list', array( 'region_Code' => $region_id ) );

    $wpdb->delete( 'amu_localities_list', array( 'region_Code' => $region_id ) );

echo json_encode(array('message'=>'saved'));
die;	
}


add_action('wp_ajax_edit_regions_data', 'edit_regions_data');
add_action('wp_ajax_nopriv_edit_regions_data', 'edit_regions_data');
function edit_regions_data(){
global $wpdb;
$region_id=  $_POST['region_id'];
$editRegion=  $_POST['editRegion'];
$edit_region_start_date=  $_POST['edit_region_start_date'];
$editregion_name_en=  $_POST['editregion_name_en'];
$edit_region_code=  $_POST['edit_region_code'];
    $tablename=$wpdb->prefix.'regions';

$wpdb->update( $tablename, array('region_name_mt' => $editRegion,'regionen' => $editregion_name_en,'start_date' => $edit_region_start_date,'region_code' => $edit_region_code),array('id'=>$region_id));
echo json_encode(array('message'=>'saved'));
die;	
}


if( function_exists('acf_add_options_page') ) {

    acf_add_options_page();

}


//locality ajax hooks start

add_action('wp_ajax_add_localities_data', 'add_localities_data');
add_action('wp_ajax_nopriv_add_localities_data', 'add_localities_data');
function add_localities_data(){
global $wpdb;

$locality_name=  $_POST['locality_name'];
$region_code=  $_POST['region_code'];
$locality_start_date=  $_POST['locality_start_date'];
$locality_end_date=  $_POST['locality_end_date'];
$local_council=  $_POST['local_council'];
$locality_code=  $_POST['locality_code'];

$tablename=$wpdb->prefix.'localities_list';
$local_council_list = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM amu_local_council_list WHERE id ='".$local_council."' ") );

    $data=array(
        'Locality_Name' => stripslashes($locality_name), 
        'region_Code' => $region_code,
        'local_council_name' =>$local_council_list->local_council_name,
        'Start_Date' => $locality_start_date,
        'End_Date' =>$locality_end_date,
        'localityCode' =>$locality_code,
        'local_council_code' =>$local_council,
);
$wpdb->insert( $tablename, $data);
echo json_encode(array('message'=>'saved'));
die;	
}

//get locality


add_action('wp_ajax_get_localities_data', 'get_localities_data');
add_action('wp_ajax_nopriv_get_localities_data', 'get_localities_data');
function get_localities_data(){
global $wpdb;
$locality_id=  $_POST['locality_id'];

$tablename=$wpdb->prefix.'localities_list';

$rowarr = $wpdb->get_row("SELECT * FROM  $tablename where locality_Code='".$locality_id."'" );
$result1 = $wpdb->get_results ( "SELECT * FROM  amu_regions order by id DESC" );
         ?>
         <div class="form-group">
							<label>locality name</label>
							<input name="edit_locality_name" id="edit_locality_name" type="text" class="form-control" required value="<?php echo $rowarr->Locality_Name;?>">
							<input type="hidden" name="edit_locality_id" id="edit_locality_id" value="<?php echo $locality_id;?>">
						</div>
						

						<div class="form-group">
							<label>Regions Name</label>
							<select name="edit_region_name_locality" id="edit_region_name_locality" class="form-control" style="max-width: 100%; !important">
							  <option>Select Regions</option>
							  <?php 
                               foreach ($result1 as $key => $regionsArr1) { 


                               	?>
							  <option  value="<?php echo $regionsArr1->id;?>" <?php if($regionsArr1->id==$rowarr->region_Code) echo 'selected="selected"'; ?>><?php echo $regionsArr1->region_name_mt;?></option>
                               <?php } ?>    
							</select>

						</div>
						<div class="form-group">
							<label>Start Date</label>
							<input type="text" name="edit_locality_start_date" id="edit_locality_start_date" class="form-control" value="<?php echo $rowarr->Start_Date;?>">
						</div>
						<div class="form-group">
							<label>End Date</label>
							<input type="text" name="edit_locality_end_date" id="edit_locality_end_date" class="form-control" value="<?php echo $rowarr->End_Date;?>">
						</div>	

						
        <?php   	
    
die;	
}

//update locality data

add_action('wp_ajax_edit_localities_data', 'edit_localities_data');
add_action('wp_ajax_nopriv_edit_localities_data', 'edit_localities_data');
function edit_localities_data(){
global $wpdb;

$edit_locality_name=  $_POST['edit_locality_name'];

$edit_region_name_locality=  $_POST['edit_region_name_locality'];
$edit_locality_start_date=  $_POST['edit_locality_start_date'];
$edit_locality_end_date=  $_POST['edit_locality_end_date'];
$edit_locality_id = $_POST['edit_locality_id'];

$edit_locality_id = $_POST['edit_locality_id'];
$localitycode = $_POST['localitycode'];
$local_council_name = $_POST['local_council_name'];
$tablename=$wpdb->prefix.'localities_list';

$wpdb->update( $tablename,
                array(
				 'Locality_Name' => stripslashes($edit_locality_name),
				 'region_Code' => $edit_region_name_locality,
				 'Start_Date' => $edit_locality_start_date,
				 'End_Date' => $edit_locality_end_date,
				 'localityCode' => $localitycode,
				 'local_council_name' => $local_council_name,

				),
				 array('locality_Code'=>$edit_locality_id));

echo json_encode(array('message'=>'saved'));
die;	
}


add_action('wp_ajax_deleteRecordSingle_locality_data', 'deleteRecordSingle_locality_data');
add_action('wp_ajax_nopriv_deleteRecordSingle_locality_data', 'deleteRecordSingle_locality_data');
function deleteRecordSingle_locality_data(){
global $wpdb;
$region_id=  $_POST['region_id'];
    $tablename=$wpdb->prefix.'locality';
    $wpdb->delete( 'amu_localities_list', array( 'locality_Code' => $region_id ) );
    $wpdb->delete('amu_local_council_list', array( 'locality_code' => $region_id ) );

echo json_encode(array('message'=>'saved'));
die;	
}

//import cutsom post type posts


add_action('wp_ajax_import_rehions_csv_data', 'import_rehions_csv_data');
add_action('wp_ajax_nopriv_import_rehions_csv_data', 'import_rehions_csv_data');
function import_rehions_csv_data(){
global $wpdb;
 $error = '';
 $total_line = '';
 session_start();
 if($_FILES['file']['name'] != '')
 {
  $allowed_extension = array('csv');
  $file_array = explode(".", $_FILES["file"]["name"]);
  $extension = end($file_array);
  if(in_array($extension, $allowed_extension))
  {
  $filename=$_FILES["file"]["tmp_name"];    


 if($_FILES["file"]["size"] > 0){
   $file = fopen($filename, "r");
    while (($getData = fgetcsv($file, 10000, ",")) !== FALSE){        
    	
    	if($getData[0]!="post title"){
          $regionsarr = explode(',', $getData[1]);
          $localityArr = explode(',', $getData[2]);
          $councilArr = explode(',', $getData[3]);
           $streetArr = explode(',', $getData[4]);
            $dateArr = explode(',', $getData[5]);
          //print_r($myArray);

   $tablename=$wpdb->prefix.'posts';
$rowarr = $wpdb->get_row("SELECT * FROM  $tablename where post_title='".$getData[0]."' AND post_type='locate-street' AND post_status='publish'" );
if($wpdb->num_rows==0){ 		
     $post_id = wp_insert_post(array(
		  'post_title'=>$getData[0], 
		  'post_type'=>'locate-street', 
		  'post_content'=>'',
		  'post_status' => 'publish',

		));


     $repeater_key = 'field_64f96a369fac3';
     $value = array();
 foreach ($regionsarr as $key => $valuecol) {
 $value[] = array( 
    'field_64f96b9d9fac4' => $valuecol,
    'field_6524fcc5cd850' =>$localityArr[$key],
    'field_6524fa783d641' => $councilArr[$key],
    'field_64f96c589fac7' => $streetArr[$key],
    'field_64f96c6a9fac8' =>strtotime($dateArr[$key]),
);
}

//print_r( $value);
/*$value =  array(
  array(
    'field_64f96b9d9fac4' => $valuecol,
    'field_6524fcc5cd850' =>$localityArr[$key],
    'field_6524fa783d641' => $councilArr[$key],
    'field_64f96c589fac7' => $streetArr[$key],
    'field_64f96c6a9fac8' =>$dateArr[$key],
  )
);*/

update_field($repeater_key, $value, $post_id);

}
 }
 }
 }
  }
  else
  {
   $error = 'Only CSV file format is allowed';
  }
 }
 else
 {
  $error = 'Please Select File';
 }

 if($error != '')
 {
  $output = array(
   'error'  => $error
  );
 } 
 else
 {
  $output = array(
   'success'  => true,
   'total_line' => ($total_line - 1)
  );
 }

 echo json_encode($output);

die;	
}


//local council ajax method start


add_action('wp_ajax_save_council_data', 'save_council_data');
add_action('wp_ajax_nopriv_save_council_data', 'save_council_data');
function save_council_data(){
global $wpdb;
$council_name=  $_POST['council_name'];
$start_date=  $_POST['start_date'];


$tablename=$wpdb->prefix.'local_list';
   $data=array(
        'local_council_name' => $council_name, 
        'start_date' => $start_date,
        
);
$wpdb->insert( 'amu_local_council_list', $data);




echo json_encode(array('message'=>'saved'));
die;	
}

//get_local_council_data

add_action('wp_ajax_get_local_council_data', 'get_local_council_data');
add_action('wp_ajax_nopriv_get_local_council_data', 'get_local_council_data');
function get_local_council_data(){
global $wpdb;
$locality_id=  $_POST['locality_id'];

$tablename=$wpdb->prefix.'local_council_list';

$rowarr = $wpdb->get_row("SELECT * FROM  $tablename where id='".$locality_id."'" );
$result1 = $wpdb->get_results ( "SELECT * FROM  amu_regions order by id DESC" );
         ?>
                        <div class="form-group">
							<label>Local Council Name</label>
							<input name="edit_local_name" id="edit_local_name" type="text" class="form-control" required value="<?php echo $rowarr->local_council_name;?>">
							<input type="hidden" name="edit_local_id" id="edit_local_id" value="<?php echo $locality_id;?>">
						</div>
						
						<div class="form-group">
							<label>Start Date</label>
							<input type="text" name="edit_local_start_date" id="edit_local_start_date" class="form-control" value="<?php echo $rowarr->start_date;?>">
						</div>	


						</div>
						

						
        <?php   	
    
die;	
}

//edit_local_data

add_action('wp_ajax_edit_local_data', 'edit_local_data');
add_action('wp_ajax_nopriv_edit_local_data', 'edit_local_data');
function edit_local_data(){
global $wpdb;

$local_name=  $_POST['local_name'];
$edit_local_start_date=  $_POST['edit_local_start_date'];
$edit_local_id = $_POST['edit_local_id'];

$tablename=$wpdb->prefix.'local_council_list';
$wpdb->update( $tablename, array('local_council_name' => $local_name,'start_date' => $edit_local_start_date),array('id'=>$edit_local_id));

echo json_encode(array('message'=>'saved'));
die;	
}

//deleteRecordSingle_council_data
add_action('wp_ajax_deleteRecordSingle_council_data', 'deleteRecordSingle_council_data');
add_action('wp_ajax_nopriv_deleteRecordSingle_council_data', 'deleteRecordSingle_council_data');
function deleteRecordSingle_council_data(){
global $wpdb;
$region_id=  $_POST['region_id'];
    $tablename=$wpdb->prefix.'local_council_list';
    $wpdb->delete( $tablename, array( 'id' => $region_id ) );

echo json_encode(array('message'=>'saved'));
die;	
}


add_action('wp_ajax_localities_import_excel', 'localities_import_excel');
add_action('wp_ajax_nopriv_localities_import_excel', 'localities_import_excel');
function localities_import_excel(){
global $wpdb;

$tablename=$wpdb->prefix.'localities_list';

$fileName = $_FILES['file']['name'];
$file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
$allowed_ext = ['xls','csv','xlsx'];
if(in_array($file_ext, $allowed_ext)) {
        
$inputFileNamePath = $_FILES['file']['tmp_name'];
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
$data = $spreadsheet->getActiveSheet()->toArray();

 foreach($data as $row) { 
$amu_locality = $wpdb->get_row( $wpdb->prepare('SELECT * FROM amu_localities_list WHERE Locality_Name ="'.$row['1'].'"' ) );

$amu_local = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM amu_local_council_list WHERE id ='".$row['2']."' ") );

if($amu_locality->Locality_Name==""){
           $data=array(
        'Locality_Name' => $row[1], 
        'region_Code' => $row[3],
        'local_council_name'=> $amu_local->local_council_name,
        'Start_Date' => $row[4],
         'End_Date' => $row[5],
        'localityCode'=> $row[0],
        'local_council_code'=> $row[2],
		);
		$wpdb->insert( $tablename, $data);

      }else{
      	$wpdb->update( 'amu_localities_list',
      	 array('Locality_Name' => $row[1],
      	 	'region_Code' =>$row[3],
      	 	'local_council_name' => $amu_local->local_council_name,
      	 	'Start_Date' => $row[4],
      	 	'End_Date' => $row[5],
      	 	'localityCode' => $row[0],
      	 	'local_council_code' => $row[2]),array('locality_Code'=> $amu_locality->locality_Code));
      }  
  }
 }	
echo json_encode(array('message'=>'saved'));
die;	
}



add_action('wp_ajax_regions_import_excel', 'regions_import_excel');
add_action('wp_ajax_nopriv_regions_import_excel', 'regions_import_excel');
function regions_import_excel(){
global $wpdb;
echo "rgtrgtrf";
$fileName = $_FILES['file']['name'];
$file_ext = pathinfo($fileName, PATHINFO_EXTENSION);
$allowed_ext = ['xls','csv','xlsx'];
if(in_array($file_ext, $allowed_ext)) {
        
$inputFileNamePath = $_FILES['file']['tmp_name'];
$spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
$data = $spreadsheet->getActiveSheet()->toArray();

 foreach($data as $row) { 
$amu_locality = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM amu_regions  WHERE regionen ='".$row[2]."'" ) );

if($amu_locality->regionen==""){

           $data=array(
	        'region_name_mt' => $row[1], 
	        'regionen' =>$row[2],
	        'start_date' => date('Y-m-d'),
			);
		$wpdb->insert('amu_regions', $data);

      }  
  }
 }	
echo json_encode(array('message'=>'saved11111'));
die;	
}
