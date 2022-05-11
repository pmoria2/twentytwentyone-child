<?php

function displayHyperPost_testDatabaseUpdate ($post, $colcss ) {

	//$repeater = get_field('network_links');
	//$repeatNum = count($repeater);


					// collect the tags associated with the links
					$array1=[];
					while ( have_rows('network_links', $post) ) : the_row();
						$tag_ID  = get_sub_field('tag');
						$array1[] = $tag_ID;
					endwhile;

					//$numArray1=count($array1);
					$array1 = array_unique($array1);
					
					// print_r($array1); 

	?>		
					<div class="<?php echo $colcss; ?>">
					<!-- <h3> the_title(); </h3> -->
				
	<?php
								
					foreach ($array1 as &$tag_ID) {

						$tag = get_tag($tag_ID); 
						
	?>
						<h3><?php echo $tag->name; ?></h3>
				

						<ul>
						
	<?php		

						while ( have_rows('network_links', $post) ) : the_row();

						if(  $tag_ID == get_sub_field('tag')): 
							$link=get_sub_field('link');     ?>						
						

							<a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
							<li><?php echo $link['title']; ?></li></a>



							
						<?php endif; 

					    endwhile; ?>

						</ul><br>
							
			<?php	} //end foreach ?>


					</div>

<?php
// clear_resource2_from_db();
// read_table_and_output_to_file();
// update_resource2();
// update_resource2_regional_and_national();
// update_resource2_regional_and_national_test();
// update_resource2_taxonomy_ada_specialty();

	}   // End Function


// echo '<pre>';
//     print_r( get_field('post_objects')  );
// echo '</pre>';
// die;





