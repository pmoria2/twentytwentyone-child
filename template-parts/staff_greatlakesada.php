<?php
/**
 * 
 *
 * @package      Advanced Custom Fields (ACF) Custom Blocks 
 * 
 *                  Custom Post Type: "Department Staff", "staff"
 *                  Custom Fields\Field Group name for field definitions: "Staff"
 *                  Custom Fields block name: "Staff Display"
 *                  URL: homepage url/staff/
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



// Great Lakes ADA Staff output

// echo 'Display this:';

//  echo '<pre>';
//     print_r($display_this);
//  echo '</pre>';

?>


<?php

$display_this=get_field('display_this');

// echo 'Display this:';

//  echo '<pre>';
//     print_r($display_this);
//  echo '</pre>';

?>


<?php
if($display_this == 1) { 
   // Display staff contact info based on parameters
   $staffphone='';
   $staffphonelabel='';

   if( isset($_GET[ 'attention' ]) and $_GET[ 'attention' ] != 'General Inquiry') {

   
   $args=array(
	'post_type'			=> 'staff',
    'meta_query' => array(
        array(
           'key'     => 'name',
           'compare' => '=',
           'value'   =>  $_GET[ 'attention' ],
       )
        )
);
   // The Query
   $query1 = new WP_Query( $args );
   if ($query1->have_posts())  {
      $staffphone=get_field('phone_number', $query1->post->ID);
      $staffphonelabel=get_field('phone_label', $query1->post->ID);

     


   ?>
   <h2 style="text-align:center"><?php echo 'Contact '.get_field('name', $query1->post->ID); ?></h2>

   <div class="section-devider">
   
   <h3><?php echo ('Mailing Address:'); ?></h3><br>
   <p> <?php echo get_field('name', $query1->post->ID); ?></p>
   <p> <?php echo get_field('title', $query1->post->ID); ?></p>
   
   <?php

   }
   else {
    ?>

    <h2> <?php echo 'Error, Missing Staff Record!'; ?></h2>
    
    <?php

   }
}
else {

   ?>
   <h2 style="text-align:center">Contact Us</h2>
   
   <p>The Great Lakes ADA Center is a member of the ADA National Network. We staff a toll-free information line providing informal guidance on the Americans with Disabilities Act (ADA) and Accessible Information Technology (AIT).</p>


   <div class="section-devider"> 
   <h3><?php echo ('Mailing Address:'); ?></h3><br>
   
   <?php
}

 // Restore original Post Data
  wp_reset_postdata();




   $args=array(
      'post_type'			=> 'ada_resource',
      'meta_query'      => array(
           array(
              'key'     => 'name',
              'compare' => '=',
              'value'   => 'Region 05 - Great Lakes ADA Center',
          )));

      // The Query
      $query1 = new WP_Query( $args );
      if ($query1->have_posts())  {

         
      ?>

          <p> <?php echo wpautop(get_field('full_name', $query1->post->ID)); ?></p>
          <p> <?php echo get_field('address', $query1->post->ID); ?></p>
          <p> <?php if (! empty(get_field('city', $query1->post->ID)) ) { echo (get_field('city', $query1->post->ID).', '); } ?> <?php echo get_field('state', $query1->post->ID); ?> <?php echo get_field('zip', $query1->post->ID); ?> </p>
          <br>

         <h3><?php echo ('Telephone:'); ?></h3><br>
         <?php if ($staffphone != '') { ?> <p> <?php echo $staffphone." ".$staffphonelabel; ?> </p>  <?php } ?>

          <p> <?php if (! empty(get_field('phone1_number', $query1->post->ID)) ) { echo (get_field('phone1_number', $query1->post->ID).' ('.get_field('phone1_label', $query1->post->ID).')'); } ?> </p>
          <p> <?php if (! empty(get_field('phone2_number', $query1->post->ID)) ) { echo (get_field('phone2_number', $query1->post->ID).' ('.get_field('phone2_label', $query1->post->ID).')'); } ?> </p>
          <p> <?php if (! empty(get_field('phone3_number', $query1->post->ID)) ) { echo (get_field('phone3_number', $query1->post->ID).' ('.get_field('phone3_label', $query1->post->ID).')'); } ?> </p>
          <p> <?php if (! empty(get_field('phone4_number', $query1->post->ID)) ) { echo (get_field('phone4_number', $query1->post->ID).' ('.get_field('phone4_label', $query1->post->ID).')'); } ?> </p>
         
          <br>
         </div>
          



<?php
      }
      else  
      { 
         ?>
         
         <h4>Error!  Missing record for Great Lakes ADA Center!</h4><br>
         </div>

<?php
      } 
   
} 
elseif($display_this == 2)
{

   $args2 = array(
    'post_type' => 'staff',
    'posts_per_page'	=> -1,
    'meta_key'			=> 'unit',
    'orderby'			=> 'meta_value',
    'order'				=> 'ASC'
);



   /* The 2nd Query */
   $query2 = new WP_Query( $args2 );
    
if ($query2->have_posts())  {
?>

<h2 style="text-align:center">Great Lakes Staff</h2>


<?php
 // The 2nd Loop

   $unitNumber=0;
   $unit=[];

   while ( $query2->have_posts() ) {
       $query2->the_post();
       //echo '<li>' . get_the_title( $query2->post->ID ) . '</li>';

       
      
       
       $name=get_field('name', $query2->post->ID);
       $link = home_url('staff/?attention=').str_replace(" ","+",$name).'#contact';


//  echo '<pre>';
//     print_r(get_field('unit', $query2->post->ID));
//  echo '</pre>';


       $unit=get_field('unit', $query2->post->ID);

       if ($unitNumber != $unit['value']) {
         $unitNumber=$unit['value'];
         ?>

          <h3><?php echo $unit['label']; ?></h3>

       <?php   
       }
       ?>

      
       <div class="section-devider">


       <a href="<?php echo $link; ?>">
       <p><?php echo $name; ?></p></a>



       <p> <?php echo get_field('title', $query2->post->ID); ?></p>
       </div>

<?php

   }

}
   // Restore original Post Data
   wp_reset_postdata();


}
   ?>






