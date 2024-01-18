<?php function my_admin_menu() {
    add_menu_page(
        __( 'Acf Regions', 'my-textdomain' ),
        __( 'Acf Regions', 'my-textdomain' ),
        'manage_options',
        'acf-regions',
        'acf_admin_page_regions',
        'dashicons-schedule',
        100
    );
}
add_action( 'admin_menu', 'my_admin_menu' );

function acf_admin_page_regions() {
	global $wpdb;

    ?>
     <?php if($_GET['council']=='' && $_GET['locality']=='' && $_GET['LocalCouncil']==''){
       $btnactive = 'btn-primary';
     }else{
     	$btnactive = 'btn-default';
     }
     if($_GET['locality']==''){
     	$btnactive2 = 'btn-default';
     }else{
     	$btnactive2 = 'btn-primary';
     }

     if($_GET['LocalCouncil']==''){
     	$btnactive3 = 'btn-default';
     }else{
     	$btnactive3 = 'btn-primary';
     }
     	?>
    <h1> <?php esc_html_e( 'Manage Regions and locality data', 'my-plugin-textdomain' ); ?> </h1>
    <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
            <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css" rel="stylesheet">
    
     <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.0/js/bootstrap.min.js"></script>
      <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
       <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>

  <link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>


<div class="col-lg-12 col-sm-12">
   
    <div class="btn-pref btn-group btn-group-justified btn-group-lg" role="group" aria-label="...">
        <div class="btn-group" role="group">
            <a type="button" id="stars" class="btn <?php echo $btnactive;?>" href="<?php echo home_url()?>/wp-admin/admin.php?page=acf-regions"><span class="glyphicon glyphicon-star" aria-hidden="true"></span>
                <div class="hidden-xs">REGION</div>
            </a>
        </div>
        <div class="btn-group" role="group">
            <a type="button" id="favorites" class="btn <?php echo $btnactive2;?>" href="<?php echo home_url()?>/wp-admin/admin.php?page=acf-regions&locality=true"><span class="glyphicon glyphicon-heart" aria-hidden="true"></span>
                <div class="hidden-xs">LOCALITY</div>
            </a>
        </div>

          <div class="btn-group" role="group">
            <a type="button" id="favorites" class="btn <?php echo $btnactive3;?>" href="<?php echo home_url()?>/wp-admin/admin.php?page=acf-regions&LocalCouncil=true"><span class="glyphicon glyphicon-map-marker" aria-hidden="true"></span>
                <div class="hidden-xs">LOCAL COUNCIL</div>
            </a>
        </div>

         

        
    </div>

        <div class="well">
      <div class="tab-content">
      	 <?php if($_GET['council']=='' && $_GET['locality']=='' && $_GET['LocalCouncil']==''){?>
        <div class="tab-pane fade in active" id="tab1">
           <div class="">
		<div class="table-responsive">
			<div class="table-wrapper">
				<div class="table-title">
					<div class="row">
						<div class="col-xs-4">
							<h2>Manage <b>Regions</b></h2>

						</div>
						<div class="col-xs-8">
							<a download href="/wp-content/uploads/2023/12/Region.xlsx" style="background-color:#f2c205;" class="btn btn-success" data-toggle="modal" data-backdrop="false"><i class="material-icons">&#xE147;</i> <span>Download Sample File </span></a>
							 <a style="background-color:#21b685;" href="#importExcelRegion"  data-toggle="modal" data-backdrop="false" class="btn btn-primary"><i class="material-icons">&#xE24D;</i> <span>Import Regions</span></a>
							<a href="#addEmployeeModal" style="background-color:#e14d43;" class="btn btn-success" data-toggle="modal" data-backdrop="false"><i class="material-icons">&#xE147;</i> <span>Add New </span></a>

						</div>
					</div>
				</div>
				<table class="table table-striped table-hover" id="example">
					<thead>
						<tr>
							<th>Region Code</th>
							<th>Region Name MT </th>
							<th>Region Name EN </th>
							<th>Created Date </th>							
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $result = $wpdb->get_results ( "SELECT * FROM  amu_regions order by region_code ASC" );
						$count =1;
                         foreach ($result as $key => $regionsArr) {?>
						<tr>
							
							<td><?php echo $regionsArr->region_code;?></td>
							<td><?php echo $regionsArr->region_name_mt;?></td>
							<td><?php echo $regionsArr->regionen;?></td>
							<td><?php echo $regionsArr->start_date;?></td>
														<td>
								<a region_code="<?php echo $regionsArr->region_code;?>" data-id="<?php echo $regionsArr->id;?>" end_date="<?php echo $regionsArr->regionen;?>" start_date="<?php echo $regionsArr->start_date;?>" title="<?php echo $regionsArr->region_name_mt;?>" href="#editEmployeeModal" class="edit editRegion" data-toggle="modal" data-backdrop="false"><i class="material-icons" data-toggle="tooltip" title="Edit ">&#xE254;</i></a>
								<a action="deleteRecordSingle_regions_data" data-id="<?php echo $regionsArr->id;?>" href="#deleteEmployeeModal" class="delete" data-toggle="modal" data-backdrop="false"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
							</td>
						</tr>
					<?php $count++;  } ?>
						
					</tbody>
				</table>
				
			</div>
		</div>        
    </div>


    <div id="importExcelRegion" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="region_excel" id="locality_excel" method="POST" enctype="multipart/form-data">
					<div class="modal-header">						
						<h4 class="modal-title">Import Regions</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Import Excel File</label>
							<input type="file" name="locality_excel" id="locality_excel" value="" class="form-control">
						</div>							
											
					</div>
					
					<div class="modal-footer">
						<p class="status" style="color:green;"></p>
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" style="background-color:#e14d43;" class="btn btn-success" value="Import">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Edit Modal HTML -->
	
	<div id="addEmployeeModal" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="addReginos" id="addReginos" method="POST">
					<div class="modal-header">						
						<h4 class="modal-title">Add Regions</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Region Name MT</label>
							<input type="text" name="region_name" id="region_name" class="form-control" required>
						</div>
						<div class="form-group">
							<label>Region Name EN</label>
							<input type="text" name="region_name_en" id="region_name_en" class="form-control" required>
						</div>

						<div class="form-group">
							<label>Date</label>
							<input type="text" name="region_start_date" id="region_start_date" class="form-control" required>
						</div>

						<div class="form-group">
							<label>Region Code</label>
							<input type="text" name="add_region_code" id="add_region_code" class="form-control" required>
						</div>
												
											
					</div>
					
					<div class="modal-footer">
						<p class="status" style="color:green;"></p>
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" style="background-color:#e14d43;" class="btn btn-success" value="Import">
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Edit Modal HTML -->
	<div id="editEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="editRegionData" id="editRegionData"  method="POST">
					<div class="modal-header">						
						<h4 class="modal-title">Edit Regions</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Region Name</label>
							<input type="text" name="editRegion" id="editRegion" class="form-control" required value="">
							<input type="hidden" value="" name="regionId" id="regionId">
						</div>
						<div class="form-group">
							<label>Region Name EN</label>
							<input type="text" name="editregion_name_en" id="editregion_name_en" class="form-control" required>
						</div>
						
						<div class="form-group">
							<label>Start Date</label>
							<input type="text" name="edit_region_start_date" id="edit_region_start_date" class="form-control" required>
						</div>

						<div class="form-group">
							<label>Region Code</label>
							<input type="text" name="edit_region_code" id="edit_region_code" class="form-control" required>
						</div>
							
										
					</div>
					<div class="modal-footer">
						<p class="status" style="color:green;"></p>
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" style="background-color:#e14d43;"  class="btn btn-info" value="Save">
					</div>
				</form>
			</div>
		</div>
	</div>
	
        </div>
    <?php } ?>
        <?php if($_GET['locality']!=""){?>
        <div class="tab-pane fade in active" id="tab2">
           <div class="">
		<div class="table-responsive">
			<div class="table-wrapper">
				<div class="table-title">
					<div class="row">
						<div class="col-xs-4">
							<h2>Manage <b>locality</b></h2>
						</div>
						<div class="col-xs-8">
							<a download href="/wp-content/uploads/2023/12/Locality-sample.xlsx" style="background-color:#f2c205;" class="btn btn-success" data-toggle="modal" data-backdrop="false"><i class="material-icons">&#xE147;</i> <span>Download Sample File </span></a>
							 <a style="background-color:#21b685;" href="#importExcelLocalities"  data-toggle="modal" data-backdrop="false" class="btn btn-primary"><i class="material-icons">&#xE24D;</i> <span>Import locality</span></a>
							<a href="#addEmployeeModal" style="background-color:#e14d43;" class="btn btn-success" data-toggle="modal" data-backdrop="false"><i class="material-icons">&#xE147;</i> <span>Add New </span></a>
						</div>
					</div>
				</div>
				<table class="table table-striped table-hover" id="example2">
					<thead>
						<tr>
							<th>Locality Code</th>
							<th>locality Name</th>
							<th>Local Council Name</th>
							<th>Region Name</th>							
							<th>Start Date</th>
							<th>End Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $result = $wpdb->get_results ( "SELECT * FROM  amu_localities_list order by locality_Code ASC" );
						$count =1;
                         foreach ($result as $key => $localitiesArr) {
                           $amu_regions = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM amu_regions WHERE id = $localitiesArr->region_Code" ) );
                          
                           ?>
						<tr>
							<td>
								<?php echo $localitiesArr->localityCode;?>
							</td>
							<td><?php echo $localitiesArr->Locality_Name;?></td>
							<td><?php echo $localitiesArr->local_council_name;?></td>
							<td><?php echo $amu_regions->region_name_mt;?></td>
							<td><?php echo $localitiesArr->Start_Date;?></td>
							<td><?php echo $localitiesArr->End_Date;?></td>
							<td>
								<button 
                                 localityCode="<?php echo $localitiesArr->localityCode;?>"
                                 Locality_Name="<?php echo $localitiesArr->Locality_Name;?>"
                                 amu_regions="<?php echo $amu_regions->id;?>"
                                 Start_Date="<?php echo $localitiesArr->Start_Date;?>"
                                  End_Date="<?php echo $localitiesArr->End_Date;?>"
                                   local_council_name="<?php echo $localitiesArr->local_council_name;?>"
								data-id="<?php echo $localitiesArr->locality_Code;?>" class="edit editLocalityBtn edit1"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></button>
								<a action="deleteRecordSingle_locality_data" data-id="<?php echo $localitiesArr->locality_Code;?>" href="#deleteEmployeeModal"  data-backdrop="false" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
							</td>
						</tr>
						<?php $count++;  } ?>
						
					</tbody>
				</table>
				
			</div>
		</div>        
    </div>

    <div id="importExcelLocalities" class="modal fade" >
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="locality_excel" id="locality_excel" method="POST" enctype="multipart/form-data">
					<div class="modal-header">						
						<h4 class="modal-title">Import Locality</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Import Excel File</label>
							<input type="file" name="locality_excel" id="locality_excel" value="" class="form-control">
						</div>							
											
					</div>
					
					<div class="modal-footer">
						<p class="status" style="color:green;"></p>
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" style="background-color:#e14d43;" class="btn btn-success" value="Import">
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- Edit Modal HTML -->
	<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="localityadd" id="localityadd" method="POST">
					<div class="modal-header">						
						<h4 class="modal-title">Add locality</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>locality name</label>
							<input name="locality_name" id="locality_name" type="text" class="form-control" required>
						</div>

						<div class="form-group">
							<label>locality code</label>
							<input name="locality_code" id="locality_code" type="text" class="form-control" required>
						</div>

						<div class="form-group">
							<label>Regions Name</label>
							<select name="region_code" id="region_code" class="form-control" style="max-width: 100%; !important">
							  <option>Select Regions</option>
							  <?php $result = $wpdb->get_results ( "SELECT * FROM  amu_regions order by id ASC" );
                               foreach ($result as $key => $regionsArr) {?>
							  <option value="<?php echo $regionsArr->id;?>"><?php echo $regionsArr->region_name_mt;?></option>
                               <?php } ?>    
							</select>

						</div>	
                       <?php $result = $wpdb->get_results ( "SELECT * FROM  amu_local_council_list order by id ASC" );
						$count =1;
                         	?>
						<div class="form-group">
							<label>Local Council Name</label>
							<select name="local_council" id="local_council" class="form-control" style="max-width: 100%; !important">
							  <option>Select Local Council</option>
                              <?php foreach ($result as $key => $local_councilArr) {?>
							  <option value="<?php echo $local_councilArr->id;?>"><?php echo $local_councilArr->local_council_name;?></option>
                               <?php } ?>    
							</select>

						</div>	

						<div class="form-group">
							<label>Start Date</label>
							<input type="text" name="locality_start_date" id="locality_start_date" class="form-control" required>
						</div>
						<div class="form-group">
							<label>End Date</label>
							<input type="text" name="locality_end_date" id="locality_end_date" class="form-control" required>
						</div>				
					</div>
					<div class="modal-footer">
						<p class="status" style="color:green;"></p>
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" style="background-color:#e14d43;" class="btn btn-success" value="Add">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Edit Modal HTML -->
	<div id="editEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="editLocality" id="editLocality" method="POST">
					<div class="modal-header">						
						<h4 class="modal-title">Edit Locality</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body" id="editLocalityBody111">					
						 <div class="form-group">
							<label>locality name</label>
							<input name="edit_locality_name" id="edit_locality_name" type="text" class="form-control" required value="<?php echo $rowarr->Locality_Name;?>">
							<input type="hidden" name="edit_locality_id" id="edit_locality_id" value="">
						</div>
						<div class="form-group">
							<label>locality code</label>
							<input name="edit_locality_code" id="edit_locality_code" type="text" class="form-control"  value="">
						</div>

						<div class="form-group">
							<label>Regions Name</label>
							<select name="edit_region_name_locality" id="edit_region_name_locality" class="form-control" style="max-width: 100%; !important">
							  <option>Select Regions</option>
							  	 <?php $result = $wpdb->get_results ( "SELECT * FROM  amu_regions order by id ASC" );
                               foreach ($result as $key => $regionsArr) {?>
							  <option value="<?php echo $regionsArr->id;?>"><?php echo $regionsArr->region_name_mt;?></option>
                               <?php } ?>   
							</select>

						</div>

						  <?php $result = $wpdb->get_results ( "SELECT * FROM  amu_local_council_list order by id ASC" );
						$count =1;
                         	?>
						<div class="form-group">
							<label>Local Council Name</label>
							<select name="edit_local_council" id="edit_local_council" class="form-control" style="max-width: 100%; !important">
							  <option>Select Local Council</option>
                              <?php foreach ($result as $key => $local_councilArr) {?>
							  <option value="<?php echo $local_councilArr->local_council_name;?>"><?php echo $local_councilArr->local_council_name;?></option>
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
					</div>
					<div class="modal-footer">
						<p class="status" style="color:green;"></p>
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" style="background-color:#e14d43;" class="btn btn-info" value="Update">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Delete Modal HTML -->
	
     
        </div>
        <?php } ?>


  <?php if($_GET['LocalCouncil']!=""){?>
        <div class="tab-pane fade in active" id="tab2">
           <div class="">
		<div class="table-responsive">
			<div class="table-wrapper">
				<div class="table-title">
					<div class="row">
						<div class="col-xs-4">
							<h2>Manage <b>Local Council</b></h2>
						</div>
						<div class="col-xs-8">
							<a download href="/wp-content/uploads/2023/12/local_council-sample.xlsx" style="background-color:#f2c205;" class="btn btn-success" data-toggle="modal" data-backdrop="false"><i class="material-icons">&#xE147;</i> <span>Download Sample File </span></a>
							 <a style="background-color:#21b685;" href="#" class="btn btn-primary"><i class="material-icons">&#xE24D;</i> <span>Import Local Council</span></a>	
							<a href="#addEmployeeModal" style="background-color:#e14d43;" class="btn btn-success" data-toggle="modal" data-backdrop="false"><i class="material-icons">&#xE147;</i> <span>Add New </span></a>
						</div>
					</div>
				</div>
				<table class="table table-striped table-hover" id="example3">
					<thead>
						<tr>
							<th>Local Council Code </th>
							<th>Local Council</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php $result = $wpdb->get_results ( "SELECT * FROM  amu_local_council_list order by id DESC" );
						$count =1;
                         foreach ($result as $key => $local_council) {
                         	?>

						<tr>
							<td>
								<?php echo '00'.$local_council->id;?>
							</td>
							<td><?php echo $local_council->local_council_name;?></td>
							<td><?php echo $local_council->start_date;?></td>
							<td><?php echo $local_council->end_date;?></td>

							<td>

								<button data-id="<?php echo $local_council->id;?>" class="edit editlocalBtn edit1"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></button>


								<a action="deleteRecordSingle_council_data" data-id="<?php echo $local_council->id;?>" href="#deleteEmployeeModal"  data-backdrop="false" class="delete" data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
							</td>
						</tr>
						<?php $count++;  } ?>
						
					</tbody>
				</table>
				
			</div>
		</div>        
    </div>
	<!-- Edit Modal HTML -->
	<div id="addEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="counciladd" id="counciladd" method="POST">
					<div class="modal-header">						
						<h4 class="modal-title">Add Local Council</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body">					
						<div class="form-group">
							<label>Local Council Name</label>
							<input name="council_name" id="council_name" type="text" class="form-control" required>
						</div>

						<div class="form-group">
							<label>Start Date</label>
							<input type="text" name="locality_start_date" id="locality_start_date" class="form-control" required>
						</div>						
					
										
					</div>
					<div class="modal-footer">
						<p class="status" style="color:green;"></p>
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" style="background-color:#e14d43;" class="btn btn-success" value="Add">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Edit Modal HTML -->
	<div id="editEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="editlocal" id="editlocal" method="POST">
					<div class="modal-header">						
						<h4 class="modal-title">Edit Local Council</h4>
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
					</div>
					<div class="modal-body" id="editLocalityBody">					
										
					</div>
					<div class="modal-footer">
						<p class="status" style="color:green;"></p>
						<input type="button" class="btn btn-default" data-dismiss="modal" value="Cancel">
						<input type="submit" style="background-color:#e14d43;" class="btn btn-info" value="Update">
					</div>
				</form>
			</div>
		</div>
	</div>
	<!-- Delete Modal HTML -->
	
     
        </div>
        <?php } ?>
           
      </div>
    </div>
    
    </div>
    <div id="deleteEmployeeModal" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<form name="deleteRecordSingle" id="deleteRecordSingle" method="POST">
					<div class="modal-header">						
						<h4 class="modal-title">Delete Record</h4>
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

	<script>
  $( function() {
    $( "#region_start_date" ).datepicker({ dateFormat: 'dd/mm/yy' });
     $( "#region_end_date" ).datepicker({ dateFormat: 'dd/mm/yy' });
     $( "#edit_region_start_date" ).datepicker({ dateFormat: 'dd/mm/yy' });
     $( "#edit_region_end_date" ).datepicker({ dateFormat: 'dd/mm/yy' });


     //localities
 $( "#locality_start_date" ).datepicker({ dateFormat: 'dd/mm/yy' });
  $( "#locality_end_date" ).datepicker({ dateFormat: 'dd/mm/yy' });

   $( "#edit_locality_start_date" ).datepicker({ dateFormat: 'dd/mm/yy' });
  $( "#edit_locality_end_date" ).datepicker({ dateFormat: 'dd/mm/yy' });
     
  } );
  </script>


    <script> 
new DataTable('#example');
new DataTable('#example2');
new DataTable('#example3');

jQuery(document).ready(function($) {
jQuery(document).on('click','.delete', function() {
//jQuery(".delete").click(function(){ 
   var del_id=jQuery(this).attr('data-id');
   var action=jQuery(this).attr('action');
   jQuery("#deleteRecord").val(del_id);
   jQuery("#deleteRecordAction").val(action);
});

jQuery(document).on('click','.editRegion', function() {
//jQuery(".editRegion").click(function(){ 
  var del_id=jQuery(this).attr('data-id');
  var start_date=jQuery(this).attr('start_date');
   var end_date=jQuery(this).attr('end_date');
  var name=jQuery(this).attr('title');
 var region_code =jQuery(this).attr('region_code');

 jQuery("#regionId").val(del_id);
 jQuery("#edit_region_start_date").val(start_date);
 jQuery("#editregion_name_en").val(end_date);
 jQuery("#editRegion").val(name);

 jQuery("#edit_region_code").val(region_code);
});

jQuery('form#editRegionData').on('submit', function(e) {
        jQuery('.status').show();

        region_id = jQuery('form#editRegionData #regionId').val();
        editRegion = jQuery('form#editRegionData #editRegion').val();
        edit_region_start_date = jQuery('form#editRegionData #edit_region_start_date').val();
        editregion_name_en = jQuery('form#editRegionData #editregion_name_en').val();
        edit_region_code = jQuery('form#editRegionData #edit_region_code').val();

        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo  admin_url( 'admin-ajax.php' );?>',
            data: {
                'action': 'edit_regions_data',
                'region_id': region_id,
                'editRegion': editRegion,
                'edit_region_start_date': edit_region_start_date,
                 'editregion_name_en': editregion_name_en,
                 'edit_region_code': edit_region_code,
            },
            success: function(data) {

                jQuery('.status').text('Record Updated successfully!....');
                 setTimeout(function () {
                      location.reload();
                 }, 2500);
                
            }
        });
        e.preventDefault();
    });


jQuery('form#deleteRecordSingle').on('submit', function(e) {
        jQuery('.status').show();
        region_id = jQuery('form#deleteRecordSingle #deleteRecord').val();
         action = jQuery('form#deleteRecordSingle #deleteRecordAction').val();

        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo  admin_url( 'admin-ajax.php' );?>',
            data: {
                'action': action,
                'region_id': region_id,
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


    // Perform AJAX login/register on form submit
    jQuery('form#addReginos').on('submit', function(e) {
        jQuery('.status').show();

          var  region_name = jQuery('form#addReginos #region_name').val();
          var region_start_date = jQuery('form#addReginos #region_start_date').val();
          var  region_name_en = jQuery('form#addReginos #region_name_en').val();   
          var add_region_code  = jQuery('form#addReginos #add_region_code').val(); 

        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo  admin_url( 'admin-ajax.php' );?>',
            data: {
                'action': 'save_regions_data',
                'region_name': region_name,
                'region_start_date': region_start_date,
                'region_name_en': region_name_en,
                'add_region_code': add_region_code,
            },
            success: function(data) {

                jQuery('.status').text('Record Inserted successfully...');
                 setTimeout(function () {
                      location.reload();
                 }, 2500);
                
            }
        });
        e.preventDefault();
    });

  //locality tabs

jQuery('form#localityadd').on('submit', function(e) {
        jQuery('.status').show();
          locality_name = jQuery('form#localityadd #locality_name').val();
          region_code = jQuery('form#localityadd #region_code').val();
          locality_start_date = jQuery('form#localityadd #locality_start_date').val();
          locality_end_date = jQuery('form#localityadd #locality_end_date').val();
           local_council = jQuery('form#localityadd #local_council').val();
            locality_code = jQuery('form#localityadd #locality_code').val();
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo  admin_url( 'admin-ajax.php' );?>',
            data: {
                'action': 'add_localities_data',
                'locality_name': locality_name,
                'region_code': region_code,
                'locality_start_date': locality_start_date,
                'locality_end_date': locality_end_date,
                'local_council' : local_council,
                'locality_code' : locality_code
            },
            success: function(data) {

                jQuery('.status').text('Record Inserted successfully...');
                 setTimeout(function () {
                     location.reload();
                 }, 2500);
                
            }
        });
        e.preventDefault();
    });
//edit local council

//editlocalBtn
jQuery(document).on('click','.editlocalBtn', function(e) {

        locality_id = jQuery(this).attr('data-id');
        jQuery.ajax({
            type: 'POST',
            dataType: 'html',
            url: '<?php echo  admin_url('admin-ajax.php');?>',
            data: {
                'action': 'get_local_council_data',
                'locality_id': locality_id,

            },
            success: function(data) {
                 
                jQuery('#editLocalityBody').html(data);
                    jQuery("#editEmployeeModal").modal({backdrop: false});
                
            }
        });
        e.preventDefault();
    });
//editLocalityBtn
jQuery(document).on('click','.editLocalityBtn', function(e) {

			var localitycode = jQuery(this).attr('localitycode');
			var locality_name = jQuery(this).attr('locality_name');
			var amu_regions = jQuery(this).attr('amu_regions');
			var start_date = jQuery(this).attr('start_date');
			var end_date = jQuery(this).attr('end_date');
			var local_council_name = jQuery(this).attr('local_council_name');
            var edit_locality_id = jQuery(this).attr('data-id');
            
            jQuery("#edit_locality_id").val(edit_locality_id);
            jQuery("#edit_locality_name").val(locality_name); 
            jQuery("#edit_locality_code").val(localitycode);
            jQuery("#edit_region_name_locality").val(amu_regions);
            jQuery("#edit_local_council").val(local_council_name);
            jQuery("#edit_locality_start_date").val(start_date);
            jQuery("#editEmployeeModal").modal({backdrop: false});
     
      
    });
//editLocality

jQuery('form#editLocality').on('submit', function(e) {
        jQuery('.status').show();
        edit_locality_name = jQuery('form#editLocality #edit_locality_name').val();
        edit_region_name_locality = jQuery('form#editLocality #edit_region_name_locality').val();
        edit_locality_start_date = jQuery('form#editLocality #edit_locality_start_date').val();
        edit_locality_end_date = jQuery('form#editLocality #edit_locality_end_date').val();
        edit_locality_id = jQuery('form#editLocality #edit_locality_id').val();

      localitycode = jQuery("#edit_locality_code").val();
      local_council_name =   jQuery("#edit_local_council").val();




        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo  admin_url( 'admin-ajax.php' );?>',
            data: {
                'action': 'edit_localities_data',
                'edit_locality_name': edit_locality_name,
                'edit_region_name_locality': edit_region_name_locality,
                'edit_locality_start_date': edit_locality_start_date,
                'edit_locality_end_date': edit_locality_end_date,
                 'localitycode': localitycode,
                  'local_council_name': local_council_name,
                'edit_locality_id': edit_locality_id,
            },
            success: function(data) {

                jQuery('.status').text('Record Updated successfully...');
                 setTimeout(function () {
                      location.reload();
                 }, 2500);
                
            }
        });
        e.preventDefault();
    });

