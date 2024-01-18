<?php /* Template Name: Search Page */ ?>
<?php
get_header();?>
      <link href = "https://code.jquery.com/ui/1.10.4/themes/ui-lightness/jquery-ui.css"
         rel = "stylesheet">
    <!--   <script src = "https://code.jquery.com/jquery-1.10.2.js"></script>
      <script src = "https://code.jquery.com/ui/1.10.4/jquery-ui.js"></script> -->
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
</style>

<div class="search_page_bgcolor">
<div class="search_container">
  <div class="search_heading">
    <h1 class="search_heading_content">
      RESEARCH SPOT
    </h1>
  </div>
  <div class="search_content_data">
      <p>Lorem ipsum dolor sit, amet consectetur adipisicing elit. Debitis molestiae nisi harum cumque fuga obcaecati perspiciatis, repellat, nihil perferendis molestias itaque ipsa iste, doloribus architecto earum veritatis sit enim vero aperiam! Velit, nulla omnis atque cupiditate exercitationem repudiandae. Vitae minus dolores sit nisi deleniti soluta commodi odit eaque, iste hic illo esse enim maiores voluptas.
    </p>
  </div>


  <div class="search_form_content">
    <form id="search-form" method="post" action="">
      <label for="search-input-1">LOCALITY</label>
		<select name="" id="search-input-1">
			<option value="">Select Locality</option>
		</select>
<!--       <input type="text" id="search-input-1" placeholder="Locality"> <br/> -->
		<br>
      <label for="search-input-2">STREET</label>
		<select name="" id="search-input-2">
			<option value="">Select Street</option>
		</select>
<!--       <input type="text" id="search-input-2" placeholder="Street"> -->
      <div class="button-container">
        <button type="submit" name="serchform_submit">Search</button>
        <button type="button" id="search_clear" name="clearform_submit">Clear</button>
      </div>
    </form>
  </div>
  <div class="search_result_table">
    <div class="search_result_for">
      <h4 id="search-for"> </h4>
    </div>
    <div class="goverment_gazette">
      <h3>Government Gazette</h3>
    </div>
    <table id="search-results"></table>
  </div>
</div>
</div>
    