add_action( 'wp_ajax_nopriv_read_table_and_output_to_file', 'read_table_and_output_to_file' );
add_action( 'wp_ajax_read_table_and_output_to_file', 'read_table_and_output_to_file' );
function read_table_and_output_to_file() {

        global $wpdb;
   
 	    $file = get_stylesheet_directory()."/logMainRoutine.txt";
        
     	$sqlStatement="SELECT * FROM adagreatlakes13_db.resource where status<>0 and name<>'' order by status, ltrim(name) desc;";

		$record="Creating posts!  SqlStatement=".$sqlStatement;
		file_put_contents($file, $record, FILE_APPEND);



        $result = $wpdb->get_results( $sqlStatement);
        

        
      //  $sqlStatement="SELECT id, status, createdBy, lastUpdatedBy, creationDate, lastUpdate, alias, name, website, address, voice, tty, fax, email, otherPhone, description, notesFROM adagreatlakes12_db.resource;"

      foreach ( $result as $print )   {
		$resource_slug = slugify( $print->name . '-' . $print->id );
      
		$inserted_resource = wp_insert_post( [
		  'post_name' => $resource_slug,
		  'post_title' => $print->name,
		  'post_type' => 'ada_resource2',
		  'post_status' => 'publish'
		] );
  
		if( is_wp_error( $inserted_resource ) || $inserted_resource === 0 ) {
			$record="?*?*?*?* Resource NOT inserted!!! inserted_resource = ".$inserted_resource.", resource_slug = ".$resource_slug."!!!\n\n";
			file_put_contents($file, $record, FILE_APPEND);

		  //die('Could not insert brewery: ' . $brewery_slug);
		  //error_log( 'Could not insert resource: ' . $resource_slug );
		  //continue;
		}
  
		// add meta fields
		$fillable = [
			  'field_605244843d6a6' => 'id',
			  'field_605244ef3d6a7' => 'status',
			  'field_605245353d6a9' => 'createdBy',
			  'field_605245613d6aa' => 'creationDate',
			  'field_605245a73d6ab' => 'lastUpdatedBy',
			  'field_605245fb3d6ac' => 'lastUpdate',
			  'field_6052460e3d6ad' => 'alias',
			  'field_605246373d6ae' => 'name',
			  'field_605246423d6af' => 'website',
			  'field_6052465a3d6b0' => 'address',
			  'field_6052466c3d6b1' => 'voice',
			  'field_6052468d3d6b2' => 'tty',
			  'field_6052469c3d6b3' => 'fax',
			  'field_605246a93d6b4' => 'email',
			  'field_605246bf3d6b5' => 'otherphone',
			  'field_605246e53d6b6' => 'description',
			  'field_605247073d6b7' => 'notes',
		];
  
		foreach( $fillable as $key => $name ) {
		  update_field( $key, $print->$name, $inserted_resource );
		}
          
      }

	  //admin-ajax.php?action=read_table_and_output_to_file 

	  return;

 }


  function clear_resource2_from_db() {
  
	global $wpdb;
  
	$wpdb->query("DELETE FROM wp_posts WHERE post_type='ada_resource2'");
	$wpdb->query("DELETE FROM wp_postmeta WHERE post_id NOT IN (SELECT id FROM wp_posts);");
	$wpdb->query("DELETE FROM wp_term_relationships WHERE object_id NOT IN (SELECT id FROM wp_posts)");
  
  }
  // clear_breweries_from_db();



  function update_resource2() {

	global $wpdb;
  
	// echo '<pre>';
	//     print_r(  $result );
	// echo '</pre>';
	// die;
   // $file = get_stylesheet_directory()."/report.txt";
  
	
	//$sqlStatement="SELECT * FROM adagreatlakes12_db.resource order by status, name desc;";
	$file = get_stylesheet_directory()."/report2_states.txt";
	//file_put_contents($file, "Here we go\n\n", FILE_APPEND);

    $sqlStatement="SELECT resource, res_id as id, SUBSTRING(rtrim(category), 1, length(rtrim(category))-5) as state FROM adagreatlakes13_db.resource1_v where (resource1_v.group=2) order by resource asc;";
  
	$result = $wpdb->get_results( $sqlStatement);
	file_put_contents($file, "In Update resource2_states, SqlStatement =".$sqlStatement."\n\n", FILE_APPEND);



	
	
  //  $sqlStatement="SELECT id, status, createdBy, lastUpdatedBy, creationDate, lastUpdate, alias, name, website, address, voice, tty, fax, email, otherPhone, description, notesFROM adagreatlakes12_db.resource;"
  
  foreach ( $result as $print )   {
  $resource_slug = slugify( $print->resource . '-' . $print->id );
  
  $existing_resource = get_page_by_path( $resource_slug, 'OBJECT', 'ada_resource2' );

  $record= $resource_slug.", ".$print->state."!!\n";

  file_put_contents($file, $record, FILE_APPEND);

 
  
 if( $existing_resource <> null  ){

$existing_resource_id = $existing_resource->ID;			
$label = $print->state;

switch ($label) {
  case "Illinois":
    $value = 1;
    break;
  case "Indiana":
    $value = 2;
    break;
  case "Michigan":
	$value = 3;
    break;
  case "Minnesota":
	$value = 4;
	break;
  case "Ohio":
	$value = 5;
	break;
  case "Wisconsin":
	$value = 6;
	break;	
  default:
    $value=-1;
}




// $exisiting_resource_timestamp = get_field('lastUpdate', $existing_resource_id);
  
	   // if( $print->lastUpdate >= $exisiting_brewerey_timestamp ){
  
		  $keyValue = 'field_605247c71f6ae';
		 // $radioButton = Array(Array($label => $value));
		   $radioButton[$label] = $value;


		  $retVal = update_field( $keyValue, $value, $existing_resource_id);

		  if( $retVal <> null  ){
			$record="********** Resource Saved, existing_resource_id = ".$existing_resource_id.", retVal = ".$retVal.", keyvalue = ".$keyValue.", label = ". $label.", value=".$value."!!!\n\n";
				 
		  }
		  else {

			$record="???????? Resource NOT Saved! existing_resource_id = ".$existing_resource_id." retVal = ".$retVal.", keyvalue = ".$keyValue.", label = ". $label.", value=".$value."!!!\n\n";
			

		  }
  
		}
		else {

			$record="?*?*?*?* Resource NOT Found! existing_resource = ".$existing_resource.", category = ".$print->category."!!!\n\n";

		}
  
		file_put_contents($file, $record, FILE_APPEND);

	  }
  
	}
	
	

	function update_resource2_regional_and_national_test() {

		global $wpdb;
	  
		// echo '<pre>';
		//     print_r(  $result );
		// echo '</pre>';
		// die;
	  
		
	  //$sqlStatement="SELECT * FROM adagreatlakes12_db.resource order by status, name desc;";
	  $logfile = get_stylesheet_directory()."/report_loadCategories.txt";

	  $categoryGroups = array(1,3);

	  foreach ($categoryGroups as $Group) {

		if ($Group==1) {
			$sqlStatement="SELECT id, name, nationalrecs as numberOfRecs FROM adagreatlakes13_db.resource where nationalrecs>0 order by id;";
			$keyValue = 'field_605b88b52aa5e';
	
			}
			elseif ($Group==3) {
				$sqlStatement="SELECT id, name, specialties as numberOfRecs FROM adagreatlakes13_db.resource where specialties>0 order by id;";
				$keyValue = 'field_605b876e2aa5d';
			}


			file_put_contents($logfile, "In update_resource2_regional_and_national_test(), SqlStatement =".$sqlStatement."\n", FILE_APPEND);

			$results = $wpdb->get_results( $sqlStatement);
		   //file_put_contents($file, $sqlStatement."\n\n", FILE_APPEND);
		   
			 
		   //  $sqlStatement="SELECT id, status, createdBy, lastUpdatedBy, creationDate, lastUpdate, alias, name, website, address, voice, tty, fax, email, otherPhone, description, notesFROM adagreatlakes12_db.resource;"
		   
		   $categoryArray=[];
		   $values = [];
		   foreach ( $results as $result)   {
	 
			 $categoryArray = get_categores($Group, $result->id, $result->numberOfRecs, $logfile);
			 $values = get_categoryValues($Group, $result->numberOfRecs, $categoryArray);
			 
			 $valueStr =  implode(",", $values);
	 
			 
			 file_put_contents($logfile, "Back in update_resource2, categoryValues=".$valueStr."\n\n", FILE_APPEND);
				 // echo '<pre>';
				 // print_r( $categoryArray );
				 // echo '</pre>';
				 // die;

				//Update Wordpress post

				$resource_slug = slugify( $result->name . '-' . $result->id );
				
				$existing_resource = get_page_by_path( $resource_slug, 'OBJECT', 'ada_resource2' );
			  
				$existing_resource_id = $existing_resource->ID;
				
			   if( $existing_resource <> null  ){
			  
						$retVal = update_field( $keyValue, $values, $existing_resource_id);
			  
						if( $retVal <> null  ){
			  
							$record="********** Resource Saved, existing_resource_id = ".$existing_resource_id.", retVal = ".$retVal.", keyvalue = ".$keyValue.", values array = (". $valueStr.")!!!\n\n";
						
							if ($Group==3) {
								$taxonomy ="ada_specialty";
								wp_set_post_terms( $existing_resource_id,  $categoryArray, $taxonomy, true );
							}
						}	
						else {
			  
						  $record="???????? Resource NOT Saved! existing_resource_id = ".$existing_resource_id." retVal = ".$retVal.", keyvalue = ".$keyValue.", values array = (". $valueStr.")!!!\n\n";
			  
						}
					}
					else {
		
						$record="?*?*?*?* Resource NOT Found! existing_resource = ".$existing_resource.", resource_slug = ".$resource_slug."!!!\n\n";
			
		
					}

					file_put_contents($logfile, $record, FILE_APPEND);
	  

		}

	  }
   
}

        
		function get_categores($categoryGroup, $res_id, $numRecs, $logfile){
			global $wpdb;
			// echo '<pre>';
			//     print_r(  $result );
			// echo '</pre>';
			// die;
		   
		  
			
			//$sqlStatement="SELECT * FROM adagreatlakes12_db.resource order by status, name desc;";
			//file_put_contents($file, "Here we go\n\n", FILE_APPEND);


            $sqlStatement="SELECT resource, res_id, category FROM adagreatlakes13_db.resource1_v as res where res_id=".$res_id." and res.group=".$categoryGroup." order by category;";
		 
            file_put_contents($logfile, "In get_categores(), SqlStatement =".$sqlStatement."\n", FILE_APPEND);

			//$wpdb->show_errors();

              $results = $wpdb->get_results( $sqlStatement);
              
            //  $sqlStatement="SELECT id, status, createdBy, lastUpdatedBy, creationDate, lastUpdate, alias, name, website, address, voice, tty, fax, email, otherPhone, description, notesFROM adagreatlakes12_db.resource;"
            $i=0;
            $retval=[];
            foreach ( $results as $result)   {
                array_push($retval,$result->category);
			// echo '<pre>';
		    // print_r( $retval );
			// echo '</pre>';
			// die;


                file_put_contents($logfile, "In get_categores(), Category".($i+1)."=".$retval[$i]."\n", FILE_APPEND);
              
                $i=$i+1;
              
        }


		return $retval;
    }



	function get_categoryvalues($categoryGroup, $numRecs, $categoryArray){

		$i=0;
		$retval=[];
		foreach ($categoryArray as $label) {

			if ($categoryGroup==1) {

			
	
			switch ($label) {
			  case "Federal Agencies":
				$value = 1;
				break;
			  case "National Resources":
				$value = 2;
				break;
			  case "Great Lakes Regional Resources":
				$value = 3;
				break;
			  default:
				$value=0;
			}


		}	
		elseif ($categoryGroup==3) {

			switch ($label) {
				case "Accessibility Guidelines, Standards and Tools":
					$value = 17;
					break;
				case "Accessible Parking":
					$value = 18;
					break;
				case "Accessible Recreation":
					$value = 19;
					break;
				case "Accessible Technology":
					$value = 20;
					break;
				case "ADA Projects":
					$value = 21;
					break;
				case "Advocacy/Disability Rights":
					$value = 22;
					break;
				case "Assistive Technology":
					$value = 23;
					break;
				case "Attorney Generals":
					$value = 24;
					break;
				case "Client Assistant Program":
					$value = 25;
					break;
				case "Communicating with People who have Disabilities":
					$value = 26;
					break;
				case "Education":
					$value = 27;
					break;
				case "Employment":
					$value = 28;
					break;
				case "Enforcement":
					$value = 29;
					break;
				case "Guidelines for Reporting and Writing About Disability":
					$value = 30;
					break;
				case "Housing":
					$value = 31;
					break;
				case "Independent Living":
					$value = 32;
					break;
				case "Legal Assistance":
					$value = 33;
					break;
				case "Native American":
					$value = 34;
					break;
				case "Protection and Advocacy":
					$value = 35;
					break;
				case "Publications":
					$value = 36;
					break;
				case "Research and Disability Statistics":
					$value = 37;
					break;
				case "State Building Codes":
					$value = 38;
					break;
				case "Transportation":
					$value = 39;
					break;
				case "Universal Design":
					$value = 40;
					break;
				case "Veterans with Disabilities":
					$value = 41;
					break;
				case "Vocational Rehabilitation Services":
					$value = 42;				 
					break;				
				default:
					$value=0;
				}

		}	


			array_push($retval,$value);

			$i=$i+1;
		
	}


	return $retval;
}





	function update_resource2_regional_and_national() {

		global $wpdb;
	  
		// echo '<pre>';
		//     print_r(  $result );
		// echo '</pre>';
		// die;
	   // $file = get_stylesheet_directory()."/report.txt";
	  
		
		//$sqlStatement="SELECT * FROM adagreatlakes12_db.resource order by status, name desc;";
		$file = get_stylesheet_directory()."/report3.txt";
		//file_put_contents($file, "Here we go\n\n", FILE_APPEND);
	
	  //$sqlStatement="SELECT resource, id, SUBSTRING(rtrim(category), 1, length(rtrim(category))-5) as state FROM adagreatlakes12_db.resource1_v where status<>0 and ltrim(resource)<>'' and (resource1_v.group=2) order by status, ltrim(resource) asc;";
	  
	  $sqlStatement="SELECT resource, id, category FROM adagreatlakes13_db.resource1_v where status<>0 and ltrim(resource)<>'' and (resource1_v.group=1) order by status, ltrim(resource) asc;";
	  
	
	
		$result = $wpdb->get_results( $sqlStatement);
		//file_put_contents($file, $sqlStatement."\n\n", FILE_APPEND);
	  
		
	  //  $sqlStatement="SELECT id, status, createdBy, lastUpdatedBy, creationDate, lastUpdate, alias, name, website, address, voice, tty, fax, email, otherPhone, description, notesFROM adagreatlakes12_db.resource;"
	  
	  foreach ( $result as $print )   {
	  $resource_slug = slugify( $print->resource . '-' . $print->id );
	  
	  $existing_resource = get_page_by_path( $resource_slug, 'OBJECT', 'ada_resource2' );
	
	  $record= $resource_slug.", ".$print->category."!!\n";
	
	  file_put_contents($file, $record, FILE_APPEND);
	
	  $existing_resource_id = $existing_resource->ID;
	  
	 if( $existing_resource <> null  ){
			
			
	$label = $print->category;
	
	switch ($label) {
	  case "Federal Agencies":
		$value = 1;
		break;
	  case "National Resources":
		$value = 2;
		break;
	  case "Great Lakes Regional Resources":
		$value = 3;
		break;
	  default:
		$value=0;
	}
	
	
	
	
	// $exisiting_resource_timestamp = get_field('lastUpdate', $existing_resource_id);
	  
		   // if( $print->lastUpdate >= $exisiting_brewerey_timestamp ){
			//             field_605b88b52aa5e
			  $keyValue = 'field_605b88b52aa5e';
			 //            field_605895416a4e9
			 // $radioButton = Array(Array($label => $value));
			 // $radioButton[$label] = $value;
	
	
			  $retVal = update_field( $keyValue, $value, $existing_resource_id);
	
			  if( $retVal <> null  ){
	
			  $record="********** Resource Saved, existing_resource_id = ".$existing_resource_id.", retVal = ".$retVal.", keyvalue = ".$keyValue.", label = ". $label.", value=".$value."!!!\n\n";
					 
			  }
			  else {
	
				$record="???????? Resource NOT Saved! existing_resource_id = ".$existing_resource_id." retVal = ".$retVal.", keyvalue = ".$keyValue.", label = ". $label.", value=".$value."!!!\n\n";
	
			  }
			  
	  
			}
			else {

				$record="?*?*?*?* Resource NOT Found! existing_resource = ".$existing_resource.", category = ".$print->category."!!!\n\n";
	

			}
			file_put_contents($file, $record, FILE_APPEND);
		  }
	  
		}




		function update_resource2_taxonomy_ada_specialty() {

			global $wpdb;
		  
			// echo '<pre>';
			//     print_r(  $result );
			// echo '</pre>';
			// die;
		   // $file = get_stylesheet_directory()."/report.txt";
		  
			
			//$sqlStatement="SELECT * FROM adagreatlakes12_db.resource order by status, name desc;";
			$file = get_stylesheet_directory()."/report4.txt";
			//file_put_contents($file, "Here we go\n\n", FILE_APPEND);
		
		 $sqlStatement="SELECT resource, id, category FROM adagreatlakes13_db.resource1_v where status<>0 and ltrim(resource)<>'' and (resource1_v.group=3) order by category, status, ltrim(resource) asc;";
		 
		  file_put_contents($file, "In update_resource2_taxonomy_ada_specialty(), SqlStatement =".$sqlStatement."\n\n", FILE_APPEND);
		
			$result = $wpdb->get_results( $sqlStatement);
			//file_put_contents($file, $sqlStatement."\n\n", FILE_APPEND);
		  
			
		  //  $sqlStatement="SELECT id, status, createdBy, lastUpdatedBy, creationDate, lastUpdate, alias, name, website, address, voice, tty, fax, email, otherPhone, description, notesFROM adagreatlakes12_db.resource;"
		  
		  foreach ( $result as $print )   {
		  $resource_slug = slugify( $print->resource . '-' . $print->id );

		  $record= $resource_slug.", ".$print->category."!!\n";
		  file_put_contents($file, $record, FILE_APPEND);
		  
		  $existing_resource = get_page_by_path( $resource_slug, 'OBJECT', 'ada_resource2' );
		  
		 if( $existing_resource <> null  ){

		$existing_resource_id = $existing_resource->ID;
		$taxonomy ="ada_specialty";
		

		$label = $print->category;
		switch ($label) {
			case "Accessibility Guidelines, Standards and Tools":
				$value = 17;
				break;
			case "Accessible Parking":
				$value = 18;
				break;
			case "Accessible Recreation":
				$value = 19;
				break;
			case "Accessible Technology":
				$value = 20;
				break;
			case "ADA Projects":
				$value = 21;
				break;
			case "Advocacy/Disability Rights":
				$value = 22;
				break;
			case "Assistive Technology":
				$value = 23;
				break;
			case "Attorney Generals":
				$value = 24;
				break;
			case "Client Assistant Program":
				$value = 25;
				break;
			case "Communicating with People who have Disabilities":
				$value = 26;
				break;
			case "Education":
				$value = 27;
				break;
			case "Employment":
				$value = 28;
				break;
			case "Enforcement":
				$value = 29;
				break;
			case "Guidelines for Reporting and Writing About Disability":
				$value = 30;
				break;
			case "Housing":
				$value = 31;
				break;
			case "Independent Living":
				$value = 32;
				break;
			case "Legal Assistance":
				$value = 33;
				break;
			case "Native American":
				$value = 34;
				break;
			case "Protection and Advocacy":
				$value = 35;
				break;
			case "Publications":
				$value = 36;
				break;
			case "Research and Disability Statistics":
				$value = 37;
				break;
			case "State Building Codes":
				$value = 38;
				break;
			case "Transportation":
				$value = 39;
				break;
			case "Universal Design":
				$value = 40;
				break;
			case "Veterans with Disabilities":
				$value = 41;
				break;
			case "Vocational Rehabilitation Services":
				$value = 42;				 
				break;				
			default:
				$value=0;
			}
				  
	
			      //           field_605b876e2aa5d
				  $keyValue = 'field_605b876e2aa5d';
				  //$keyValue = 'field_6054b1555b7eb';
				 //            field_605895416a4e9
				 // $radioButton = Array(Array($label => $value));
				 // $radioButton[$label] = $value;
		
		
				  $retVal = update_field( $keyValue, $value, $existing_resource_id);

				 wp_set_post_terms( $existing_resource_id, array($label), $taxonomy, true );			  
		
				  if( $retVal <> null  ){
		
				  $record="********** Resource Saved, existing_resource_id = ".$existing_resource_id.", retVal = ".$retVal.", keyvalue = ".$keyValue.", label = ". $label.", value=".$value."!!!\n\n";
						 
				  }
				  else {
		
					$record="???????? Resource NOT Saved! existing_resource_id = ".$existing_resource_id." retVal = ".$retVal.", keyvalue = ".$keyValue.", label = ". $label.", value=".$value."!!!\n\n";
		
				  }
				  
		  
				}
				else {
	
					$record="?*?*?*?* Resource NOT Found! existing_resource = ".$existing_resource.", category = ".$print->category."!!!\n\n";
		
	
				}
				file_put_contents($file, $record, FILE_APPEND);
			  }
		  
			}
	

?>