//local council script start

  // Perform AJAX login/register on form submit
    jQuery('form#counciladd').on('submit', function(e) {
        jQuery('.status').show();
        council_name = jQuery('form#counciladd #council_name').val();
        start_date = jQuery('form#counciladd #locality_start_date').val();   
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo  admin_url( 'admin-ajax.php' );?>',
            data: {
                'action': 'save_council_data',
                'council_name': council_name,
                'start_date': start_date,
            },
            success: function(data) {

                jQuery('.status').text('Record Inserted successfully...');
                 setTimeout(function () {
                     location.reload();
                 }, 2500);
                
            }
        });
        e.preventDefault();
    });
//editlocal script
jQuery('form#editlocal').on('submit', function(e) {
        jQuery('.status').show();
        edit_locality_name = jQuery('form#editlocal #edit_local_name').val();
        edit_local_start_date = jQuery('form#editlocal #edit_local_start_date').val();
        edit_local_id = jQuery('form#editlocal #edit_local_id').val();
        jQuery.ajax({
            type: 'POST',
            dataType: 'json',
            url: '<?php echo  admin_url( 'admin-ajax.php' );?>',
            data: {
                'action': 'edit_local_data',
                'local_name': edit_locality_name,
                'edit_local_start_date': edit_local_start_date,
                'edit_local_id': edit_local_id,
            },
            success: function(data) {

                jQuery('.status').text('Record Updated successfully...');
                 setTimeout(function () {
                      location.reload();
                 }, 2500);
                
            }
        });
        e.preventDefault();
    });


