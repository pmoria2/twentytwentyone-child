<?php
/**
 * 
 *
 * @package      Advanced Custom Fields (ACF) Custom Blocks 
 * 
 *                  Custom Post Type: This routine is not linked to a CPT
 *                  Custom Fields\Field Group name for field definitions: N/A
 *                  Custom Fields block name: "Display dataTable TP"
 *                  Displays records from MySQL view transition_plans_v (see MySQL script: ada_great_lakes_transition_plan_basicStuff.sql).
 *                  URL: ADA Great Lakes homepage/transition-plans/
 * 
 * @author       Patrick Moriarty
 * @since        1.0.0
 * @license      GPL-2.0+
 
 * 
 * This code is registered and linked to the block name (listed above) in the function pm_register_blocks().  To display the records for this CPT on a page, you
 * simply add the block to a page with the WordPress block editor. 
 * 
 * 
**/
global $wpdb;


define('_state_cd', 0);
define('_unit_type', 1);
define('_census_region', 2);
define('_pop_group', 3);
define('_income_group', 4);
define('_plan_decade', 5);
define('_req_met_percentile', 6);
define('_best_prct_percentile', 7);


/*

$searchFields[_unit_type][value]

*/


  $file = get_stylesheet_directory()."/log/wp_tablecode.log";
  date_default_timezone_set("America/Chicago");
  $record="\n\n *** Update GreatLakesADA for ". date("Y-m-d h:i:sa")."\n";
  file_put_contents($file, $record);


$pageTitle = "ADA Transition Plans";

  
  /* Search Field parameters and data */

$searchFields=array(array('fieldname'=>'state_cd', 'label'=>'State Abbrev.', 'value'=>'', 'data'=>[]),
                    array('fieldname'=>'unit_type', 'label'=>'Unit Type', 'value'=>'', 'data'=>[]),
                    array('fieldname'=>'census_region', 'label'=>'Census Region', 'value'=>'', 'data'=>[]),
                    array('fieldname'=>'pop_group', 'label'=>'Population Group', 'value'=>'', 'data'=>[]),
                    array('fieldname'=>'income_group', 'label'=>'Income Group', 'value'=>'', 'data'=>[]),                    
                    array('fieldname'=>'plan_decade', 'label'=>'Most recent plan', 'value'=>'', 'data'=>[]),
                    array('fieldname'=>'req_met_percentile', 'label'=>'Requirements Met', 'value'=>'', 'data'=>[]),
                    array('fieldname'=>'best_prct_percentile', 'label'=>'Best Practice Met', 'value'=>'', 'data'=>[]));                  


                    $searchcount = count($searchFields);
        
$i = 0;
while ($i < $searchcount)
{
  if( isset($_GET[ $searchFields[$i]['fieldname'] ]) ) {
    $param = $_GET[ $searchFields[$i]['fieldname'] ];
  
  } else {
    $param="";
  }
  $searchFields[$i]['value']=$param;
  $searchFields[$i]['data']=getFieldValues ($searchFields[$i]['fieldname'], $file);

  $i++;
}

  
// echo '<pre>';
//     print_r( $searchFields );
// echo '</pre>';
// die;



?>

<!-- CSS code -->

<style type="text/css">
table {
table-layout: auto;
width: 100%;  
margin: 4px;
border-collapse: collapse;
border-spacing: 2px;
border-color: gray
   
}


col {
  display: table-column;
} 




th {
font-family: Arial, Helvetica, sans-serif;
font-size: 1.0em;
background: #666;
color: #FFF;
padding: 2px 3px;
border-collapse: separate;
border: 1px solid #000;
text-align:left;
}

td {
font-family: Arial, Helvetica, sans-serif;
font-size: 1.0em;
border: 1px solid #DDD;
overflow: hidden;
white-space: nowrap;
text-overflow: ellipsis;
}


.scrollit {
    overflow:scroll;
    height:650px;
    max-width: 800;
    /*background-color: powderblue;*/
}

.btn-group button {
  /*background-color: #4CAF50; /* Green background */
  /* border: 1px solid green; Green border */
  /*color: white; /* White text */
  /*padding: 10px 24px;  Some padding */
  cursor: pointer; /* Pointer/hand icon */
  float: left; /* Float the buttons side by side */
  font-family: Arial, Helvetica, sans-serif;
  font-size: 1.0em;
  /*border: 1px solid #DDD;*/
  width: 100%;
  table-layout: fixed;
  border-collapse: collapse;
}


/* Clear floats (clearfix hack) */
.btn-group:after {
  content: "";
  clear: both;
  display: table;
}

.btn-group button:not(:last-child) {
  border-right: none; /* Prevent double borders */
}

/* Add a background color on hover */
.btn-group button:hover {
  /*background-color: #3e8e41;*/
}

.column {
  float: left;
  width: 50%;
  padding: 10px;
  column-gap: 20px;
  column-width:100%;
}

