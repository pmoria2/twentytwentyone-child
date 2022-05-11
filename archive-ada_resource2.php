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
		<h1  style="text-align:center">ADA Resources</h1>
		<div class="archive-description"></div>

		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>

		
	</header><!-- .page-header -->

 <div class="entry-content">
<hr>
	 <p>The numbers in parenthathese following each term are the number of records that will be returned when that term is selected.  As you drill down on our list of resources these numbers will decrease, and terms will fall off when their number hits zero. To go back to the original list, deselect the terms.</p>
<hr>

<div id="archive-filters1" class="row alignwide">	
<div id="filter-ada_specialty1" class="column1">

<h3>Resources by Services Provided</h3>
<br>

<?php echo do_shortcode( '[facetwp facet="ada_resource"]' ); ?>
<br>

</div>

<div id="filter-ada_specialty2" class="column2">

<h3>Resources by ADA Great Lakes Member States</h3>
<br>

<?php echo do_shortcode( '[facetwp facet="ada_states"]' ); ?>
<hr>
<br>

<h3>National and Regional Resources</h3>
<br>
<?php echo do_shortcode( '[facetwp facet="national"]' ); ?>
<hr>
<br>
<h3>Filter by First Letter</h3>
<p>If there are no resources beginning with a certain letter (say the letter "X"), then that letter will not be displayed below.  To turn off this filtering, select "Any".</p>
<br>
<?php echo do_shortcode( '[facetwp facet="first_letter"]' ); ?>
<br>
<hr>
<br>
<h3>More ADA resources at these links:</h3>
<br>
<ul>
<li><a href="<?php echo home_url('30-years-of-the-ada') ?>">
Celebrate 30 Years of the Americans with Disabilities Act</a></li>
<li><a href="<?php echo home_url('supreme_court_cases') ?>">
Supreme Court Cases</a></li>
</ul>
<br>
<br>
</div>
</div>
<hr>
	<?php while ( have_posts() ) : ?>
		<?php the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<h3 class="entry-title"><?php the_title(); ?></h3>

		<!-- Let's show our custom fields here -->
	<br>
	<p> <?php the_field('description'); ?></p>
    <br>
	<p> <?php if (! empty(get_field('state')) ) { echo 'Member State: '.(get_field('state')); } ?> </p>
	
	<?php 
            $terms = get_field('regional_and_national');

            if( $terms) { 

			?>	
				<br>
				<p>Regional and National Resources:</p>
				<ul>
				<?php foreach ( $terms as $term )   { ?>
				<li><?php echo $term; ?></li>
			<?php } ?>

			    </ul>

<?php   	} ?>



	<?php 
            $terms = get_field('ada_specialty');

			//$ada_specialty =  $term->name;
            if( $terms) { 

			?>	<br>
				<p>ADA Specialty:</p>
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

	<br>
	<p> <?php if (! empty(get_field('address')) ) { echo 'Address: '.(get_field('address')); } ?> </p>
	<br>

	<p> <?php if (! empty(get_field('voice')) ) { echo 'Voice: '.(get_field('voice')); } ?> </p>
	<p> <?php if (! empty(get_field('tty')) ) { echo 'TTY: '.(get_field('tty')); } ?> </p>
	<p> <?php if (! empty(get_field('fax')) ) { echo 'Fax: '.(get_field('fax')); } ?> </p>
	<p> <?php if (! empty(get_field('email')) ) { echo 'Email: '.(get_field('email')); } ?> </p>
	
	<br>
	<?php if (! empty(get_field('website')) ) { ?>

	<p>Website: <a href="<?php the_field('website'); ?>">
        <?php the_field('name') ?></a></p>

	<?php } ?>

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
