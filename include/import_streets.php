<?php function import_admin_menu_street() {
    add_menu_page(
        __( 'Research Spot', 'my-textdomain' ),
        __( 'Research Spot', 'my-textdomain' ),
        'manage_options',
        'research-import',
        'street_admin_page_import',
        'dashicons-schedule',
        100
    );
}
add_action( 'admin_menu', 'import_admin_menu_street' );
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



function street_admin_page_import() { ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

<?php 	
global $wpdb;

if($_GET['editPost']!=""){
   require_once( get_stylesheet_directory() . '/include/update_resource_spot.php' );
}

if($_GET['addPost']=="true"){
   require_once( get_stylesheet_directory() . '/include/add_resource_spot.php' );
}



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
            if($count > 0)
            {
                
             $post_title = sanitize_title($row['0']);
            $post_title = $post_title.$row['3'];
            $tablename=$wpdb->prefix.'research_spot';
            $rowarr = $wpdb->get_row("SELECT * FROM  $tablename where defut_street ='".$post_title."' " );
          if($rowarr->notice_number==""){
	            $wpdb->insert('amu_research_spot', array(
						    'street_name' => $row['0'],
						    'locality_name' => $row['1'],
						    'date' => $row['2'],
						    'notice_number' => $row['3'],
						    'pdf_name' => $row['4'],
						    'description' => $row['5'],
						    'publish_date' => date('Y-m-d'),
						    'defut_street'=> $post_title,
						));
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
if($_GET['editPost']=="" && $_GET['addPost']==""){
 ?>

 <br />
  <br />
  

  <div>
   
  <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css" rel="stylesheet">
    
     <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
      <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
       <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
 <br />
  <br />
 
 <h2 style="margin-bottom:10px">Import Research Spot Data </h2>
  
  <div id="ath_containerSec" class="ath_container tile-container" style="display:none;">
    <div id="uploadStatus"></div>
   <form id="sample_form" method="POST" enctype="multipart/form-data" action="<?php echo home_url()?>/wp-admin/admin.php?page=research-import&import=true">
    <input type="file" name="file" id="fileUpload" /> 
    <small>Please upload Excel,xls files only</small>

    <br>
    <br>
   <input type="submit" class="btn btn-primary mb-3" name="importSubmit" value="Import" style="margin-top: 22px;width: 200px;">
   <button type="button" id="importCenBtn" class="btn btn-primary mb-3" style="background-color:#e14d43 !important; margin-top: 22px;width: 200px;">Cancel</button>
    </form>
    <div>
        <table id="progressBarsContainer">
            <!-- Table rows will be dynamically added here -->
        </table>
    </div> <!-- Container for progress bars -->
    <br>
</div>

<div class="table-responsive">
        <div class="table-wrapper">
            <div class="table-title">
                <div class="row">
                    <div class="col-xs-5">
                        <h2>Manage Research Spot Data</h2>
                    </div>
                    <div class="col-xs-7">
                    	<a download href="/wp-content/uploads/2023/12/research-spot-sample.xlsx" style="background-color:#f2c205;" class="btn btn-success" data-toggle="modal" data-backdrop="false"><i class="material-icons">&#xE147;</i> <span>Download Sample File </span></a>

                    	 <a style="background-color:#626c69; color:#fff;" href="#" id="importResBtn"  class="btn btn-primary"><i class="material-icons">&#xE24D;</i> <span>Import Research Spot</span></a>
						<a href="<?php echo home_url();?>/wp-admin/admin.php?page=research-import&addPost=true" style="background-color:#e14d43;color: #fff;" class="btn btn-success"><i class="material-icons"></i> <span>Add New </span></a>
                    </div>
                </div>
            </div>
           				<table class="table table-striped table-hover" id="example">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Locate Region</th>
                        <th>Locate Locality</th>
                        <th>Locate Local Council </th>
                        <th>Locate Street</th>
                         <th>End Date</th>
                         <th></th>
			             <th></th>	
                    </tr>
                </thead>
                
            </table>
           
        </div>
           <div id="deleteEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="deleteRecordSingle" id="deleteRecordSingle" method="POST">
					<div class="modal-header">						
						<h4 class="modal-title">Delete Regions</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<input type="hidden" value="" name="deleteRecord" id="deleteRecord">
					<input type="hidden" value="" name="deleteRecordAction" id="deleteRecordAction">
					<div class="modal-body">					
						<p>Are you sure you want to delete these Records?</p>
						<p class="text-warning"><small>This action cannot be undone.</small></p>
					</div>
					<p class="status" style="color:green;"></p>
					<div class="modal-footer">
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" class="btn btn-danger" value="Delete">
					</div>
				</form>
			</div>
		</div>
	</div>
    </div> 

     <script> 
jQuery(document).ready(function($) {

jQuery("#importResBtn").click(function(){
 
 jQuery("#ath_containerSec").show('slow');

});

jQuery("#importCenBtn").click(function(){
 
 jQuery("#ath_containerSec").hide('slow');

});


$(document).ready(function(){
	var employeeData = $('#example').DataTable({
	"lengthChange": false,
	"processing":true,
	"serverSide":true,
	"order":[],
	"ajax":{
		  type:"POST",
		  dataType:"json",
		  url: '<?php echo  admin_url( 'admin-ajax.php' );?>',
		  data: {
                'action': 'research_spot_table_data',           
            },
		 
	},
	"columnDefs":[
		{
			"targets":[0, 3, 4],
			"orderable":false,
		},
	],
	"pageLength": 10
});	
   
});

jQuery(document).on('click','.delete', function() {
//jQuery(".delete").click(function(){ 
   var del_id=jQuery(this).attr('data-id');
   var action=jQuery(this).attr('action');
   jQuery("#deleteRecord").val(del_id);
   jQuery("#deleteRecordAction").val(action);
});
jQuery('form#deleteRecordSingle').on('submit', function(e) {
        jQuery('.status').show();
        region_id = jQuery('form#deleteRecordSingle #deleteRecord').val();
         pdfname = jQuery('form#deleteRecordSingle #deleteRecordAction').val();

        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo  admin_url( 'admin-ajax.php' );?>',
            data: {
                'action': 'delete_resource_pdf',
                'region_id': region_id,
                'pdfname' :pdfname,
            },
            success: function(data) {

                jQuery('.status').text('Record Deleted successfully...');
                 setTimeout(function () {
                      location.reload();
                 }, 2500);
                
            }
        });
        e.preventDefault();
    });

});
</script>
<?php } ?>
<style>
    body {
        color: #566787;
		background: #f5f5f5;
		font-family: 'Varela Round', sans-serif;
		font-size: 13px;
	}
    .table-responsive {
        margin: 30px 0;
    }
	.table-wrapper {
        min-width: 1000px;
        background: #fff;
        padding: 20px 25px;
		border-radius: 3px;
        box-shadow: 0 1px 1px rgba(0,0,0,.05);
    }
	.table-title {
		padding-bottom: 15px;
		background: #21b685;
		color: #fff;
		padding: 16px 30px;
		margin: -20px -25px 10px;
		border-radius: 3px 3px 0 0;
    }
    .table-title h2 {
		margin: 5px 0 0;
		font-size: 24px;
	}
	.table-title .btn {
		color: #fff;
		float: right;
		font-size: 13px;
		background: #fff;
		border: none;
		min-width: 50px;
		border-radius: 2px;
		border: none;
		outline: none !important;
		margin-left: 10px;
	}
	.table-title .btn:hover, .table-title .btn:focus {
        color: #566787;
		background: #f2f2f2;
	}
	.table-title .btn i {
		float: left;
		font-size: 21px;
		margin-right: 5px;
	}
	.table-title .btn span {
		float: left;
		margin-top: 2px;
	}
    table.table tr th, table.table tr td {
        border-color: #e9e9e9;
		padding: 12px 15px;
		vertical-align: middle;
    }
	table.table tr th:first-child {
		width: 60px;
	}
	table.table tr th:last-child {
		width: 100px;
	}
    table.table-striped tbody tr:nth-of-type(odd) {
    	background-color: #fcfcfc;
	}
	table.table-striped.table-hover tbody tr:hover {
		background: #f5f5f5;
	}
    table.table th i {
        font-size: 13px;
        margin: 0 5px;
        cursor: pointer;
    }	
    table.table td:last-child i {
		opacity: 0.9;
		font-size: 22px;
        margin: 0 5px;
    }
	table.table td a {
		font-weight: bold;
		color: #566787;
		display: inline-block;
		text-decoration: none;
	}
	table.table td a:hover {
		color: #2196F3;
	}
	table.table td a.settings {
        color: #2196F3;
    }
    table.table td a.delete {
        color: #F44336;
    }
    table.table td i {
        font-size: 19px;
    }
	table.table .avatar {
		border-radius: 50%;
		vertical-align: middle;
		margin-right: 10px;
	}
	.status {
		font-size: 30px;
		margin: 2px 2px 0 0;
		display: inline-block;
		vertical-align: middle;
		line-height: 10px;
	}
    .text-success {
        color: #10c469;
    }
    .text-info {
        color: #62c9e8;
    }
    .text-warning {
        color: #FFC107;
    }
    .text-danger {
        color: #ff5b5b;
    }
    .pagination {
        float: right;
        margin: 0 0 5px;
    }
    .pagination li a {
        border: none;
        font-size: 13px;
        min-width: 30px;
        min-height: 30px;
        color: #999;
        margin: 0 2px;
        line-height: 30px;
        border-radius: 2px !important;
        text-align: center;
        padding: 0 6px;
    }
    .pagination li a:hover {
        color: #666;
    }	
    .pagination li.active a, .pagination li.active a.page-link {
        background: #03A9F4;
    }
    .pagination li.active a:hover {        
        background: #0397d6;
    }
	.pagination li.disabled i {
        color: #ccc;
    }
    .pagination li i {
        font-size: 16px;
        padding-top: 6px
    }
    .hint-text {
        float: left;
        margin-top: 10px;
        font-size: 13px;
    }
</style>
<style> 


.ath_container {
    width: 99%;
    border: #d7d7d7 1px solid;
    border-radius: 5px;
    padding: 10px 20px 10px 20px;
    box-shadow: 0 0 5px rgba(0, 0, 0, .3);
    /* border-radius: 5px; */
}

#uploadStatus {
    color: #00e200;
}

