<?php
/**
 * 
 *
 * @package      Advanced Custom Fields (ACF) Custom Blocks 
 * 
 *                  Custom Post Type: "YouTube Showcase"
 *                  Custom Fields\Field Group name for field definitions: "YouTube Showcase"
 *                  Custom Fields block name: "Youtube Showcase" and "Youtube View"
 *                  url: homepage/youtube-showcase/
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
  width: 50%;
  padding: 10px;
}

</style>
<?php
$title = get_field('heading');

?>

<article class="alignwide">

<h1 style="text-align:center" ><?php echo $title; ?></h1>
<br />
<br />

<?php 
// create our video category menus

$terms = get_terms([
  'taxonomy' => 'video_category'
]);

$categoryMenu='<p style="font-size:20px;text-align:center">';

?>
<div class="center">
<p>Our videos are organized into sections. Choose from the menu below to jump to that section, or just scroll through them all.</p>
<br />
<ul>

<?php 
foreach( $terms as $term ) {
?>
  <li><a href=<?php echo '#'.$term->slug; ?>><?php echo (empty($term->description)) ? $term->name : $term->name.' - '.$term->description; ?></a></li>
  
<?php
  // While we're at it, let's build our horizontal menue
  $categoryMenu=$categoryMenu.' | <a href="#'.$term->slug.'">'.$term->name.'</a> ';
}

$categoryMenu=$categoryMenu.' | ';

?>
</ul>
</div>
<br />
<br />
<hr class="wp-block-separator is-style-dots"/>
  

<?php 

// Output the videos!!

foreach( $terms as $term ) {

$columnNum=0;
$rowNum=0;


$args2 = array(  
  'post_type' => 'youtube_showcase', 
  'posts_per_page' => -1, 
  'orderby'			=> 'title',
  'order'				=> 'ASC',
  'tax_query' => array( 
      array( 
          'taxonomy' => 'video_category', //or tag or custom taxonomy
          'field' => 'name', 
          'terms' => $term->name
      ) 
  ) 
);


   /* The 2nd Query */
   $query2 = new WP_Query( $args2 );
   
    
if ($query2->have_posts())  {
  

  ?>


  <div id="<?php echo $term->slug; ?>"></div>
  <br />
  <h2 style="text-align:center" ><?php echo $term->name; ?></h2>
  <br />
  <hr />
  <br />

  <?php


while ( $query2->have_posts() ) {

	$query2->the_post();

if ($rowNum==0) { 
?>
	<div class="row">
<?PHP
	}	
	elseif ($columnNum==0) { ?>
		</div>
		<div class="row">
<?php
	}	

?>

  <div class="column" >
  	<div class="embed-container">
		<?php the_field('video', $query2->post->ID); ?>
	</div>
  <?php 
    $caption=get_field('caption', $query2->post->ID);    
    if (! empty($caption)) { ?>
      <p style="text-align:center" ><?php echo $caption; ?></p>
  <?php  
  } ?>
  </div>
  
<?php

$rowNum++;
$columnNum=++$columnNum % 2;

}   // End While

// Add the final "row" </div> closing tag
?>
  </div>
  <br />
  <br />
  <hr class="wp-block-separator is-style-dots"/>
  <br />
  <br />
  <hr />
  <?php echo $categoryMenu; ?>
  <br />
  <hr />
  <br />
  <br />
  <hr class="wp-block-separator is-style-dots"/>
  <br />

<?PHP

}  // End If

   // Restore original Post Data
   wp_reset_postdata();

}   // End Big Foreach


   ?>


</article>





