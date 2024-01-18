<?php function import_admin_menu() {
    add_menu_page(
        __( 'Import Regions', 'my-textdomain' ),
        __( 'Import Regions', 'my-textdomain' ),
        'manage_options',
        'acf-import',
        'acf_admin_page_import',
        'dashicons-schedule',
        100
    );
}
add_action( 'admin_menu', 'import_admin_menu' );
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



function acf_admin_page_import() {
global $wpdb;

if(isset($_GET['import']) && $_GET['import']=='true'){
    // Allowed mime types 
    $fileName = $_FILES['file']['name'];
     $file_ext = pathinfo($fileName, PATHINFO_EXTENSION);

    $allowed_ext = ['xls','csv','xlsx'];
    if(in_array($file_ext, $allowed_ext))
    {
        $inputFileNamePath = $_FILES['file']['tmp_name'];
        $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($inputFileNamePath);
        $data = $spreadsheet->getActiveSheet()->toArray();
        $count = "0";
        foreach($data as $row)
        {
         
            if($row[0]!="" && $row[0]!="STREET_CODE")
            {

            
            	
               $publishdate=  date("Y-m-d", strtotime($row['4']) );
              $date_time=   $publishdate.' '.'23:59:59';
            $post_title =    sanitize_title($row['1'].$row[0]);
            $regionsarr = explode(',', $row['1']);
            $localityArr = explode(',', $row['2']);
            $councilArr = explode(',', $row['3']);
            $streetArr = explode(',', $row['4']);
            $dateArr = explode(',', $row['5']);
           $tablename=$wpdb->prefix.'posts';
           $rowarr = $wpdb->get_row("SELECT * FROM  $tablename where `post_title`='".$post_title."' AND post_type='locate-street' AND post_status='publish' " );


           if($wpdb->num_rows==0 && $post_title!=""){
              $post_id = wp_insert_post(array(
			  'post_title'=>$post_title, 
			  'post_type'=>'locate-street', 
			  'post_content'=>'',
			  'post_status' => 'publish',
			   'post_date' => $date_time,
		      'post_date_gmt' => get_gmt_from_date( $date_time ),

		));
      
       
       $rowlocality = $wpdb->get_row('SELECT * FROM   amu_localities_list   where  localityCode ="'.$row['2'].'"' );

      // print_r( $rowlocality);
       $rowregionsList = $wpdb->get_row("SELECT * FROM  amu_regions   where id='".$rowlocality->region_Code."'" );
       // print_r($rowlocality );
      // echo "insert";
         $repeater_key = 'field_64f96a369fac3';
         $value = array();
            $rowregionsList->region_name_mt;
		 /*foreach ($regionsarr as $key => $valuecol) {
		 $value[] = array( 
		    'field_64f96b9d9fac4' => $valuecol,
		    'field_6524fcc5cd850' =>$localityArr[$key],
		    'field_6524fa783d641' => $councilArr[$key],
		    'field_64f96c589fac7' => $streetArr[$key],
		    'field_64f96c6a9fac8' =>strtotime($dateArr[$key]),
		);
		}*/
        if( $row['1']!=""){
		$value[] = array( 
		    'field_64f96b9d9fac4' => $rowregionsList->region_name_mt,
		    'field_6524fcc5cd850' =>$rowlocality->Locality_Name,
		    'field_6524fa783d641' => $rowlocality->local_council_name,
		    'field_64f96c589fac7' => $row['1'],
		    'field_64f96c6a9fac8' =>'',
		);
		 update_field($repeater_key, $value, $post_id);
	}
	}else{

		$my_post = array(

   'ID' =>  $rowarr->ID,
  'post_type'=>'locate-street',
  'post_content'  => "",
  'post_status'   => 'publish',

);

wp_update_post( $my_post );

$rowlocality = $wpdb->get_row("SELECT * FROM   amu_localities_list   where  localityCode ='".$row['2']."'" );
       $rowregionsList = $wpdb->get_row("SELECT * FROM  amu_regions   where id='".$rowlocality->region_Code."'" );
      // print_r($rowlocality );
      // echo "update";
         $repeater_key = 'field_64f96a369fac3';
         $value = array();
            $rowregionsList->region_name_mt;
            
 if( $row['1']!=""){
		$value[] = array( 
		    'field_64f96b9d9fac4' => $rowregionsList->region_name_mt,
		    'field_6524fcc5cd850' =>$rowlocality->Locality_Name,
		    'field_6524fa783d641' => $rowlocality->local_council_name,
		    'field_64f96c589fac7' => $row['1'],
		    'field_64f96c6a9fac8' =>'',
		);
		 update_field($repeater_key, $value, $rowarr->ID);
	}
	}



            }
            else
            {
                $count = "1";
            }
        }
       
       
       echo "Data Successfully Imported";
        
}

}

 ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
 <br />
  <br />
  <div class="container1" style="display:none;">
   <br />
   <div class="panel panel-default">
    <div class="panel-heading">
     <h3 class="panel-title">Import CSV File Data</h3>
    </div>
      <div class="panel-body">
       <span id="message"></span>
       <form id="sample_form" method="POST" enctype="multipart/form-data" class="form-horizontal1">
        <div class="form-group">
         <label class="control-label">Select CSV File</label>
         <input type="file" name="file" id="file" />
        </div>
        <div class="form-group">
         <input type="hidden" name="hidden_field" value="1" />
         <input type="submit" name="import" id="import" class="btn btn-info" value="Import" />
        </div>
       </form>
       <div class="form-group" id="process" style="display:none;">
        <div class="progress">
         <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
          <span id="process_data">0</span> - <span id="total_data">0</span>
         </div>
        </div>
       </div>
      </div>
     </div>
  </div>


  <div class="row p-3" style="width:50%;">
   
    <!-- Excel file upload form -->
    <div class="col-md-12" id="importFrm">
        <form class="row g-3" action="<?php echo home_url()?>/wp-admin/admin.php?page=acf-import&import=true" method="post" enctype="multipart/form-data">
            <div class="col-auto">
                <label for="fileInput" class="visually-hidden">File</label>
                <input type="file" class="form-control" name="file" id="fileInput" />
            </div>
            <div class="col-auto">
                <input type="submit" class="btn btn-primary mb-3" name="importSubmit" value="Import" style="margin-top: 22px;width: 200px;">
            </div>
        </form>
    </div>

  <script>
 
 $(document).ready(function(){

  var clear_timer;

  $('#sample_form').on('submit', function(event){
   $('#message').html('');
   event.preventDefault();
       var fd = new FormData();
    var file = jQuery(document).find('input[type="file"]');
    var individual_file = file[0].files[0];
    fd.append("file", individual_file);
    fd.append('action', 'import_rehions_csv_data')

   $.ajax({
    url: '<?php echo  admin_url( 'admin-ajax.php' );?>',
    method:"POST",
     data: fd,
    dataType:"json",
    contentType:false,
    cache:false,
    processData:false,
    beforeSend:function(){
     $('#import').attr('disabled','disabled');
     $('#import').val('Importing');
    },
    success:function(data)
    {
     if(data.success)
     {
     	$('#process').css('display', 'none');
      $('#file').val('');
      $('#message').html('<div class="alert alert-success">Data Successfully Imported</div>');
      $('#import').attr('disabled',false);
      $('#import').val('Import');

     // $('#total_data').text(data.total_line);

     // start_import();

      //clear_timer = setInterval(get_import_data, 2000);

      //$('#message').html('<div class="alert alert-success">CSV File Uploaded</div>');
     }
     if(data.error)
     {
      $('#message').html('<div class="alert alert-danger">'+data.error+'</div>');
      $('#import').attr('disabled',false);
      $('#import').val('Import');
     }
    }
   })
  });

  function start_import()
  {
   $('#process').css('display', 'block');
   $.ajax({
    url:"import.php",
    success:function()
    {

    }
   })
  }

  function get_import_data()
  {
   $.ajax({
    url:"process.php",
    success:function(data)
    {
     var total_data = $('#total_data').text();
     var width = Math.round((data/total_data)*100);
     $('#process_data').text(data);
     $('.progress-bar').css('width', width + '%');
     if(width >= 100)
     {
      clearInterval(clear_timer);
      $('#process').css('display', 'none');
      $('#file').val('');
      $('#message').html('<div class="alert alert-success">Data Successfully Imported</div>');
      $('#import').attr('disabled',false);
      $('#import').val('Import');
     }
    }
   })
  }

 });
</script>
<?php }