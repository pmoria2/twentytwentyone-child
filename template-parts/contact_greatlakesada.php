<?php
/**
 * 
 *
 * @package      Advanced Custom Fields (ACF) Custom Blocks 
 * 
 *                  Custom Post Type: "Great Lakes Associations"
 *                  Custom Fields\Field Group name for field definitions: "ADA Resource"
 *                  Custom Fields block name: "ADA_Resource_DisplayAssoc", "ADA_Resource_Display"
 *                  Displays contact information for ADA National Network or Great Lakes ADA Partners.
 *                  You add the block and then choose the post, with the contact info you wish to display, from a list
 *                  URL: homepage url/contact_us/
 * 
 * @author       Patrick Moriarty
 * @since        1.0.0
 * @license      GPL-2.0+
 
 * 
 * This routine displays records for the custom post type (CPT) listed above.  Custom Post types are defined under the 'CPT UI' menu choice in the WordPress admin 
 * menu.  The fields for the CPT that this routine reads in and outputs are defined under the Custom Fields\Field Group name for field definitions (listed above). 
 * This Custom Fields\Field Group is also where the fields are linked to the CPT. There is a menu choice for the custom post type in the WordPress Admin menu, where
 * you can go to add records (or posts).  
 * 
 * This code is registered and linked to the block name (listed above) in the function pm_register_blocks().  To display the records for this CPT on a page, you
 * simply add the block to a page with the WordPress block editor. 
 * 
 * 
**/
global $post;
$post= get_field('contact_record');

?>


<div class="section-devider"> 
<h3><?php echo ('Telephone:'); ?></h3><br>

<p> <?php if (! empty(get_field('phone1_number', $post->ID)) ) { echo (get_field('phone1_number', $post->ID).' ('.get_field('phone1_label', $post->ID).')'); } ?> </p>
<p> <?php if (! empty(get_field('phone2_number', $post->ID)) ) { echo (get_field('phone2_number', $post->ID).' ('.get_field('phone2_label', $post->ID).')'); } ?> </p>
<p> <?php if (! empty(get_field('phone3_number', $post->ID)) ) { echo (get_field('phone3_number', $post->ID).' ('.get_field('phone3_label', $post->ID).')'); } ?> </p>
<p> <?php if (! empty(get_field('phone4_number', $post->ID)) ) { echo (get_field('phone4_number', $post->ID).' ('.get_field('phone4_label', $post->ID).')'); } ?> </p>

<br><br>

<h3><?php echo ('Mailing Address:'); ?></h3><br>

<p> <?php echo wpautop(get_field('full_name', $post->ID)); ?></p>
<p> <?php echo get_field('address', $post->ID); ?></p>
<p> <?php if (! empty(get_field('city', $post->ID)) ) { echo (get_field('city', $post->ID).', '); } ?> <?php echo get_field('state', $post->ID); ?> <?php echo get_field('zip', $post->ID); ?> </p>
<br>
</div>


<?php



// echo '<pre>';
// print_r(getContactPhone());
// echo '</pre>';
// die();



    // echo '<pre>';
    // print_r($post);
    // echo '</pre>';

  


    //$title = the_title();



//wp_reset_postdata(); 








?>

<!-- 

<div class="section-devider"> 

	<hr>
</div>

 -->

<?php


// echo '<pre>';
//     print_r( get_field('featured_post')  );
// echo '</pre>';
// die;



?>