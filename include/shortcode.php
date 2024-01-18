<?php
function regions_lists($atts) {
       global $wpdb; ob_start(); 
       ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

<table class="table table-striped table-hover" id="regionDtable">
					<thead>
						<tr>
							
							<th>Region Name MT</th>
							<th>Region Name EN </th>

						</tr>
					</thead>
					<tbody>
						<?php $result = $wpdb->get_results ( "SELECT * FROM  amu_regions order by region_name_mt ASC" );
						$count =1;
                         foreach ($result as $key => $regionsArr) {?>
						<tr>
							
							<td><?php echo $regionsArr->region_name_mt;?></td>
							<td><?php echo $regionsArr->regionen;?></td>

						</tr>
					<?php $count++;  } ?>
						
					</tbody>
				</table>
				 <script> 
  	jQuery(document).ready(function($) {
new DataTable('#regionDtable'); 
});
</script>
<style> 
table tbody tr, table thead tr {
	border: 1px solid #e6e6e6;
	background: #fff !important;
	color: #222;
}
th {
	background: #d9edf7 !important;
}
.dataTables_wrapper {
	width: 80%;
	margin: 0 auto;
	color: #333;
}
</style>
<?php  $output = ob_get_contents();
    ob_end_clean(); 
    return  $output; ?>

<?php 
}
add_shortcode('regions_lists', 'regions_lists');


//shortode loalities
function loalities_lists($atts) {
       global $wpdb; ob_start(); 
       ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

<table class="table table-striped table-hover" id="loalitiesTbl">
					<thead>
						<tr>
							<th>
								Locality Name
							</th>
							<th>Local Council</th>
							<th>Region Name MT </th>
							<th>Region Name EN </th>

						</tr>
					</thead>
					<tbody>
						<?php $result = $wpdb->get_results ( "SELECT * FROM  amu_localities_list order by Locality_Name ASC" );
						$count =1;
                         foreach ($result as $key => $local_council) {
                         	 $amu_regions = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM amu_regions WHERE id = $local_council->region_Code" ) );
                         	 $amu_local_council_list  = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM amu_local_council_list  WHERE locality_code = $local_council->locality_Code" ) );

                         	?>
						<tr>
							
							<td><?php echo  $local_council->Locality_Name;?></td>
							<td><?php echo $local_council->local_council_name;?></td>
							<td><?php echo $amu_regions->region_name_mt;?></td>
							<td><?php echo $amu_regions->regionen;?></td>

						</tr>
					<?php $count++;  } ?>
						
					</tbody>
				</table>
				 <script> 
  	jQuery(document).ready(function($) {
new DataTable('#loalitiesTbl'); 
});
</script>
<style> 
table tbody tr, table thead tr {
	border: 1px solid #e6e6e6;
	background: #fff !important;
	color: #222;
}
th {
	background: #d9edf7 !important;
}
.dataTables_wrapper {
	width: 80%;
	margin: 0 auto;
	color: #333;
}
</style>
<?php  $output = ob_get_contents();
    ob_end_clean(); 
    return  $output; ?>

<?php 
}
add_shortcode('loalities_lists', 'loalities_lists');



//shortode loalities
function localcouncil($atts) {
       global $wpdb; ob_start(); 
       ?>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css" rel="stylesheet">
            <script src="https://code.jquery.com/jquery-3.7.0.js"></script>

<table class="table table-striped table-hover" id="loalitiesTbl11">
					<thead>
						<tr>
							
							<th>Local Council</th>
							<th>Region Name MT </th>
							<th>Region Name EN </th>

						</tr>
					</thead>
					<tbody>
						<?php $result = $wpdb->get_results ( "SELECT * FROM  amu_local_council_list order by id DESC" );
						$count =1; 
                         foreach ($result as $key => $local_council) {
                          $amu_localArr = $wpdb->get_row(  "SELECT * FROM amu_localities_list WHERE local_council_name ='".$local_council->local_council_name."'" );

                          $amu_regions = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM amu_regions  WHERE id = $amu_localArr->region_Code" ) );


                         	?>
						<tr>
							
							
							<td><?php echo $local_council->local_council_name;?></td>
							<td><?php echo $amu_regions->region_name_mt;?></td>
							<td><?php echo $amu_regions->regionen;?></td>

						</tr>
					<?php $count++;  } ?>
						
					</tbody>
				</table>
				 <script> 
  	jQuery(document).ready(function($) {
new DataTable('#loalitiesTbl11'); 
});
</script>
<style> 
table tbody tr, table thead tr {
	border: 1px solid #e6e6e6;
	background: #fff !important;
	color: #222;
}
th {
	background: #d9edf7 !important;
}
.dataTables_wrapper {
	width: 80%;
	margin: 0 auto;
	color: #333;
}
</style>
<?php  $output = ob_get_contents();
    ob_end_clean(); 
    return  $output; ?>

<?php 
}
add_shortcode('localcouncil', 'localcouncil');

 ?>