<script type="text/javascript">

	var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>"

	jQuery(document).ready(function($) {
  var globalArray;
$('#search-input-1').on('input', function() {
    autoCom(globalArray);
});

function autoCom(arr) {
    $("#search-input-1").autocomplete({
        source: arr,
        autoFocus: true
    });
}

var searchQuery1 = $('#search-input-1').val(); // Get the value of the first custom field

// Remove duplicate words from searchQuery1
var wordsArray = searchQuery1.split(' ');
var uniqueWordsArray = Array.from(new Set(wordsArray));
searchQuery1 = uniqueWordsArray.join(' ');

// AJAX request
$.ajax({
    url: ajaxurl, // WordPress AJAX handler
    type: 'POST',
    data: {
        onChangeHandler1: 'onChangeHandler',
        action: 'search_custom_posts',
        search_query_1: searchQuery1,
    },
    success: function(response) {
        //console.log(response);
        var uniqueResponse = Array.from(new Set(response)); // Remove duplicate values from the response array
        uniqueResponse.sort(function(a, b) {
        return a.localeCompare(b);
    }); // Sort the response array using localeCompare
        uniqueResponse.forEach(function(locality) {
            $('#search-input-1').append(`<option value="${locality}">${locality}</option>`);
        });
        globalArray = uniqueResponse;
    }
});



    var globalArray2 ;
    $('#search-input-2').on('input', function() {
	    autoComm(globalArray2);
    });

     $("#search-input-1").change(function() {
    var searchQuery1 = $(this).val();
    var searchQuery3 = null;
    $('#search-input-2').empty();
    console.log("searchQuery1", searchQuery1);
    console.log("searchQuery3", searchQuery3);
    $.ajax({
        url: ajaxurl, // WordPress AJAX handler
        type: 'POST',
        data: {
            onChangeHandler2: 'onChangeHandler2',
            action: 'search_custom_posts',
            search_query_2: searchQuery3,
        },
        success: function(response2) {
            console.log("response on change", response2);
            var uniqueResponse2 = [];
            var uniqueStreets = new Set(); // Track unique street values

            response2.forEach(function(res) {
                if (res.locality == searchQuery1 && !uniqueStreets.has(res.street)) {
                    uniqueStreets.add(res.street);
                    uniqueResponse2.push(res);
                }
            });

            uniqueResponse2.sort(function(a, b) {
                return a.street.localeCompare(b.street);
            });

            uniqueResponse2.forEach(function(res) {
                console.log(res.locality == searchQuery1);
                $('#search-input-2').append(`<option value="${res.street}">${res.street}</option>`);
            });
        }
    });
});


	function autoComm(arr2){
         	$( "#search-input-2" ).autocomplete({
               source: arr2,
               autoFocus:true
            });
         }


 var searchQuery2 = $('#search-input-2').val(); // Get the value of the first custom field
var wordsArray2 = searchQuery2.split(' ');
var uniqueWordsArray2 = Array.from(new Set(wordsArray2));
searchQuery2 = uniqueWordsArray2.join(' ');
// AJAX request
$.ajax({
    url: ajaxurl, // WordPress AJAX handler
    type: 'POST',
    data: {
        onChangeHandler2: 'onChangeHandler2',
        action: 'search_custom_posts',
        search_query_2: searchQuery2,
    },
    success: function(response2) {
        console.log(response2);
        var uniqueResponse2 = [];
        var uniqueStreets = new Set(); // Track unique street values

        response2.forEach(function(street) {
            if (!uniqueStreets.has(street.street)) {
                uniqueStreets.add(street.street);
                uniqueResponse2.push(street);
            }
        });

        uniqueResponse2.sort(function(a, b) {
            return a.street.localeCompare(b.street);
        });

        uniqueResponse2.forEach(function(street) {
            //console.log(street, "sadasda");
            $('#search-input-2').append(`<option value="${street.street}">${street.street}</option>`);
        });
        globalArray2 = uniqueResponse2;
    }
});


  // Handle the form submission
  $('#search-form').on('submit', function(e) {
    e.preventDefault(); // Prevent the default form submission
  $('.search_result_table').addClass('show_table');
    var searchQuery1 = $('#search-input-1').val(); // Get the value of the first custom field
    var searchQuery2 = $('#search-input-2').val(); // Get the value of the second custom field
    searchQuery2 = decodeURIComponent(searchQuery2);
    console.log(searchQuery2);
    
    // AJAX request
    $.ajax({
      url: ajaxurl, // WordPress AJAX handler
      type: 'POST',
      data: {
        action: 'search_custom_posts',
        search_query_1: searchQuery1,
        search_query_2: searchQuery2
      },
      success: function(response) {
        // Update the DOM with the search results
		$('#search-for').empty();

    if(searchQuery1 && !searchQuery2){
       $('#search-for').append(`Locality <b>${searchQuery1}</b>`);
    } if(searchQuery2 && ! searchQuery1){
      $('#search-for').append(`Street <b>${searchQuery2}</b>`);
    } if(searchQuery1 && searchQuery2 ){
      $('#search-for').append(`Locality <b>${searchQuery1}</b> and Street <b>${searchQuery2}</b>`);
    }
		  
        $('#search-results').html(response);
      }
    });
  });

// clear form data
 $('#search_clear').click(function() {
  $('#select2-search-input-1-container').text('Select Locality'); // Reset the value of the first custom field
  $('#select2-search-input-2-container').text('Select Street'); // Reset the value of the second custom field
  $('#search-results').empty();
  $('#search-for').empty();
  $('.search_result_table').removeClass('show_table');
});


});
</script>
<?php
get_footer();
