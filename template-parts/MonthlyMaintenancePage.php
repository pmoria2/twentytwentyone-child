<?php
/**
 * 
 *
 * @package      Advanced Custom Fields (ACF) Custom Blocks 
 * 
 *                  Custom Post Type: This routine is not associated with a CPT
 *                  Custom Fields\Field Group name for field definitions: N/A
 *                  Custom Fields block name: "Monthly Maintenance Page"
 *                  Monthly Maintenance Page contains a form where you can import webinar records from Accessibility Online, 
 *                  and links for easy access to to content that is regularly updated on the ADA Great Lakes homepage.
 *                  URL: homepage url/monthlymaintenancepage/#form/ 
 * 
 * @author       Patrick Moriarty
 * @since        1.0.0
 * @license      GPL-2.0+
 
 * 
 * This code is registered and linked to the block name (listed above) in the function pm_register_blocks().  To display the records for this CPT on a page, you
 * simply add the block to a page with the WordPress block editor. 
 * 
 * 
**/



?>

<!-- CSS code -->

<style>
    .embed-container { 
        position: relative; 
        padding-bottom: 50%;
        overflow: hidden;
        max-width: 100%;
        height: auto;
    } 

    .embed-container iframe,
    .embed-container object,
    .embed-container embed { 
        position: absolute;
        top: 0;
        left: 0;
        width: 95%;
        height: 100%;
    }


.column {
  float: left;
  width: 50%;
  padding: 10px;
  column-gap: 20px;
  column-width:100%;
}

/* Clear floats after the columns */
.row:after {
  content: "";
  display: table;
  clear: both;
}

.row {

  width: 100%
}

/* Responsive layout - makes the two columns stack on top of each other instead of next to each other on screens that are smaller than 600 px */
@media screen and (max-width: 800px) {
  .column {
    width: 100%;
  }
}

.center {
  margin: auto;
  width: 70%;
  padding: 10px;
}
</style>

<?php

global $wpdb;

//-----------------------------------------------------------------------------
// we keep a training calendar parameters table to track the number of months to display, and when the last update took place
//-----------------------------------------------------------------------------
$parametersTable = $wpdb->prefix."training_calendar_params";
    
$sqlStatement="SELECT display_months, download_date FROM ".$parametersTable." where id = 1;";
                
    //$wpdb->show_errors();
    $displayMonthsUpdate = false;
    $download = false;
    $weeksBack = 0;
    $rows = $wpdb->get_results( $sqlStatement);
    //$wpdb->print_error();

    //<p> <?php echo $print->date_end.', '.$print->period;	</p>

    // echo '<pre>';
    //     print_r( $period_days  );
    // echo '</pre>';
    // die;
    // echo $period_days['value'];

        
    foreach( $rows as $row ) {
		
      //$displayMonths = get_field('display_months');
      //$downloadDate = get_field('download_date');    

      $displayMonths =$row->display_months;
      $downloadDate = $row->download_date;
      }
	
      $sectionTitle = get_field('heading');
      $sectionText = "Last Training Calendar download was ".$downloadDate;
      

      //-----------------------------------------------------------------------------
      // Collect data entry from calendar form
      //-----------------------------------------------------------------------------

      if( isset($_GET[ 'download' ]) ) {
        $download = $_GET[ 'download' ]; 

        date_default_timezone_set("America/Chicago");
        $downloadDate=date("Y-m-d h:i:sa");
        $sectionText = "Last download was ".$downloadDate;
        copyRemoteWebinarTable();

      }
      
      if( isset($_GET[ 'displayMonths' ]) ) {
        $displayMonths = $_GET[ 'displayMonths' ];
        $displayMonthsUpdate = true;
      }

      if( isset($_GET[ 'weeksBack' ]) ) {
        $weeksBack = $_GET[ 'weeksBack' ];
      }


      if ($displayMonthsUpdate or $download) {

        // Save input to parameters table
        $wpdb->update($parametersTable, array('display_months'=>$displayMonths, 'download_date'=>$downloadDate), array('id'=>1) );
      }
      
      $numDaysGoingForward = $displayMonths*30;
      $numDaysGoingback = $weeksBack*7;

      ?>
<article class="alignwide" id="maintenance1" >

<h1 style="text-align:center" ><?php echo $sectionTitle; ?></h1>
<br>
<div id="form"></div>
<br />
<br />
<p style="text-align:center"><?php echo $sectionText; ?></p>
<br />

<div class="row" >

<div class="column" >
   <h3 style="text-align:center" >Training Calendar</h3><br>  
   <div id="clipboard">
      <p><?php echo displayTrainingCalendar_monthlyMaintenance($numDaysGoingback, $numDaysGoingForward); ?></p>
   </div>
 </div>

  <div class="column" >
  <h3 style="text-align:center" >Training Calendar Maintenance Form</h3>
  <form method="GET">
   <label>
     <input
      type="checkbox"
      name="download"
      value=1
     />
     
     Download training schedule records? </label>
<br />

  <label>How far in the future should the Training Schedule look? </label>
  <br>
  <select name="displayMonths" id="displayMonths" >
    <option value="1" <?php echo selected($displayMonths,"1"); ?>>1 Month</option>
    <option value="2" <?php echo selected($displayMonths,"2"); ?>>2 Month</option>
    <option value="3" <?php echo selected($displayMonths,"3"); ?>>3 Month</option>
  </select>
  <br>
  <label>How far back should the Training Schedule look? </label>
  <select name="weeksBack" id="weeksBack" >
    <option value="0" <?php echo selected($weeksBack,"0"); ?>>Starting Today</option>
    <option value="1" <?php echo selected($weeksBack,"1"); ?>>1 Week</option>
    <option value="2" <?php echo selected($weeksBack,"2"); ?>>2 Weeks</option>
    <option value="3" <?php echo selected($weeksBack,"3"); ?>>3 Weeks</option>
  </select>

  <br>
  <br>
  <p>Note: Changing the starting point is for creating a Chronicle post, and only affects the list to the left. Changing the number of months to look into the future will also change the the calendar on the homepage.</p>
  <br>
    <div class="center" >
      <button type="submit">Apply</button>
    </div>

  </form>
  
  <br><br>
  <h3 style="text-align:left" >Update Chronicle</h3>

<div class="center" >
  <br><button class='btn' data-clipboard-action="copy" data-clipboard-target="#clipboard">Copy Training Calendar</button>
  <br>
</div>

<br>
<a href="<?php echo admin_url('post-new.php?post_type=chronicle'); ?>" target="_self">Begin a new Chronicle entry</a>
<br>
<a href="<?php echo admin_url('post-new.php?post_type=chronicle'); ?>" target="_blank">Begin a new Chronicle entry in a new tab</a>
<br>
<a href="<?php echo admin_url('edit.php?post_type=chronicle'); ?>" target="_self">Go back and edit a Chronicle entry</a>
<br>
<a href="<?php echo site_url('chronicle'); ?>" target="_self">Go to the Chronicle Archive</a>
<br>
<br>
<h3>More Updates</h3>
<a href="<?php echo admin_url('post.php?post=11937&action=edit'); ?>" target="_self">Update Resource of the Month List</a>
<br>
<a href="<?php echo admin_url('post.php?post=11939&action=edit'); ?>" target="_self">Update Announcements</a>
<br>
<a href="<?php echo admin_url('post.php?post=12408&action=edit'); ?>" target="_self">Update Video of the Month</a>
<br>

</div>

  </div>
  </article>

<hr>