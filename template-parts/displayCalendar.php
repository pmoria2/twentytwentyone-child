<?php
/**
 * 
 *
 * @package      Advanced Custom Fields (ACF) Custom Blocks 
 * 
 *                  Custom Post Type: This routine is no longer linked to a CPT, records are copied fromn Accessibility Online
 *                  Custom Fields\Field Group name for field definitions: N/A
 *                  Custom Fields block name: "Training Calendar", "Calendar List"
 *                  Displays records for Webinar Table: $wpdb->prefix."webinar_session".
 *                  URL: The webinar training calendar is displayed on the ADA Great Lakes homepage 
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

<style type="text/css">
.center {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 50%;
    text-align: center;
  }

  .column1 {
  float: left;
  width: 50%;
  column-gap: 10px;
  column-width: auto;
  padding: 50px 0;
  text-align: center;
}
.column2 {
  float: left;
  width: 50%;
  column-gap: 10px;
  column-width:auto%;
  padding: 20px 0;
}

.row {
  display: block;
  margin-left: auto;
  margin-right: auto;
  width: 70%;
}

@media screen and (max-width: 800px) {
  .column1 {
    width: 100%;
  }
}
@media screen and (max-width: 800px) {
  .column2 {
    width: 100%;
  }
}

</style>

<?php
global $wpdb;

$sectionTitle = get_field('heading');
$today = date('Ymd');
$date1=date_create($today);
$image=get_field('image');
$display_heading=get_field('display_heading');

$parametersTable = $wpdb->prefix."training_calendar_params";

$sqlStatement="SELECT display_months, download_date FROM ".$parametersTable." where id = 1;";
                
$rows = $wpdb->get_results( $sqlStatement);
foreach( $rows as $row ) {		
      $displayMonths =$row->display_months;
      $downloadDate = $row->download_date;
}

$numDaysGoingForward = $displayMonths*30;
?>


<div class="section-devider"> 


<?php
if( $display_heading ) {

?>
<div class="row"> 

<div class="column1">

<h3><?php echo ($sectionTitle); ?></h3>
<br>

<?php 

if( !empty( $image ) ) {
?>
</div>
<div class="column2">

    <img src="<?php echo esc_url($image['url']); ?>" alt="<?php echo esc_attr($image['alt']); ?>" width="300" height="200"/>
<?php } ?>
</div>
</div>
<br>
<hr>

<?php
}

    displayTrainingCalendar();
?>    
    </div>

    <div class="center"> 
<?php 
    if( current_user_can('editor') || current_user_can('administrator') ) {
?>
        <a href="<?php echo site_url('monthlymaintenancepage/#form'); ?>" target="_self">
        **** Update Homepage Content (e.g. Training Calendar, Resource of the Month, etc.) ***</a><br>

<?php }

?>

</div>