.column3 {
  float: left;
  width: 20%;
  padding: 10px;
  column-gap: 20px;
  column-width:100%;
}
.column4 {
  float: left;
  width: 30%;
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
  width: 100%;
}

caption {
    text-align:left;
}

.center {
  margin: auto;
  width: 70%;
  padding: 10px;
}
nav { text-align: center }

/* 
p {
  font-size: 18px;
}
 */


/* Responsive layout - makes the two columns stack on top of each other instead of next to each other on screens that are smaller than 600 px */
@media screen and (max-width: 800px) {
  .column {
    width: 100%;
  }
}
@media screen and (max-width: 1022px) {
  .column4 {
    width: 100%;
  }
}

@media screen and (max-width: 1022px) {
  .column3 {
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
      

<h1  style="text-align:center"><?php echo ($pageTitle); ?></h1>
<br>

<div class="center">
<p>Municipalities and counties with 50 or more employees are required to create a document, called a transition plan to identify problems with sidewalks and intersections, and plan for how to remove problems so that they are easier to use for people with disabilities. </p>
<br>
<p>Below is a table of communities that have developed a plan. You can browse the records and click on the name of the community to bring their record up in the Current Record column, located directly to the right of the table. The record includes information about the size of the community, the number of people 65 and older, the number of people with disabilities, etc.  There is a link in the current record to the community’s website and transition plan.</p>
<br>
<p>Above the table and current record columns is a form you can use to filter records by various criteria.  Choose from the menu below to go directly to the table and browse records or to the form where you can narrow your search.  Or scroll down to learn more about some of the fields in the table and the search criteria.</p>
<br>
<hr>
<br>
<nav>
<a href="#top_of_table">Table</a> | 
<a href="#search">Form</a>
</nav>
<br>
<hr class="wp-block-separator is-style-wide is-style-dots">
<br>
<p>Listed below are the fields in the form and the data they contain, that you can choose from. The number in parentheses is the number of records where the field contains that value. At the time of this writing, we have 102 municipalities and counties, with transition plans from 33 states. You can choose from all 33, but for the sake of brevity, listed below are the states that have 3 or more localities in our dataset.</p>
<br>
</div>

<div class="row">

<div class="column3">

<?php
      $i = 0;
      while ($i <=0 )
      { 
?>
        <div class="row">
        <h5><?php echo $searchFields[$i]['label']; ?></h5>  
        <ul>  
<?php
        $j = 0;
        $selectioncount=count($searchFields[$i]['data']);
        while ($j < $selectioncount)
        { 
          
          if ($searchFields[$i]['data'][$j][1]>2) { ?>
          <li><?php echo $searchFields[$i]['data'][$j][0]." (".$searchFields[$i]['data'][$j][1].")"; ?></li>
<?php     }      
          $j++;
        }  ?>
        </ul>
        </div><br>
<?php           
        $i++;   
  } 
?>   
  </div>


<div class="column4">

<?php
      $i = 1;
      while ($i <=2 )
      { 
?>
        <div class="row">
        <h5><?php echo $searchFields[$i]['label']; ?></h5>  
        <ul>  
<?php
        $j = 0;
        $selectioncount=count($searchFields[$i]['data']);
        while ($j < $selectioncount)
        { ?>
          <li><?php echo $searchFields[$i]['data'][$j][0]." (".$searchFields[$i]['data'][$j][1].")"; ?></li>
<?php           
          $j++;
        }  ?>
        </ul>
        </div><br>
<?php           
        $i++;   
  } 

  $i = 6;
  while ($i <=6 )
  { 
?>
    <div class="row">
    <h5><?php echo $searchFields[$i]['label']; ?></h5>  
    <ul>  
<?php
    $j = 0;
    $selectioncount=count($searchFields[$i]['data']);
    while ($j < $selectioncount)
    { ?>
      <li><?php echo $searchFields[$i]['data'][$j][0]." (".$searchFields[$i]['data'][$j][1].")"; ?></li>
<?php           
      $j++;
    }  ?>
    </ul>
    </div><br>
<?php           
    $i++;   
} 

?>   
  </div>
  <div class="column4">

<?php
      $i = 3;
      while ($i <=3 )
      { 
?>
        <div class="row">
        <h5><?php echo $searchFields[$i]['label']; ?></h5>  
        <ul>  
<?php
        $j = 0;
        $selectioncount=count($searchFields[$i]['data']);
        while ($j < $selectioncount)
        { ?>
          <li><?php echo $searchFields[$i]['data'][$j][0]." (".$searchFields[$i]['data'][$j][1].")"; ?></li>
<?php           
          $j++;
        }  ?>
        </ul>
        </div><br>
<?php           
        $i++;   
  } 

  $i = 7;
  while ($i <=7 )
  { 
?>
    <div class="row">
    <h5><?php echo $searchFields[$i]['label']; ?></h5>  
    <ul>  
<?php
    $j = 0;
    $selectioncount=count($searchFields[$i]['data']);
    while ($j < $selectioncount)
    { ?>
      <li><?php echo $searchFields[$i]['data'][$j][0]." (".$searchFields[$i]['data'][$j][1].")"; ?></li>
<?php           
      $j++;
    }  ?>
    </ul>
    </div><br>
<?php           
    $i++;   
} 


?>   
  </div>
  <div class="column3">

<?php
      $i = 4;
      while ($i <=5 )
      { 
?>
        <div class="row">
        <h5><?php echo $searchFields[$i]['label']; ?></h5>  
        <ul>  
<?php
        $j = 0;
        $selectioncount=count($searchFields[$i]['data']);
        while ($j < $selectioncount)
        { ?>
          <li><?php echo $searchFields[$i]['data'][$j][0]." (".$searchFields[$i]['data'][$j][1].")"; ?></li>
<?php           
          $j++;
        }  ?>
        </ul>
        </div><br>
<?php           
        $i++;   
  } 


?>   
  </div>

</div>
<div class="row">
<br>
<p>To keep the page anchored at the form or the table while perusing records choose from the menu below: </p>
<br>
<hr>
<br>
<nav>
<a href="#top_of_table">Table</a> | 
<a href="#search">Form</a>
</nav>
<br>
<hr class="wp-block-separator is-style-wide is-style-dots">
<br>
</div>

<div id="search"></div>
<br>
<form>
<h3  style="text-align:center">Search Transition Plans</h3>
<div class="row"> 
<?php
      $i = 0;
      while ($i < $searchcount)
      { 
        if ($i==4) { ?>
          </div>
          <div class="row">
<?php
        }
        
        ?>

        <div class="column3"> 
        <label for="<?php echo $searchFields[$i]['fieldname']; ?>"><?php echo $searchFields[$i]['label']; ?>:</label>   
        <select name="<?php echo $searchFields[$i]['fieldname']; ?>" id="<?php echo $searchFields[$i]['fieldname']; ?>" >
          <option value="" <?php echo selected($searchFields[$i]['value'],""); ?>>---</option>
    <?php 
      // load all the options into the select box
        $j = 0;
        $selectioncount=count($searchFields[$i]['data']);
        $record="selectioncount:".$selectioncount.", i=".$i."\n";
        file_put_contents($file, $record, FILE_APPEND);		
  
        while ($j < $selectioncount)
        { ?>

          <option value="<?php echo $searchFields[$i]['data'][$j][0]; ?>" <?php echo selected($searchFields[$i]['value'], $searchFields[$i]['data'][$j][0]); ?>><?php echo $searchFields[$i]['data'][$j][0]; ?></option>
      
   <?php 
          $record="Search Data:".$searchFields[$i]['data'][$j][0].", Search value=".$searchFields[$i]['value']."\n";
          file_put_contents($file, $record, FILE_APPEND);		

          $j++;
       }  ?>
  </select>
</div>
<?php 
    $i++;   
  } ?>

<div class="column3"> 
<!--<input type="submit" value="Submit"> -->
  <br><button class="submit" type="submit">Apply</button>
</div>  
</div>  
  </form>

  <div class="row">

  <div class="column">
  <div id="top_of_table"></div>
  <br>
  <h3  style="text-align:center">Transition Plan Table</h3>
  <hr>
  
  <?php 


     $district_id=displayTable3 ($searchFields[_state_cd]['value'], $searchFields[_unit_type]['value'], $searchFields[_census_region]['value'], $searchFields[_pop_group]['value'], $searchFields[_income_group]['value'], $searchFields[_plan_decade]['value'], $searchFields[_req_met_percentile]['value'], $searchFields[_best_prct_percentile]['value'], $file);

    //$district_id=displayTable3 ($searchFields[_state_cd][value], $searchFields[_unit_type][value], $searchFields[_census_region][value], $searchFields[_pop_group][value], $searchFields[_income_group][value], $searchFields[_plan_decade][value], $searchFields[_audit_quality][value], $file);
    $record="Return value from displayTable3=".$district_id."\n";
    file_put_contents($file, $record, FILE_APPEND);

   ?>

  <p style="text-align:center"><a href="<?php echo site_url('transition-plans/#search'); ?>" target="_self">Clear Transition Plan Search Form</a></p>

</div>

<div class="column" id="ajax-target">


<div id="top_of_record"></div>
<br>
<h3  style="text-align:center">Current Record</h3>
<hr>
<?php
	if ($district_id<>"") {
   displayTable4 ($district_id, $file);
  }
?>
</div>

</div>

<hr>
<?php

// echo '<pre>';
//     print_r( get_field('post_objects')  );
// echo '</pre>';
// die;





/* ************************************************ */


?>