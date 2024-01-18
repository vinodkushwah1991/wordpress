<?php 
if($_POST['streetName']!=""){


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
              $fileNamearr[]=    $files['name'][$key];
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

            } 

           
        } 
        
    }



 $wpdb->insert('amu_research_spot', array(
					    'street_name' => $_POST['streetName'],
					    'locality_name' => $_POST['locality'],
					    'date' =>  date('Y-m-d'),
					    'notice_number' => $_POST['noticNo'],
					    'pdf_name' => $fileNamearr[0],
					    'description' => $_POST['naming'],
					    'publish_date' => date('Y-m-d'),
					    'defut_street'=>$_POST['streetName'],
					)); ?>
<div class="alert alert-success">
  <strong>Success!</strong> Record Successfully insert.
</div>
 <?php // echo "Record Successfully insert";

}
?>


<div id="employeeModal" style="margin-top: 6rem;background-color: white;padding: 55px;">

	<div class="modal-dialog1">
		<form method="post" id="employeeForm" method="POST" enctype="multipart/form-data">
			<div class="modal-content1">
				<div class="modal-header1">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Add Research Spot</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="name" class="control-label">Street Name</label>
						<input type="text" class="form-control" id="streetName" name="streetName" placeholder="Street Name" required>			
					</div>
					<div class="form-group">
						<label for="age" class="control-label">Locality</label>							
						<input type="type" class="form-control" id="locality" name="locality" placeholder="Locality">							
					</div>	   	
					<div class="form-group">
						<label for="lastname" class="control-label">Government Notice No</label>							
						<input type="text" class="form-control"  id="noticNo" name="noticNo" placeholder="Government Notice No" required>							
					</div>	 
					<div class="form-group">
						<label for="address" class="control-label">Upload PDF</label>							
						<input type="file" class="form-control" id="pdfArr" name="pdf_files[]" placeholder="Upload PDF">						
					</div>
					<div class="form-group">
						<label for="lastname" class="control-label">Description</label>							
						<textarea class="form-control" rows="5" id="naming" name="naming"></textarea>				
					</div>						
				</div>
				<div class="modal-footer" style="text-align:left;">

					<input type="submit" name="save" id="save" class="btn btn-info" value="Save" />
				</div>
			</div>
		</form>
	</div>
</div>

