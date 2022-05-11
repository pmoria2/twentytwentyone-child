<?php
/**
 * 
 *
 * @package      Advanced Custom Fields (ACF) Custom Blocks 
 * 
 *                  Custom Post Type: "Great Lakes Associations"
 *                  Custom Fields\Field Group name for field definitions: "ADA Resource"
 *                  Custom Fields block name: "ADA_Resource_DisplayAssoc", "Great Lakes Associations"
 *                  Displays records for ADA National Network or Great Lakes ADA Partners, depending 
 *                  on the value entered into the block and read into the variable $display_this.
 *                  URL: homepage url/great_lakes_ada_partners/ and homepage url/ada_national_network/ 
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

$display_this=get_field('display_this');

// echo 'Display this:';

//  echo '<pre>';
//     print_r($display_this);
//  echo '</pre>';

?>


<?php
if($display_this == 1) { 
   //  ADA National Network

   // I pull the description field from the ADA National Network record, for the telephone number
   
   $args=array(
	'post_type'			=> 'ada_resource',
	'posts_per_page'	=> -1,
    'meta_query' => array(
        array(
           'key'     => 'name',
           'compare' => '=',
           'value'   => 'ADA National Network',
       )
        )
);
   // The Query
   $query1 = new WP_Query( $args );
   if ($query1->have_posts())  {

   ?>

   <p> <?php echo get_field('description', $query1->post->ID); ?></p>
   
   <?php
   }
   else {
    ?>

    <h2> <?php echo 'Error, Missing ADA National Network Record!'; ?></h2>
    
    <?php

   }

   /* Restore original Post Data 
    * NB: Because we are using new WP_Query we aren't stomping on the 
    * original $wp_query and it does not need to be reset with 
    * wp_reset_query(). We just need to set the post data back up with
    * wp_reset_postdata().
    */
   wp_reset_postdata();
    
    

   $args2 = array(
    'post_type' => 'ada_resource',
    'tax_query' => array(
        array(
            'taxonomy' => 'groups_and_members',
            'field'    => 'slug',
            'terms'    => 'ada_national_network_member',
        ),
    ),
   'meta_key'			=> 'name',
   'orderby'			=> 'meta_value',
   'order'				=> 'ASC'
);



   /* The 2nd Query */
   $query2 = new WP_Query( $args2 );
    
if ($query2->have_posts())  {

 // The 2nd Loop

   while ( $query2->have_posts() ) {
       $query2->the_post();
       
       ?>

       <div class="section-devider"> 
       <h3><?php echo get_the_title( $query2->post->ID ); ?></h3>
       <br>
       <p> <?php echo get_field('description', $query2->post->ID); ?></p>

       <p> <?php echo wpautop(get_field('full_name', $query2->post->ID)); ?></p>
       <p> <?php echo get_field('address', $query2->post->ID); ?></p>
       <p> <?php if (! empty(get_field('city', $query2->post->ID)) ) { echo (get_field('city', $query2->post->ID).', '); } ?> <?php echo get_field('state', $query2->post->ID); ?> <?php echo get_field('zip', $query2->post->ID); ?> </p>
       
       <p> <?php if (! empty(get_field('phone1_number', $query2->post->ID)) ) { echo (get_field('phone1_number', $query2->post->ID).' ('.get_field('phone1_label', $query2->post->ID).')'); } ?> </p>
       <p> <?php if (! empty(get_field('phone2_number', $query2->post->ID)) ) { echo (get_field('phone2_number', $query2->post->ID).' ('.get_field('phone2_label', $query2->post->ID).')'); } ?> </p>
       <p> <?php if (! empty(get_field('phone3_number', $query2->post->ID)) ) { echo (get_field('phone3_number', $query2->post->ID).' ('.get_field('phone3_label', $query2->post->ID).')'); } ?> </p>

       <a href="<?php echo get_field('website', $query2->post->ID); ?>" target="_blank">
        <?php echo get_field('website', $query2->post->ID); ?></a><br><br>
       </div>

<?php

   }
?>

<?php

   // Restore original Post Data
   wp_reset_postdata();

}
} 
elseif($display_this == 2)
{
   //  Great Lakes ADA Partners


   $args2 = array(
    'post_type' => 'ada_resource',
    'tax_query' => array(
        array(
            'taxonomy' => 'groups_and_members',
            'field'    => 'slug',
            'terms'    => 'great_lakes_ada_partner',
        ),
    ),
   'meta_key'			=> 'state',
   'orderby'			=> 'meta_value',
   'order'				=> 'ASC'
);



   /* The 2nd Query */
   $query2 = new WP_Query( $args2 );
    
if ($query2->have_posts())  {
?>



<?php
 // The 2nd Loop

   while ( $query2->have_posts() ) {
       $query2->the_post();

       $code = wp_oembed_get( get_field('video', $query2->post->ID), array( 'width' => 600 ) );
       
       ?>

<div class="section-devider" id="<?php echo get_field('state', $query2->post->ID); ?>"> 
<br>
<hr>
<br>
       
<h3 style="text-align:center"><?php echo get_the_title( $query2->post->ID ); ?></h3><br>

<hr />
 <p style="text-align:center"><a href="#Introduction">Introduction</a> | <a href="#Illinois">Illinois</a> | <a href="#Indiana">Indiana</a> | <a href="#Michigan">Michigan</a> | <a href="#Minnesota">Minnesota</a> | <a href="#Ohio">Ohio</a>  | <a href="#Wisconsin">Wisconsin</a></p>
<hr />

<br>

<h4><?php echo 'ADA 30th Anniversary #ThanksToTheADA '.get_field('state', $query2->post->ID); ?></h4>

<?php echo $code; ?>
<br>
<p> <?php echo get_field('description', $query2->post->ID); ?></p>
<br><br>



<p>Contact Information:</p>

<p> <?php echo wpautop(get_field('full_name', $query2->post->ID)); ?></p>
<p> <?php echo get_field('address', $query2->post->ID); ?></p>
<p> <?php if (! empty(get_field('city', $query2->post->ID)) ) { echo (get_field('city', $query2->post->ID).', '); } ?> <?php echo get_field('state', $query2->post->ID); ?> <?php echo get_field('zip', $query2->post->ID); ?> </p>

<p> <?php if (! empty(get_field('phone1_number', $query2->post->ID)) ) { echo (get_field('phone1_number', $query2->post->ID).' ('.get_field('phone1_label', $query2->post->ID).')'); } ?> </p>
<p> <?php if (! empty(get_field('phone2_number', $query2->post->ID)) ) { echo (get_field('phone2_number', $query2->post->ID).' ('.get_field('phone2_label', $query2->post->ID).')'); } ?> </p>
<p> <?php if (! empty(get_field('phone3_number', $query2->post->ID)) ) { echo (get_field('phone3_number', $query2->post->ID).' ('.get_field('phone3_label', $query2->post->ID).')'); } ?> </p>

<p> <?php if (! empty(get_field('website', $query2->post->ID)) ) {

   ?>
   
   <a href="<?php echo (get_field('website', $query2->post->ID)); ?>" target="_blank">
							<?php echo (get_field('website', $query2->post->ID)); ?></a>

<?php } ?> </p>

 <br>

<br>

</div>


<?php

   }
?>

<?php

   // Restore original Post Data
   wp_reset_postdata();

}
} else { 

?>

    <h2> <?php echo 'Error, That is strange there should only be 2 associations!'; ?></h2>

<?php


}   // Large End IF

   ?>






