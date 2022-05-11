<?php
/**
 * 
 *
 * @package      Advanced Custom Fields (ACF) Custom Blocks 
 * 
 *                  Custom Post Type: This routine is not linked to a CPT
 *                  Custom Fields\Field Group name for field definitions: N/A
 *                  Custom Fields block name: "online_resource_repository"
 *                  Displays records from table online_resource_repository_import".
 *                  URL: ADA Great Lakes homepage/resource_repository/ 
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
    width: 65%;
  }
  .alignfull {
	margin: 32px calc(50% - 50vw);
	max-width: 100vw;
	width: 100vw;
}
.alignwide {
	margin: 32px calc(25% - 25vw);
	max-width: 90vw;
	width: 90vw;
}
  </style>

<?php
global $wpdb;


$today = date('Ymd');
$date1=date_create($today);

$heading=get_field('heading');
$description=get_field('description');
// $upload_file=get_field('upload_file');

// echo '<pre>';
//     print_r( $upload_file );
// echo '</pre>';
// die;


?>

<div class="alignfull">  
<div class="center">  

<h1 class="has-text-align-center"><?php echo ($heading); ?></h1>
<br>
<p><?php echo ($description); ?></p>
<?php 
    $i=6;
    CreateUrls("online_resource_repository_import");
?>    
    </div>
    </div>

