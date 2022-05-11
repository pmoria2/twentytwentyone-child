<?php
/**
 * 
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress and Advanced Custom Fields (ACF) Custom Blocks 
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 *
 *                  Custom Post Type: "Great Lakes Chronicle"
 *                  Custom Fields\Field Group name for field definitions: "chronicle"
 *                  Custom Fields block name: This routine is not associated with a block
 *                  Displays chronicle issues via the Homepage "e-News" link and via the "Great Lakes Chronicle Archive"
 *                  (see also archive-chronicle.php and single-chronicle.php)
 *                  URL: homepage url/chronicle/march-2022/ (homepage url/chronicle/month-YYYY/)
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
 * 
**/





?>

<!-- CSS code -->
<!-- <style type="text/css"> -->

<style>

.column {
  float: left;
  width: 80%;
  column-width:100%;
}

.column_right {
  float: left;
  width: 15%;
  column-width:100%;
  padding-top: 550px;
  padding-left: 20px;
}


.center {
  margin: auto;
  width: 80%;
  padding: 10px;
}

.center2 {
  margin: auto;
  width: 70%;
  padding: 10px;
}


  .alignfull {
	margin: 32px calc(50% - 50vw);
	max-width: 100vw;
	width: 100vw;
}


/* Responsive layout - makes the two columns stack on top of each other instead of next to each other on screens that are smaller than 600 px */

@media screen and (max-width: 482px) {
  .column {
    width: 100%;
  }
}
@media screen and (max-width: 482px) {
  .column_right {
    width: 100%;
  }
}

@media only screen and (min-width: 482px) {
	:root {
		--responsive--aligndefault-width: min(calc(100vw - 4 * var(--global--spacing-horizontal)), 1110px);
	}
}
@media only screen and (min-width: 822px) {
	:root {
		--responsive--aligndefault-width: min(calc(100vw - 8 * var(--global--spacing-horizontal)), 1110px);
	}
}
</style>



<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<header class="entry-header alignwide">
		<?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
		<?php twenty_twenty_one_post_thumbnail(); ?>
	</header><!-- .entry-header -->
	
	<div class="alignfull" >
	<div class="center" >
	<h1 style="text-align:center" >Great Lakes Chronicle</h1><br>

		<?php
		//the_content();
?>
		<!-- Let's show our custom fields here -->

		
	<p> <?php the_field('month'); ?></p>
	<p style="text-align:right"> <?php echo "Volume: ".get_field('volume').", Issue: ". get_field('issue'); ?></p>

	<div class="column" >
	
	<?php chronicle_displayfields(get_the_ID()); ?>

	</div>

	<div class="column_right" >
		<a href="<?php echo site_url('chronicle'); ?>" target="_self">
			Chronicle Archive</a><br>
	</div>

<?php
// echo '<pre>';
//     print_r( $term->name );
// 	print_r( $term);
// echo '</pre>';
// die;		

		wp_link_pages(
			array(
				'before'   => '<nav class="page-links" aria-label="' . esc_attr__( 'Page', 'twentytwentyone' ) . '">',
				'after'    => '</nav>',
				/* translators: %: Page number. */
				'pagelink' => esc_html__( 'Page %', 'twentytwentyone' ),
			)
		);
		?>
</div>
</div>



	<footer class="entry-footer default-max-width">
		<?php twenty_twenty_one_entry_meta_footer(); ?>
	</footer><!-- .entry-footer -->

	<?php if ( ! is_singular( 'attachment' ) ) : ?>
		<?php get_template_part( 'template-parts/post/author-bio' ); ?>
	<?php endif; ?>

</article><!-- #post-<?php the_ID(); ?> -->
