<?php
/**
 * 
 *
 * @package      Advanced Custom Fields (ACF) Custom Blocks 
 * 
 *                  Custom Post Type: This routine is not associated with a CPT
 *                  Custom Fields\Field Group name for field definitions: N/A
 *                  Custom Fields block name: "Colored Text Box"
 *                  
 *                  URL: This routine is used on this page: homepage url/ada_overview/
 * 
 * @author       Patrick Moriarty
 * @since        1.0.0
 * @license      GPL-2.0+
 
 * 
 * 
 * This code is registered and linked to the block name (listed above) in the function pm_register_blocks().  To display the records for this CPT on a page, you
 * simply add the block to a page with the WordPress block editor. 
 * 
 * 
**/

$background=get_field('background_color');
//$text=get_field('text');
//$url=get_field('link');
$textBox=get_field('textbox');


//display_coloredBox($text, $background, $text_color, $url, $textBox);

?>

<style> 
.center {
  border-radius: 25px;
  margin: auto;
  width: 80%;
  padding: 10px;      
}
/* mouse over link */
a:hover {
  color: yellow;
}

/* selected link */
a:active {
  color: blue;
}

</style>

<div style="background-color:<?php echo $background; ?>;" class="center">
    
<?php

// echo '<pre>';
// print_r( 'link>>>>>'.$link.'<<<<' );
// echo '</pre>';
?>

<p><?php echo $textBox; ?></p>


</div>

<?php