.ath_container a {
    text-decoration: none;
    color: #2f20d1;
}

.ath_container a:hover {
    text-decoration: underline;
}

.ath_container img {
    height: auto;
    max-width: 100%;
    vertical-align: middle;
}


.ath_container .label {
    color: #565656;
    margin-bottom: 2px;
}



.ath_container .message {
    padding: 6px 20px;
    font-size: 1em;
    color: rgb(40, 40, 40);
    box-sizing: border-box;
    margin: 0px;
    border-radius: 3px;
    width: 100%;
    overflow: auto;
}

.ath_container .error {
    padding: 6px 20px;
    border-radius: 3px;
    background-color: #ffe7e7;
    border: 1px solid #e46b66;
    color: #dc0d24;
}

.ath_container .success {
    background-color: #48e0a4;
    border: #40cc94 1px solid;
    border-radius: 3px;
    color: #105b3d;
}

.ath_container .validation-message {
    color: #e20900;
}

.ath_container .font-bold {
    font-weight: bold;
}

.ath_container .display-none {
    display: none;
}

.ath_container .inline-block {
    display: inline-block;
}

.ath_container .float-right {
    float: right;
}

.ath_container .float-left {
    float: left;
}

.ath_container .text-center {
    text-align: center;
}

.ath_container .text-left {
    text-align: left;
}

