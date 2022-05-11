<?php
/**
 * 
 *
 * @package      Advanced Custom Fields (ACF) Custom Blocks 
 * 
 *                  Custom Post Type: Hyperlink
 *                  Custom Fields\Field Group name for field definitions: Regular Post, Network (or hyperlink) CPT.  Posts are saved in page
 *                  Custom Fields block name: "ADA_Resource_DisplayAssoc", "Display Posts"
 *                  Displays ordinary posts and/or hyperlink posts
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
$repeater = get_field('featured_post');
$repeatNum = count($repeater);
//echo $actionNum;



// echo '<pre>';
// print_r(get_field('featured_post'));
// echo '</pre>';
// die();





if ($repeatNum == 3) :
{	
	//$colcss ='col-sm-6';
	$colcss ="col-md-6";

	//echo $actionNum.'=3';
}
else :
{
	//$colcss ='col-md-6';
	$colcss ="col-sm-2";
	//echo $actionNum.'<>3';
}
endif;
//echo $colcss;

$colcss ="";


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





// check if the repeater field has rows of data
if( have_rows('featured_post') ): ?>

<div class="parent-wrapper">

    
	 
	<?php
	
	
	while ( have_rows('featured_post') ) : the_row();

		$post  = get_sub_field('post');

// echo '<pre>';
//     print_r(  $post );
// echo '</pre>';
// die;

	
		        setup_postdata($post);

				if($post->post_type=='post'): 
					//<?php the_post_thumbnail('thumbnail'); 
					?>
				    <div class="<?php echo $colcss; ?>">
					     <a href="<?php the_permalink(); ?>">
						
					     
						 <h3><?php the_title(); ?></h3> </a> 

	<?php					if (!empty($post->post_excerpt)) { ?>

								<p><?php echo $post->post_excerpt; ?></p>


	<?php					} else
							{  ?>

								<p><?php the_content(); ?></p> 


					<?php	}  ?>


				    </div>

				<?php elseif($post->post_type=='network_links'): 

					displayHyperPost ($post, $colcss );


			endif;

	endwhile; ?>



</div>
	
	<?php wp_reset_query();	 // Restore global post data stomped by the_post(). ?>

<?php
else :

    // no rows found

endif;


// echo '<pre>';
//     print_r( get_field('post_objects')  );
// echo '</pre>';
// die;





?>

















<!-- echo '<pre>';
    print_r( get_field('post_objects')  );
echo '</pre>';
die; -->





<?php 
// echo '<pre>';
// print_r(get_field('gallery'));
// echo '</pre>';
//die();
