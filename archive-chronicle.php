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
		<h1  style="text-align:center">The Great Lakes Cronicle</h1>
		<div class="archive-description"></div>

		<?php if ( $description ) : ?>
			<div class="archive-description"><?php echo wp_kses_post( wpautop( $description ) ); ?></div>
		<?php endif; ?>
		
	</header><!-- .page-header -->

<div class="entry-content">

	<?php 
	$volumeSave = 0;
	while ( have_posts() ) : ?>
		<?php the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>


	<?php
	
	$volume_issue=get_field('volume_issue');
	$volume=get_field('volume');
	$issue=get_field('issue');
	// $volume = (int) $volume_issue;
	// $issue = ($volume_issue - $volume) * 100;
	// $issue = (int)($issue + .01);

	if ($volumeSave <> $volume) {
		// Output volume information
		if ($volumeSave<>0) {
			// if this isn't the first time in, create some space
		?>
		<br>
		<hr>
		<br>

		<?php } 

		$volumeSave = $volume;

		?>
		<h3 style="text-align:left"> <?php echo "Volume: ".$volume; ?></h3>
		<hr>
		<br>

	<?php } ?>

	<h4><a href="<?php the_permalink(); ?>" > <?php echo "Issue: ".$issue." - ".get_field('month'); ?></a></h4>



	


<?php		

// echo '<pre>';
//     print_r( $term->name );
// 	print_r( $term);
// echo '</pre>';
// die;		
			?>

	</article>

	<?php endwhile; ?>
	
    </div>
	
	<!-- twenty_twenty_one_the_posts_navigation(); -->

<?php else : ?>
	<?php get_template_part( 'template-parts/content/content-none' ); ?>
<?php endif; ?>

<?php get_footer(); ?>
