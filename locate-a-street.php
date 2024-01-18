<?php /* Template Name: Locate A Street */ ?>
<?php
get_header();?>
<link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css" rel = "stylesheet">
<script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap.min.css" rel="stylesheet">
    
      <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
       <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap.min.js"></script>
<style type="text/css">
#locate_street_form label {
    width: 157px;
    text-align: left;
}
table {
  font-family: arial, sans-serif;
  border-collapse: collapse;
  width: 100%;
}

td, th {
  border: 1px solid #dddddd !important;
  text-align: left;
  padding: 8px;
}
.locate_result_table.show_table {
    display: block;
    padding: 40px;
    margin-bottom: 30px;
    background-color: #fff;
    color: #000;
}
.locate_result_table th {
    background-color: #f2f2f2;
}
.search-input-street:focus{
    color: #a2a1a1 !important;
}
input[type="text"]{
    color: #a2a1a1 !important;
}
input#search-input-street::placeholder {
    color: #a2a1a1;
}
ul#ui-id-1 {
    background: #21b685;
    border: 0;
    padding: 0;
}
li.ui-menu-item {
    border: none !important;
    list-style: none !important;
    color: #fff !important;
    list-style-image: none !important;
}
.ui-menu-item-wrapper {
    color: #fff;
    border: 0 !important;
    padding: 0 20px;
}
.ui-menu-item-wrapper:hover {
    background: #000;
    color: #fff;
    cursor: pointer;
}
.custom-dropdown-item .ui-menu-item-wrapper{
    padding: 10px 20px;
}
.custom-dropdown-item:hover h4 {
    color: #21b685;
}
.custom-dropdown-item h4 {
    margin: 0;
    padding: 0;
}
.custom-dropdown-item p {
    margin: 0;
    padding: 0;
}
.street_field {
    margin-top: 50px;
}
tbody {
	color: #333;
}
.dataTables_wrapper {
	width: 80%;
	margin: 0 auto;
	background-color: #fff;
}
th {
	color: #002035 !important;
}

.list-group-item {
	position: relative;
	display: block;
	padding: 10px 15px;
	margin-bottom: -1px;
	background-color: unset !important;
	border: 1px solid #ddd;
}
.list-group {
	padding-left: 0;
	margin-bottom: 20px;
	margin-left: 171px !important;
	width: 303px !important;
	margin-top: -10px !important;
}

</style>
<?php global $wpdb;
 $result = $wpdb->get_results ( "SELECT * FROM  amu_regions order by region_name_mt ASC" );
 ?>
<div class="search_page_bgcolor">
<div class="container">

  <div class="search_form_content">
    <form id="locate_street_form" method="post" action="">
      <label for="locateRegion">Region</label>
		<select name="locateRegion" id="locateRegion">
			<option value="">Select Region</option>
			<?php
			foreach ($result as $key => $regionsArr) {?>
				<option value="<?php echo $regionsArr->id;?>"><?php echo $regionsArr->region_name_mt;?></option>
		    	<?php } ?>
			
		</select>
		<br>
      <label for="locateLocality">Locality</label>
		<select name="" id="locateLocality">
			<option value="">Select Locality</option>
		</select>
    <br>
      <label for="locatLocalCouncil">Local Council</label>
        <select name="" id="locatLocalCouncil">
      <option value="">Local Council</option>
    </select>
    <br>
    <div class="street_field">
      <label for="locateStreet" id="locateStreet">Street</label>
      <input type="text" name="search" id="search-input-street" placeholder="Street" name="search-input-street">
                        <ul id="search_result" class="list-group ullist" style="height: 169px;overflow-y: auto; display:none">
                       
                  </ul>
      </div>
      <div class="button-container">
        <button type="submit" name="serchform_submit">Search</button>
        <button type="button" id="locate_search_clear" name="clearform_submit">Clear</button>
      </div>
    </form>
  </div>
    <!-- Table -->
<table id='empTable' class='table table-hover table-fixed locate_result_table' style="display:none;">

   <thead>
       <tr>
           <th style="background: #21b685; width:200px !important;">Region</th>
           <th style="background: #21b685; width:200px !important;">Locality</th>
           <th style="background: #21b685; width:200px !important;">Local Council</th>
           <th style="background: #21b685; width:400px !important;">Street Name</th>
           
       </tr>
   </thead>

</table>

