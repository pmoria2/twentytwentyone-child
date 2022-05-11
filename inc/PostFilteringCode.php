<?php


add_action( 'pre_get_posts', 'order_taxonomy_archive' );
function order_taxonomy_archive( $query ) {
    if ( $query->is_main_query() AND $query->is_tax('groups_and_members') ) {
        $query->set( 'orderby', 'name' );
        $query->set( 'order', 'ASC' );
    }
}


// array of filters (field key => field name)
$GLOBALS['my_query_filters'] = array( 
	'field_1'	=> 'state', 
	'field_2'	=> 'regional_and_national'
);


// action
add_action('pre_get_posts', 'my_pre_get_posts', 10, 1);

function my_pre_get_posts( $query ) {
	
	// bail early if is in admin
	if( is_admin() ) return;
	
	
	// bail early if not main query
	// - allows custom code / plugins to continue working
	if( !$query->is_main_query() ) return;
	
	// get meta query
	$meta_query[] = $query->get('meta_query');

	$file = get_stylesheet_directory()."/my_pre_get_posts_report.txt";
	$record= "In my_pre_get_posts! Post type equals: ".$query->query_vars['post_type']."!!!\n";
	file_put_contents($file, $record, FILE_APPEND);

		// only modify queries for 'event' post type
if( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'ada_resource2' ) {
	// loop over filters
	foreach( $GLOBALS['my_query_filters'] as $key => $name ) {
		
		// continue if not found in url
		if( empty($_GET[ $name ]) ) {
			
			continue;
			
		}
		
		
		// get the value for this filter
		// eg: http://www.website.com/events?city=melbourne,sydney
		$value = explode(',', $_GET[ $name ]);
		
		
		// append meta query
    	$meta_query[] = array(
            'key'		=> $name,
            'value'		=> $value,
            'compare'	=> 'IN',
        );
        
	}
	
		// update meta query
	    $query->set('meta_query', $meta_query);
		$query->set('orderby', 'meta_value');	
		$query->set('meta_key', 'name');	 
		$query->set('order', 'ASC'); 
		
}
elseif( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'supreme_court_cases' ) {

		// update meta query
	    $query->set('meta_query', $meta_query);
		$query->set('orderby', 'meta_value');	
		$query->set('meta_key', 'year');	 
		$query->set('order', 'ASC'); 


}

elseif( isset($query->query_vars['post_type']) && $query->query_vars['post_type'] == 'chronicle' ) {

	$record= "In (post_type == chronicle) code!!\n";
	file_put_contents($file, $record, FILE_APPEND);


	// update meta query
	$query->set('orderby', 'meta_value');	
	$query->set('meta_key', 'issue');	 
	$query->set('order', 'DESC'); 
	
	// $query->set('orderby', 'title');	
	// $query->set('order', 'DESC'); 


}	


// return
return $query;



}



add_action( 'transition_post_status', 'new_chronicle_post', 10, 3 );

function new_chronicle_post( $new_status, $old_status, $post )
{
    if ( 'publish' !== $new_status or 'publish' === $old_status )
        return;

    if ( 'chronicle' !== $post->post_type )
        return; // restrict the filter to a specific post type

    // do something awesome
    $logfile = get_stylesheet_directory()."/report_newChronicle.txt";

    file_put_contents($logfile, "New Chronicle Issue!!\n", FILE_APPEND);

	UpdateHyperlink ($logfile, $post);
}


function UpdateHyperlink ($logfile, $post1) {

	//$repeater = get_field('network_links');
	//$repeatNum = count($repeater);

	$record = "In Update Hyperlink!!\n";
	file_put_contents($logfile, $record, FILE_APPEND);

	$args = array("post_type" => 'network_links', "s" => 'Homepage News');

      // The Query
      $query1 = new WP_Query( $args );
      if ($query1->have_posts())  {
						$post = $query1->the_post();
                        $latestpost = "Great Lakes Chronicle -";
                        $postlength = strlen($latestpost);
						file_put_contents($logfile, "Found the post!!!\n", FILE_APPEND);
						$i=0;
						while ( have_rows('network_links', $post) ) : the_row();
                        $link=get_sub_field('link');  
						$i++;


						if( strncmp($latestpost, $link['title'], $postlength)==0) {

                            $record = "Found Latest Post Link!! title=".$link['title'].", link=".$link['url']."\n";
							file_put_contents($logfile, $record, FILE_APPEND);

							$newLink=get_permalink($post1);
							$newTitle=$latestpost." ".get_field("month", $post1->ID);
							//LatestChronicleIssue($latestpost, $logfile);
							//$link=get_sub_field('link');
							//update_sub_field('url', $newLink);
							$link = array(
								'title' => $newTitle,
								'url' => $newLink,
								'target' => "_blank",
						   );
						   update_sub_field('link', $link);


							//update_sub_field('url', "This value is for repeater row {$parent_i}, and sub_repeater row {$child_i}");
							file_put_contents($logfile,"New Title: ".$newTitle.", New Link: ".$newLink."\n", FILE_APPEND);
							break;
						}	
						else {

						    $record = "Did NOT find Latest Post Link!! title=".$link['title'].", link=".$link['url']."\n";
							file_put_contents($logfile, $record, FILE_APPEND);
						}
						

						
					    endwhile; 

						$record = "Bout to call Error Log!!\n";
						file_put_contents($logfile, $record, FILE_APPEND);


						error_log( print_r($query1->the_post(), 3, $logfile) );

// echo '<pre>';
//     print_r( $query1->the_post()  );
// echo '</pre>';
// die;
							
	  } //end if have posts
	  else {

		file_put_contents($logfile, "Did NOT find the post!!!", FILE_APPEND);
	  }

	  return;

    }  //end Update Hyperlink





	function LatestChronicleIssue ($linktext, $logfile) {

		//$repeater = get_field('network_links');
		//$repeatNum = count($repeater);
	
		$record = "In LatestChronicleIssue!!\n";
		file_put_contents($logfile, $record, FILE_APPEND);
	
		//$args = array("post_type" => 'network_links', "s" => 'Homepage News');
	
		  // The Query
		  //$query1 = new WP_Query( $args );
	
	
	$args2 = array(
		'post_type' => 'chronicle',
		'posts_per_page'	=> -1,
		'meta_key'			=> 'issue',
		'orderby'			=> 'meta_value',
		'order'				=> 'DESC'
	);
	
	
	
	   /* The 2nd Query */
	   $query2 = new WP_Query( $args2 );
		
	if ($query2->have_posts())  {
		$post = $query2->the_post();
	
		$retval = get_permalink($post);
	
	
	}
	else {
		$record = "Did not Find the last Chronicle Issue!!\n";
		file_put_contents($logfile, $record, FILE_APPEND);


	}
	  return $retval;
	
	}

?>