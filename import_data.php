<?php 
/*Template Name: Imp*/
wp_head();
global $wpdb;
?>

        <div class="container">
            <div class="row">
                <form class="form-horizontal" action="#" method="post" name="upload_excel" enctype="multipart/form-data">
                    <fieldset>
                        <!-- Form Name -->
                        <legend>Form Name</legend>
                        <!-- File Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="filebutton">Select File</label>
                            <div class="col-md-4">
                                <input type="file" name="file" id="file" class="input-large">
                            </div>
                        </div>
                        <!-- Button -->
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="singlebutton">Import data</label>
                            <div class="col-md-4">
                                <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Import</button>
                            </div>
                        </div>
                    </fieldset>
                </form>
            </div>
            
        </div>



        <?php
     if(isset($_POST["Import"])){
        
        $filename=$_FILES["file"]["tmp_name"];    
         if($_FILES["file"]["size"] > 0)
         {
            $file = fopen($filename, "r");
              while (($getData = fgetcsv($file, 10000, ",")) !== FALSE)
               {
                
       $tablename='amu_local_council';

        $data=array(
        'local_council_code' => $getData[0], 
        'local_council_name' => $getData[1],
        'date' =>'2023-10-16', 
        );


     $wpdb->insert( $tablename, $data);
           
               }
          
               fclose($file);  
         }
      }   
     ?>