</div>
</div>
  <script type="text/javascript">
        jQuery(document).ready(function ($) {
        	$(document).on('click', '.list-group-item', function(e){
            var searchstreet  =jQuery(this).attr('value');
            var locateRegionVal ="";
            var locateLocalityVal ="";
              var locatLocalCouncilVal ="";
             $('#empTable').show();
            e.preventDefault(); // Prevent the default form submission
        $("#empTable").DataTable().destroy();
             $('#empTable').DataTable({
		      'processing': false,
		      'serverSide': false,
		      'serverMethod': 'post',
		      'ajax': {
		           url: ajaxurl,
		           data: {
                   action: 'filter_locate_a_streets',
                   locateRegionVal: locateRegionVal,
                   locateLocalityVal: locateLocalityVal,
                   locatLocalCouncilVal: locatLocalCouncilVal,
                   searchstreet:searchstreet,
                 },
		      },
		      'columns': [
		         { data: 'emp_name' },
		         { data: 'email' },
		         { data: 'gender' },
		         { data: 'salary' },
		      ],
			    "columns": [
				    {
				     "width": "10%" 
				    "width": "20%",
				    "width": "10%",
				    "width": "10%",
				    "width": "35%"
				  ]
				  },
		       // fixedColumns: true,
		   });
});

          
            $("#search-input-street").keyup(function () {
                var query = $(this).val();
                if (query != "") {
                    $.ajax({
                        url: "<?php echo admin_url('admin-ajax.php'); ?>",
                        method: 'POST',
                        data: {
                            query: query,
                            action: 'get_street_live_data',
                        },
                        success: function (data) {
                            $('#search_result').html(data);
                           // $('#search_result').css('display', 'block');
                             $('#search_result').show();
                            $("#search_result li").click(function() {
                                 var value = $(this).html();
                                 $('#search-input-street').val(value);                                
                                 $('#search_result').css('display', 'none');   
                                  $('#search_result').hide();                         
                            });
                        }
                    });
                } else {
                    $('#search_result').css('display', 'none');
                }
            });
        });
    </script>  
<script type="text/javascript">

 var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

jQuery(document).ready(function($) {

$('#locateRegion').change(function(){
 var  locateRegionId = $(this).val();
  $("#locatLocalCouncil").html("<option value=''>Select Local Council</option>");

     $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            locateRegionId: locateRegionId,
            action: 'get_region_list_onChange',
        },
        success: function(response) {
         $("#locateLocality").html(response);
        }


    });
});

$('#locateLocality').change(function(){
 var  locateLocality = $(this).val();
     $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            locateLocality: locateLocality,
            action: 'get_locality_list_onChange',
        },
        success: function(response) {
         $("#locatLocalCouncil").html(response);
        }


    });
});

    $('#locate_street_form').on('submit', function(e) {
        $('#empTable').show();
        e.preventDefault(); // Prevent the default form submission
        $("#empTable").DataTable().destroy();
        var locateRegionVal = $('#locateRegion').val(); // Get the value of the first custom field
        var locateLocalityVal = $('#locateLocality').val(); // Get the value of the second custom 
        var locatLocalCouncilVal = $('#locatLocalCouncil').val(); 
         var searchstreet = $('#search-input-street').val();

        // AJAX request for the filtered results
         if (locateRegionVal || locateLocalityVal || locatLocalCouncilVal || searchstreet) {

           $('#empTable').DataTable({
		      'processing': false,
		      'serverSide': false,
		      'serverMethod': 'post',
		      'ajax': {
		           url: ajaxurl,
		           data: {
                   action: 'filter_locate_a_streets',
                   locateRegionVal: locateRegionVal,
                   locateLocalityVal: locateLocalityVal,
                   locatLocalCouncilVal: locatLocalCouncilVal,
                   searchstreet:searchstreet,
                 },
		      },
		      'columns': [
		         { data: 'emp_name' },
		         { data: 'email' },
		         { data: 'gender' },
		         { data: 'salary' },
		      ]
		   });
		   
}
});      






    // clear form data
 $('#locate_search_clear').click(function() {

 $("#locatLocalCouncil").html("<option value=''>Select Local Council</option>");
  $("#locateLocality").html("<option value=''>Select Locality'</option>");
   //$("#locateRegion").html("<option value=''>Select Region</option>");

  $('#select2-locateRegion-container').text('Select Region'); 
  $('#select2-locateLocality-container').text('Select Locality');
  $('#select2-locatLocalCouncil-container').text('Select Local Council');  
  $('#search-input-street').val('');    
  $('#Locate_street_search-results').empty();
  $('#street_results').empty();
  $('.locate_result_table ').hide();
  $("#empTable").DataTable().destroy();
});
});
//
</script>
<style> 
.table-hover > tbody > tr:hover {
	background-color: #21b685 !important;
}
.search_form_content {
	
	margin-bottom: 8rem;
}
#empTable_wrapper .row {
	background: rgb(0, 32, 53);
	color: white;
	font-size: 14px;
}
td, th {
	border: 1px solid #fff !important;
	padding: 8px;
	color: #fff;
}
table.dataTable thead > tr > th.sorting, table.dataTable thead > tr > th.sorting_asc, table.dataTable thead > tr > th.sorting_desc, table.dataTable thead > tr > th.sorting_asc_disabled, table.dataTable thead > tr > th.sorting_desc_disabled, table.dataTable thead > tr > td.sorting, table.dataTable thead > tr > td.sorting_asc, table.dataTable thead > tr > td.sorting_desc, table.dataTable thead > tr > td.sorting_asc_disabled, table.dataTable thead > tr > td.sorting_desc_disabled {
	cursor: pointer;
	position: relative;
	padding-right: 26px;
}
</style>
<?php
get_footer();
