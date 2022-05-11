<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package WordPress
 * @subpackage Twenty_Twenty_One
 * @since Twenty Twenty-One 1.0
 */
?>
<!-- CSS code -->

<style type="text/css">
.row {
	display: flex;
  }
  
  .column1 {
	flex: 70%;
  }


  .column2 {
	flex: 30%;
  }

</style>


<?php

get_header();

$description = get_the_archive_description();
?>

<?php if ( have_posts() ) : ?>

	<header class="page-header alignwide">
		<h1  style="text-align:center">Supreme Court Cases</h1>
		<div class="archive-description"></div>

		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>

		
	</header><!-- .page-header -->

 <div class="entry-content">

<div id="archive-filters1" class="row alignwide">	
<div id="filter-ada_specialty1" class="column1">

<h3>Case Category</h3>
<br>

<?php echo do_shortcode( '[facetwp facet="case_category"]' ); ?>
<br>

</div>

<div id="filter-ada_specialty2" class="column2">

<h3>Year</h3>
<br>

<?php echo do_shortcode( '[facetwp facet="year"]' ); ?>
<hr>
<br>
</div>
</div>
<hr>
<br>

	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h3 class="entry-title"><?php the_title(); ?></h3>
	<br>
		<!-- Let's show our custom fields here -->
		<p> <?php the_field('description'); ?></p>
    <br>
	<p> <?php if (! empty(get_field('year')) ) { echo 'Year: '.(get_field('year')['label']); } ?> </p>

<?php

// $term=get_field('year');
// echo '<pre>';
// 	print_r( $term);
// echo '</pre>';
// die;		

?>
	
	
	<?php 
            $terms = get_field('case_category');

			//$ada_specialty =  $term->name;
            if( $terms) { 

			?>	<br>
				<p>Case Category:</p>
				<ul> 
				<?php foreach ( $terms as $term )   { ?>
				<li><?php echo $term->name; ?></li>
			<?php } ?>

			    </ul>

<?php		

				}
// echo '<pre>';
//     print_r( $term->name );
// 	print_r( $term);
// echo '</pre>';
// die;		
			?>

	</article>
	<br>

	<?php endwhile; ?>
	<p style="text-align:center"><?php echo do_shortcode( '[facetwp facet="pager_"]' ); ?></p>
	
    </div>
	
	<!-- twenty_twenty_one_the_posts_navigation(); -->

<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

<?php get_footer(); ?>