jQuery('form#locality_excel').on('submit', function(e) {
    e.preventDefault();

    var fd = new FormData();
  var file = jQuery(document).find('input[type="file"]');
    var individual_file = file[0].files[0];
    fd.append("file", individual_file);
    fd.append('action', 'localities_import_excel');  

    jQuery.ajax({
        type: 'POST',
         url: '<?php echo  admin_url('admin-ajax.php');?>',
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
jQuery('.status').text('Record Import successfully...');
                 setTimeout(function () {
                    //  location.reload();
                 }, 2500);
        }
    });
});
//region_excel
jQuery('form#region_excel').on('submit', function(e) {
	alert('hiiii');
    e.preventDefault();
    var fd = new FormData();
  var file = jQuery(document).find('input[type="file"]');
    var individual_file = file[0].files[0];
    fd.append("file", individual_file);
    fd.append('action', 'regions_import_excel');  

    jQuery.ajax({
        type: 'POST',
         url: '<?php echo  admin_url('admin-ajax.php');?>',
        data: fd,
        contentType: false,
        processData: false,
        success: function(response){
jQuery('.status').text('Record Import successfully...');
                 setTimeout(function () {
                     // location.reload();
                 }, 2500);
        }
    });
});

});

   </script>
    <style>
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
		background: #435d7d;
		color: #fff;
		padding: 16px 30px;
		margin: -20px -25px 10px;
		border-radius: 3px 3px 0 0;
    }
    .table-title h2 {
		margin: 5px 0 0;
		font-size: 24px;
	}
	.table-title .btn-group {
		float: right;
	}
	.table-title .btn {
		color: #fff;
		float: right;
		font-size: 13px;
		border: none;
		min-width: 50px;
		border-radius: 2px;
		border: none;
		outline: none !important;
		margin-left: 10px;
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
		outline: none !important;
	}
	table.table td a:hover {
		color: #2196F3;
	}
	table.table td a.edit {
        color: #FFC107;
    }
    table.table td .edit {
        color: #FFC107;
    }
    .edit1 {
        color: #FFC107;
    }
