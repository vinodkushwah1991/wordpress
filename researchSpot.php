<?php /* Template Name: Reasearch Spot */ ?>
<?php
get_header();?>
      <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
         rel = "stylesheet">
<style type="text/css">
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
#select2-researh_spot_locality-container {
	text-align: left;
}
#select2-locatestreets-container {
	text-align: left;
}
</style>
<?php global $wpdb;
$result = $wpdb->get_results ( "SELECT  DISTINCT locality_name  FROM  amu_research_spot" ); 
?>
<div class="search_page_bgcolor">
<div class="container">
  <div class="search_form_content">
    <form id="filter_res_spot_formData" method="post" action="">
      <label for="search-input-1">LOCALITY</label>
		<select name="researh_spot_locality" id="researh_spot_locality">
			<option value="">Select Locality</option>
			<?php foreach ($result as $key => $value) { ?>
				<option value="<?php echo $value->locality_name ?>"><?php echo $value->locality_name ?></option>
			<?php } ?>
		</select>
<!--       <input type="text" id="search-input-1" placeholder="Locality"> <br/> -->
		<br>
		<div class="locatestreetsmain">
      <label for="search-input-2">STREET</label>
		<select name="locatestreets" id="locatestreets">
			<option value="">Select Street</option>
		</select>
	</div>
<!--       <input type="text" id="search-input-2" placeholder="Street"> -->
      <div class="button-container">
        <button type="submit" name="serchform_submit">Search</button>
        <button type="button" id="search_clear" name="clearform_submit">Clear</button>
      </div>
    </form>
  </div>
  <div class="search_result_table11" style="display:none;">
    <div class="search_result_for11">
      <h4 id="search-for"> </h4>
    </div>
    <div class="goverment_gazette11">
      <h3>Government Gazette</h3>
    </div>
    <table class="table table-hover table-fixed">

  <!--Table head-->
  <thead>
    <tr>
      <th style="background: #21b685;">Notice No.</th>
      <th style="background: #21b685;">Publication Date</th>
      <th style="background: #21b685;">Description</th>
      <th style="background: #21b685;">Links</th>
    </tr>
  </thead>
  <!--Table head-->

  <!--Table body-->
  <tbody id="spot_filter_data">
   
   
  </tbody>
  <!--Table body-->

</table>
  </div>
</div>
</div>
 <script type="text/javascript">

 var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";

jQuery(document).ready(function($) {

$('#search_clear').click(function(){
        $('#filter_res_spot_formData')[0].reset();
        $('form#filter_res_spot_formData select').trigger("change"); //Line2
        $(".search_result_table11").hide();
        $("#spot_filter_data").html("");
  });

$('#researh_spot_locality').change(function(){
 var  locateRegionId = $(this).val();
     $.ajax({
        url: ajaxurl,
        type: 'POST',
        data: {
            locateRegionId: locateRegionId,
            action: 'get_resources_spot_dropwown_data',
        },
        success: function(response) {
         $("#locatestreets").html(response);
        }


    });
});


jQuery('form#filter_res_spot_formData').on('submit', function(e) {
        jQuery('.status').show();
         researh_spot_locality = jQuery('#researh_spot_locality').val();
         locatestreets = jQuery('#locatestreets').val();

        jQuery.ajax({
            type: 'POST',
            dataType: 'html',
            url: '<?php echo  admin_url( 'admin-ajax.php' );?>',
            data: {
                'action': 'filter_res_spot_formData',
                'locatestreets': locatestreets,
                'researh_spot_locality' :researh_spot_locality,
            },
            success: function(data) {
             
            	jQuery(".search_result_table11").show();
               jQuery("#spot_filter_data").html(data);
            }
        });
        e.preventDefault();
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

.button-container {
	margin-left: 65px;
	margin-top: 33px;
}

.locatestreetsmain span.select2.select2-container{
	margin-left: 31px !important;;
}
</style>
<?php
get_footer();
