<?php
/**
 * 
 *
 * @package      Advanced Custom Fields (ACF) Custom Blocks 
 * 
 *                  Custom Post Type: "q_and_a" and "ada_q_and_a"
 *                  Custom Fields\Field Group name for field definitions: "q_and_a"
 *                  Custom Fields block name: "q_and_a_display", "q_and_a", "Questions and Answers"
 *                  URL: homepage url/accessible_technology/
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

$cpt_num=get_field('cpt');

// identify the cpt - custom post type
if ($cpt_num==1) {
  $cpt='q_and_a';
 }
 elseif ($cpt_num==2)  {
   $cpt='ada_q_and_a';
 }

 //echo var_dump($cpt_num, $cpt) . "<br>";

   //  Questions and Answers


   $args2 = array(
   'post_type'          => $cpt,
   'posts_per_page'	    => -1,
   'meta_key'			=> 'question_number',
   'orderby'			=> 'meta_value',
   'order'				=> 'ASC'
);



   /* The 2nd Query */
   $query2 = new WP_Query( $args2 );
    
if ($query2->have_posts())  {
?>

<ol>


<?php
 // The 2nd Loop
   $category=[];
   $categoryNumber=0;


   while ( $query2->have_posts() ) {
       $query2->the_post();
       //echo '<li>' . get_the_title( $query2->post->ID ) . '</li>';

       $category=get_field('category', $query2->post->ID);

       if ($categoryNumber != $category['value']) {
         $categoryNumber=$category['value'];
         ?>

          <br><h3><?php echo $category['label']; ?></h3><br>

       <?php   
       }
       ?>

      <li><a href=<?php echo '#question_'.get_field('question_number', $query2->post->ID); ?>><?php echo get_the_title( $query2->post->ID ); ?></a></li>

<?php

   }
?>


   </ol>


<?php

   // Restore original Post Data
   wp_reset_postdata();


   $args2 = array(
    'post_type'          =>  $cpt,
    'posts_per_page'	    => -1,
    'meta_key'			=> 'question_number',
    'orderby'			=> 'meta_value',
    'order'				=> 'ASC'
 );
 
 
 
    /* The 2nd Query */
    $query2 = new WP_Query( $args2 );


    ?>

    <ol>
    
    
    <?php
     // The 2nd Loop
    
       while ( $query2->have_posts() ) {
           $query2->the_post();
           //echo '<li>' . get_the_title( $query2->post->ID ) . '</li>';
    
           
           ?>
    
    <div class="section-devider" id="<?php echo 'question_'.get_field('question_number', $query2->post->ID); ?>"> 
    <br>
    <hr>
    <br>
           
    <h3><li><?php echo get_the_title( $query2->post->ID ); ?></li></h3>

    <br>
    
    <p> <?php echo get_field('answer', $query2->post->ID); ?></p>
    <br>
    <p style="text-align:right"><a href=<?php echo '#questions'; ?>>Goto back to list of questions</a></p>
    </div>
    
    <?php
    
       }
    ?>
    
    
       </ol>




<?php




}  // Big End If

   ?>