.edit1 {
    font-weight: bold;
    color: #566787 ;
    display: inline-block;
    text-decoration: none;
    outline: none !important;
    border: none !important;
    background: white;
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
	
	/* Modal styles */
	.modal .modal-dialog {
		max-width: 400px;
	}
	.modal .modal-header, .modal .modal-body, .modal .modal-footer {
		padding: 20px 30px;
	}
	.modal .modal-content {
		border-radius: 3px;
	}
	.modal .modal-footer {
		background: #ecf0f1;
		border-radius: 0 0 3px 3px;
	}
    .modal .modal-title {
        display: inline-block;
    }
	.modal .form-control {
		border-radius: 2px;
		box-shadow: none;
		border-color: #dddddd;
	}
	.modal textarea.form-control {
		resize: vertical;
	}
	.modal .btn {
		border-radius: 2px;
		min-width: 100px;
	}	
	.modal form label {
		font-weight: normal;
	}	
</style>
    <?php
}


add_action( 'admin_init', 'acf_settings_init_regions' );

function acf_settings_init_regions() {

    add_settings_section(
        'sample_page_setting_section',
        __( 'Custom settings', 'my-textdomain' ),
        'acf_setting_section_callback_function',
        'acf-regions'
    );

		add_settings_field(
		   'my_setting_field',
		   __( 'My custom setting field', 'my-textdomain' ),
		   'acf_setting_markup_reg',
		   'acf-regions',
		   'sample_page_setting_section'
		);

		register_setting( 'acf-regions', 'my_setting_field' );
}


function acf_setting_section_callback_function() { ?>


<?php }


function acf_setting_markup_reg() {
    ?>
    <label for="my-input"><?php _e( 'My Input' ); ?></label>
    <input type="text" id="my_setting_field" name="my_setting_field" value="<?php echo get_option( 'my_setting_field' ); ?>">
    <?php
}