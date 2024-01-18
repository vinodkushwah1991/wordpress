<?php function import_admin_menu_pdf() {
    add_menu_page(
        __( 'Upload PDF', 'my-textdomain' ),
        __( 'Upload PDF', 'my-textdomain' ),
        'manage_options',
        'pdf-import',
        'pdf_admin_page_import',
        'dashicons-media-document',
        100
    );
}
add_action( 'admin_menu', 'import_admin_menu_pdf' );
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



function pdf_admin_page_import() {
global $wpdb;


if(isset($_GET['import']) && $_GET['import']=='true'){
	$uploads = wp_get_upload_dir();

	$dirPath = $upload_dir['basedir'].'/pdf';
if ( $_FILES ) { 
    $files = $_FILES["pdf_files"];  
    foreach ($files['name'] as $key => $value) {    
         
            if ($files['name'][$key]) { 
                $file = array( 
                    'name' => $files['name'][$key],
                    'type' => $files['type'][$key], 
                    'tmp_name' => $files['tmp_name'][$key], 
                    'error' => $files['error'][$key],
                    'size' => $files['size'][$key]
                ); 
                 $fileTmpPath      = $files['tmp_name'][$key];
                $dest_path = $uploads['basedir'].'/pdf/'.$files['name'][$key];
                 $upload_path = $uploads['basedir'].'/pdf/';
                     if ( !is_writeable( $upload_path ) ) {
				        echo 'Unable to write to directory.';
				    }
				    if(move_uploaded_file($fileTmpPath, $dest_path)){
				         $uploads['baseurl'].'/pdf/'.$files['name'][$key];    
				         $wpdb->insert('amu_pdf_lifes', array(
						    'file_name' => $files['name'][$key],
						));
				    }

               // $_FILES = array ("pdf_files" => $file); 
               // foreach ($_FILES as $file => $array) {              
                   // $newupload = my_handle_attachment($file,$pid); 
               // }
            } 

           
        } 
         echo "PDF Successfully Uploaded";
    }
}
 ?>
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
 
 <h2 style="margin-bottom:10px">Upload PDF Files</h2>
  
  <div class="ath_container tile-container ">
    <div id="uploadStatus"></div>
   <form id="sample_form" method="POST" enctype="multipart/form-data" action="<?php echo home_url()?>/wp-admin/admin.php?page=pdf-import&import=true">
    <input type="file" name="pdf_files[]" id="fileUpload" multiple /> 

    <br>
    <br>
    <button  type="submit">Upload</button> <!-- Change function name -->
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
                        <h2>Manage PDF Lists</h2>
                    </div>
                    <div class="col-xs-7">
					
                    </div>
                </div>
            </div>
           				<table class="table table-striped table-hover" id="example">

                <thead>
                    <tr>
                        <th>#</th>
                        <th>Name</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                	 <?php   $result = $wpdb->get_results( " SELECT * FROM   amu_pdf_lifes ORDER BY id DESC"  );
                	 $uploads = wp_get_upload_dir();
                	 $kk= 1;
                         foreach ($result as $key => $value) {
                        
                	  ?>
                    <tr>
                        <td><?php echo  $kk;?></td>
                        <td><a href="<?php echo $uploads['baseurl']?>/pdf/<?php echo $value->file_name;?>"><?php echo $value->file_name;?></a></td>
                        <td>
                           <a action="<?php echo $uploads['baseurl']?>/pdf/<?php echo $value->file_name;?>" data-id="<?php echo $value->id;?>" href="#deleteEmployeeModal"  data-backdrop="false" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                        </td>
                    </tr>
                    <?php $kk++; } ?>
                    
                </tbody>
            </table>
           
        </div>
           <div id="deleteEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="deleteRecordSingle" id="deleteRecordSingle" method="POST">
					<div class="modal-header">						
						<h4 class="modal-title">Delete PDF</h4>
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
new DataTable('#example');
jQuery(document).ready(function($) {
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
		background: #299be4;
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
		color: #566787;
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