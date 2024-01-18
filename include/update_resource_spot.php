<div id="employeeModal" style="margin-top: 6rem;background-color: white;padding: 55px;">
<?php 
 $tablename=$wpdb->prefix.'research_spot';
 $rowarr = $wpdb->get_row("SELECT * FROM  $tablename where id ='".$_GET['editPost']."' " ); ?>
	<div class="modal-dialog1">
		<form method="post" id="employeeForm">
			<div class="modal-content1">
				<div class="modal-header1">
					<button type="button" class="close" data-dismiss="modal">×</button>
					<h4 class="modal-title"><i class="fa fa-plus"></i> Edit Research Spot</h4>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label for="name" class="control-label">Street Name</label>
						<input type="text" class="form-control" id="empName" name="empName" placeholder="Street Name" required value="<?php echo $rowarr->street_name;?>">			
					</div>
					<div class="form-group">
						<label for="age" class="control-label">Locality</label>							
						<input type="text" class="form-control" id="empAge" name="empAge" placeholder="Locality" value="<?php echo $rowarr->locality_name;?>">							
					</div>	   	
					<div class="form-group">
						<label for="lastname" class="control-label">Publication Date</label>							
						<input type="text" class="form-control"  id="empSkills" name="empSkills" placeholder="Government Notice No" value="<?php echo $rowarr->date;?>">							
					</div>	 

					<div class="form-group">
						<label for="lastname" class="control-label">Government Notice No</label>							
						<input type="text" class="form-control"  id="empSkills" name="empSkills" placeholder="Government Notice No" value="<?php echo $rowarr->notice_number;?>">							
					</div>

					<div class="form-group">
						<label for="address" class="control-label">PDF Name</label>							
						<input type="file" class="form-control" id="pdfArr" name="file" placeholder="Upload PDF">						
					</div>
					<div class="form-group">
						<label for="lastname" class="control-label">Description</label>							
						<textarea class="form-control" rows="5" id="Naming" name="Naming"><?php echo $rowarr->street_name;?></textarea>			
					</div>						
				</div>
				<div class="modal-footer" style="text-align:left;">
					<input type="hidden" name="empId" id="empId" value="<?php echo $rowarr->id;?>" />
					<input type="submit" name="save" id="save" class="btn btn-info" value="Update" />
				</div>
			</div>
		</form>
	</div>
</div>