.ath_container .text-right {
    text-align: right;
}

.ath_container .full-width {
    width: 100%;
}

.ath_container .cursor-pointer {
    cursor: pointer;
}

.ath_container .mr-20 {
    margin-right: 20px;
}

.ath_container .m-20 {
    margin: 20px;
}



.ath_container table {
    border-collapse: collapse;
    border-spacing: 0;
    width: 100%;
    border: 1px solid #ddd;
    margin-top: 20px;
}


.ath_container table th,
.ath_container table td {
    text-align: left;
    padding: 5px;
    border: 1px solid #ededed;
    width: 50%;
}

tr:nth-child(even) {
    background-color: #f2f2f2
}

.ath_container .progress {
    margin: 20px 0 0 0;
    width: 300px;
    border: 1px solid #ddd;
    padding: 5px;
    border-radius: 5px;
}

.ath_container .progress-bar {
    width: 0%;
    height: 24px;
    background-color: #4CAF50;
    margin-top: 15px;
    border-radius: 12px;
    text-align: center;
    color: #fff;
}

@media all and (max-width: 780px) {
    .ath_container {
        width: auto;
    }
}


.ath_container input,
.ath_container textarea,
.ath_container select {
    box-sizing: border-box;
    width: 200px;
    height: initial;
    padding: 8px 5px;
    border: 1px solid #9a9a9a;
    border-radius: 4px;
}

.ath_container input[type="checkbox"] {
    width: auto;
    vertical-align: text-bottom;
}

.ath_container textarea {
    width: 300px;
}

.ath_container select {
    display: initial;
    height: 30px;
    padding: 2px 5px;
}

.ath_container button, .ath_container input[type="submit"], .ath_container input[type="button"] {
	padding: 8px 57px;
	font-size: 1em;
	cursor: pointer;
	/* border-radius: 25px; */
	color: #ffffff;
	background-color: #21b685;
	border-color: unset;
}

.ath_container input[type=submit]:hover {
    background-color: #f7c027;
}

.ath_container label {
    display: block;
    color: #565656;
}

@media all and (max-width: 400px) {
    .ath_container {
        padding: 0px 20px;
    }

    .ath_container {
        width: auto;
    }

    .ath_container input,
    .ath_container textarea,
    .ath_container select {
        width: 100%;
    }
}

</style>
<?php }