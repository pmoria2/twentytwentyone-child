<?php
/**
 *                  Custom Post Type: "Video of the Month"
 *                  Custom Fields\Field Group name for field definitions: "Video of the Month"
 *                  Custom Fields block name: "Video Display"
 *                  This block is located on the ADA Great Lakes homepage
 * 
 * @author       Patrick Moriarty
 * @since        1.0.0
 * @license      GPL-2.0+
 
 * 
 * This routine displays records for the custom post type (CPT) listed above.  Custom Post types are defined under the 'CPT UI' menu choice in the WordPress admin 
 * menu.  The fields for the CPT that this routine reads in and outputs are defined under the Custom Fields\Field Group name for field definitions (listed above). 
 * This Custom Fields\Field Group is also where the fields are linked to the CPT. There is a menu choice for the custom post type in the WordPress Admin menu, where
 * you can go to add or edit records (or posts).  
 * 
 * This code is registered and linked to the block name (listed above) in the function pm_register_blocks().  This particular type of block lets you choose just one
 * post to output.  To display the post you simply add the block to a page with the WordPress block editor. 
 * 
 * 
**/
?>

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

/* Responsive layout - makes the two columns stack on top of each other instead of next to each other on screens that are smaller than 600 px */
@media screen and (max-width: 800px) {
  .center {
    width: 100%;
  }
}

.center {
    display: block;
    margin-left: auto;
    margin-right: auto;
    width: 90%;
    padding: 10px;
  }

</style>
<?php

global $post;
$post_video = get_field('video_of_the_month');
$videos=get_field('video_def', $post_video);
$numVideos = count($videos);
$heading=$post_video->post_title;

    // echo '<pre>';
    // print_r($post_video);
    // echo '</pre>';

    $file = get_stylesheet_directory()."/log/video_of_the_month.log";
    date_default_timezone_set("America/Chicago");
    $record="\n\n *** Update GreatLakesADA for ". date("Y-m-d h:i:sa")."\n";
    file_put_contents($file, $record);
  
    $numVideos=0;
    while ( have_rows('video_def', $post_video) ) : the_row();
    $display=get_sub_field('display');
    if ($display) {
        $numVideos++;
    }    
    endwhile;

 if ($numVideos > 0){
 
 if ($numVideos > 1){
    $heading="This Month's Videos";
 }
 
  ?>
<div class="center">

  <h3><?php echo $heading; ?></h3> 

<?php

while ( have_rows('video_def', $post_video) ) : the_row();
								
$display=get_sub_field('display');
if ($display) {
  //$NumElements++;
  $video=get_sub_field('video');			
  $caption=get_sub_field('caption');
  $alt_text=get_sub_field('alt_text');
  $attributes = 'alt="'.$alt_text.'"';
  $iframe = str_replace('></iframe>', ' ' . $attributes . '></iframe>', $video);
?>

<hr><br>
<div class="embed-container">
  <?php echo $iframe;?>
</div>
<?php 
    // echo '<pre>';
    // print_r();
    // echo '</pre>';
?>
<?php 
if (! empty($caption)) { ?>
<p><?php echo $caption; ?></p>
<?php } ?>


<?php  
}		

endwhile; ?>
<hr><br>
</div>


<?php } // if ($numVideos > 0){
?>



