<?php

/* enqueue scripts and style from parent theme */
   
function twentytwentyone_styles() {
	wp_enqueue_style( 'child-style', get_stylesheet_uri(),
	array( 'twenty-twenty-one-style' ), wp_get_theme()->get('Version') );
}
add_action( 'wp_enqueue_scripts', 'twentytwentyone_styles');



function add_theme_scripts() {
		wp_enqueue_script( 'jquery' );

		wp_enqueue_script( 'slug-ajax', get_template_directory_uri() . '-child/scripts/js/myjquery.js', array('jquery'), false, true );
		
		$jp = array(
			'nonce' => wp_create_nonce( 'nonce' ),
			'ajaxURL' => admin_url( 'admin-ajax.php' )
		); 
		wp_localize_script( 'slug-ajax', 'jp', $jp );


		@ini_set( 'upload_max_size' , '120M' );
		@ini_set( 'post_max_size', '120M');
		@ini_set( 'max_execution_time', '300' );

  }
  add_action( 'wp_enqueue_scripts', 'add_theme_scripts' );


 // Copy to Clipboard (https://clipboardjs.com/)
 function clipboard1() {
    if (!is_admin()) {

        wp_register_script('clipboard', get_template_directory_uri(). '-child/scripts/js/clipboard.min.js', array('jquery') );
        wp_enqueue_script('clipboard');


        // FlexSlider custom settings       
        add_action('wp_footer', 'clipboard_settings');

        function clipboard_settings() { ?>         
            <script>
                jQuery(document).ready(function($){
					new ClipboardJS('.btn');
					//var clipboard = Clipboard('.btn');
                });
            </script>
        <?php 
        }
    }
}
add_action('init', 'clipboard1');


  // deactivate new widgets block editor
  function phi_theme_support() {
    remove_theme_support( 'widgets-block-editor' );
  }
  add_action( 'after_setup_theme', 'phi_theme_support' );

  // display data table
  require_once 'inc/wp_tablecode.php';

  require_once 'inc/ContactForm7code.php';
  //require_once 'inc/wp_flexslider.php';
  require_once 'inc/reg_acf_cpt.php';
  
 // require_once 'inc/PostFilteringRoutines.php';

 add_theme_support( 'align-wide' );
 add_theme_support( 'align-full' );
  
  function pm_register_blocks() {
	
	if( ! function_exists( 'acf_register_block_type' ) )
		return;


	acf_register_block_type( array(
		'name'			=> 'displayPosts',
		'title'			=> ('Display Posts'),
		'description'	=> ('Display posts on page'),
		'render_template'	=> 'template-parts/displayPosts.php',
		'category'		=> 'formatting',
		'icon'			=> 'networking',
		'keywords'		=> array( 'posts', 'hyperlink'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));

	acf_register_block_type( array(
		'name'			=> 'videoDisplay',
		'title'			=> ('Video Display'),
		'description'	=> ('Display Video of the Month on a page'),
		'render_template'	=> 'template-parts/video_of_the_month.php',
		'category'		=> 'formatting',
		'icon'			=> 'networking',
		'keywords'		=> array( 'posts', 'video'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));

	acf_register_block_type( array(
		'name'			=> 'calendarList',
		'title'			=> ('Training Calendar'),
		'description'	=> ('Display Webinar List'),
		'render_template'	=> 'template-parts/displayCalendar.php',
		'category'		=> 'formatting',
		'icon'			=> 'networking',
		'keywords'		=> array( 'training','calendar'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));
	
	acf_register_block_type( array(
		'name'			=> 'MonthlyMaintenancePage',
		'title'			=> ('Monthly Maintenance Page'),
		'description'	=> ('Links and shortcuts for updating Chronicle Newsletter'),
		'render_template'	=> 'template-parts/MonthlyMaintenancePage.php',
		'category'		=> 'formatting',
		'icon'			=> 'networking',
		'keywords'		=> array( 'chronicle','newsletter', 'Resources', 'Announcements'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));

	acf_register_block_type( array(
		'name'			=> 'ada_resource_display',
		'title'			=> ('Great Lakes ADA'),
		'description'	=> ('Display Great Lakes ADA Contact Information'),
		'render_template'	=> 'template-parts/contact_greatlakesada.php',
		'category'		=> 'formatting',
		'icon'			=> 'networking',
		'keywords'		=> array( 'contact','ada'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));

	acf_register_block_type( array(
		'name'			=> 'ada_resource_associations',
		'title'			=> ('Great Lakes Associations'),
		'description'	=> ('Display list of ADA associates'),
		'render_template'	=> 'template-parts/adaAssociates_greatlakesada.php',
		'category'		=> 'formatting',
		'icon'			=> 'networking',
		'keywords'		=> array( 'contact','ada'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));


	acf_register_block_type( array(
		'name'			=> 'q_and_a',
		'title'			=> ('Questions and Answers'),
		'description'	=> ('Accessible IT frequently asked questions'),
		'render_template'	=> 'template-parts/accessibleIT_q_a.php',
		'category'		=> 'formatting',
		'icon'			=> 'networking',
		'keywords'		=> array( 'questions','ada', 'answers', 'accessible IT'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));

	acf_register_block_type( array(
		'name'			=> 'staff_display',
		'title'			=> ('Staff Display'),
		'description'	=> ('Great Lakes Staff'),
		'render_template'	=> 'template-parts/staff_greatlakesada.php',
		'category'		=> 'formatting',
		'icon'			=> 'networking',
		'keywords'		=> array( 'staff','employee'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));

	acf_register_block_type( array(
		'name'			=> 'chronicle_view',
		'title'			=> ('Chronicle View'),
		'description'	=> ('Mainly for displaying current issue'),
		'render_template'	=> 'template-parts/chronicle_view.php',
		'category'		=> 'formatting',
		'icon'			=> 'networking',
		'keywords'		=> array( 'chronicle','ada', 'newsletter'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));

	acf_register_block_type( array(
		'name'			=> 'youtube_showcase_display',
		'title'			=> ('YouTube Showcase'),
		'description'	=> ('Great Lakes ADA Center YouTube Showcase'),
		'render_template'	=> 'template-parts/youtube_showcase_view.php',
		'category'		=> 'formatting',
		'icon'			=> 'networking',
		'keywords'		=> array( 'youtube','ada', 'video'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));

	acf_register_block_type( array(
		'name'			=> 'dataTable_tp',
		'title'			=> ('Display dataTable TP'),
		'description'	=> ('Display dataTable in a page'),
		'render_template'	=> 'template-parts/dataTable_TP.php',
		'category'		=> 'formatting',
		'icon'			=> 'media-spreadsheet',
		'keywords'		=> array( 'table', 'data'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));	

	acf_register_block_type( array(
		'name'			=> 'online_resource_repository',
		'title'			=> ('Online Resource Repository'),
		'description'	=> ('Read in urls from a file and fill a page'),
		'render_template'	=> 'template-parts/online_resource_repository.php',
		'category'		=> 'formatting',
		'icon'			=> 'media-spreadsheet',
		'keywords'		=> array( 'table', 'data'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));	

	acf_register_block_type( array(
		'name'			=> 'slider',
		'title'			=> ('Slider'),
		'description'	=> ('Slider for ACF gallery'),
		'render_template'	=> 'template-parts/block-slider.php',
		'category'		=> 'formatting',
		'icon'			=> 'format-gallery',
		'keywords'		=> array( 'slider', 'gallery'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));

	acf_register_block_type( array(
		'name'			=> 'colored_text_box',
		'title'			=> ('Colored Text Box'),
		'description'	=> ('Add a colored text box to a page or post'),
		'render_template'	=> 'template-parts/colored_text_box.php',
		'category'		=> 'formatting',
		'icon'			=> 'format-gallery',
		'keywords'		=> array( 'color', 'text'),
		'mode'			=> 'edit',
		'Supports'		=> array( 'mode' => false)
	));
}

add_action('acf/init', 'pm_register_blocks' );

add_filter( 'facetwp_load_a11y', '__return_true' );


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
	$file = get_stylesheet_directory()."/log/my_pre_get_posts_report.log";
	$record= "In my_pre_get_posts!!!!\n";
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
	$query->set('meta_key', 'volume_issue');	 
	$query->set('order', 'DESC'); 
	
	// $query->set('orderby', 'title');	
	// $query->set('order', 'DESC'); 


}

// return
return $query;

}


function new_chronicle_post( $post_id ) {
	// What have we got here?
	$post   = get_post( $post_id );
	$post_title = get_the_title( $post_id );
    $post_url = get_permalink( $post_id );
	$post_type = $post->post_type;

 
	$logfile = get_stylesheet_directory()."/log/newChroniclePost.log";
	date_default_timezone_set("America/Chicago");

	$record = "In new_chronicle_post, time stamp: ". date("Y-m-d h:i:sa");

    if ( 'chronicle' !== $post_type ) {
		$record.=", this is not a chronicle post!";
		file_put_contents($logfile, $record."\n");
        return;
    }

	// code doesn't work
	$updated=wp_is_post_revision( $post_id );
    // if ( $updated ){
	// 	$record.=", this is not a new post, just an edit";
	// 	file_put_contents($logfile, $record."\n", FILE_APPEND);
    //     return;
    // }

	$month=get_field("month", $post->ID);
	if ( trim($month)==="" ){
		// $record.=", We don't have any data yet!";
		// file_put_contents($logfile, $record."\n", FILE_APPEND);
        return;
    }

	
	$volume=get_field("volume", $post->ID);
	$issue=get_field("issue", $post->ID);
	if ( $issue<10) {
		$volume_issue=(int)$volume.".0".(int)$issue;
	} else {
		$volume_issue=(int)$volume.".".(int)$issue;
	}

	update_field('volume_issue', $volume_issue, $post->ID);
    
    $volume_issue2=get_field("volume_issue", $post->ID);


	$record .= ", Post Title: ".$post_title . ", Post Url: " . $post_url;
	$record .= ", volume_issue: ".$volume_issue2.", Month: ".$month.", Volume: ".$volume.", Issue: ".$issue; 
	$record .= ", Post Type: ".$post_type."\n";
	file_put_contents($logfile, $record."\n");

	if (isThisTheLatestChronicleIssue($volume_issue2, $logfile)) {
		UpdateHyperlink ($logfile, $post_title, $post_url);
	}


    // Send email to admin.
	// $subject = 'A post has been updated';
    // $message = "A post has been updated on your website:\n\n";
    // $message .= $post_title . ": " . $post_url;
    //wp_mail( 'admin@example.com', $subject, $message );
}

add_action( 'save_post', 'new_chronicle_post', 10, 3 );
//add_action( 'transition_post_status', 'new_chronicle_post', 10, 3 );



function UpdateHyperlink ($logfile, $post_title, $newLink) {

	//$repeater = get_field('network_links');
	//$repeatNum = count($repeater);
	$chroniclePrefix = "Great Lakes Chronicle -";
	$lenPrefix = strlen($chroniclePrefix);
	$newTitle=$chroniclePrefix." ".$post_title;

	$record = "In Update Hyperlink!!".date("Y-m-d h:i:sa").". \n";
	file_put_contents($logfile, $record, FILE_APPEND);

	$args = array("post_type" => 'network_links', "s" => 'e-News');

      // The Query
      $query1 = new WP_Query( $args );
	  
	  file_put_contents($logfile, $query1, FILE_APPEND);

      if ($query1->have_posts())  {
	
						$post = $query1->the_post();
						file_put_contents($logfile, "Found a post!\n", FILE_APPEND);
						$i=0;
						while ( have_rows('network_links', $post) ) : the_row();
                        $link=get_sub_field('link');  
						$i++;


						if( strncmp($chroniclePrefix, $link['title'], $lenPrefix)==0) {

                            $record = "It's the one!! Current title=".$link['title'].", current link=".$link['url']."\n";
							file_put_contents($logfile, $record, FILE_APPEND);

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

						    $record = "Did NOT find current chronicle e-News link!! found title=".$link['title'].", found link=".$link['url']."\n";
							file_put_contents($logfile, $record, FILE_APPEND);
						}

					    endwhile; 

						// error_log( print_r($query1->the_post(), 3, $logfile) );
											
	  } //end if have posts
	  else {

		file_put_contents($logfile, "Did NOT find e-News post!!!", FILE_APPEND);
	  }

	  return;

    }  //end Update Hyperlink


	function isThisTheLatestChronicleIssue ($in_volume_issue, $logfile) {

		$retval=false;

		$record = "In isThisTheLatestChronicleIssue!!\n";
		file_put_contents($logfile, $record, FILE_APPEND);
	
		//$args = array("post_type" => 'network_links', "s" => 'Homepage News');
	
		  // The Query
		  //$query1 = new WP_Query( $args );
	
	
	$args2 = array(
		'post_type' => 'chronicle',
		'posts_per_page'	=> -1,
		'meta_key'			=> 'volume_issue',
		'orderby'			=> 'meta_value',
		'order'				=> 'DESC'
	);
	
	
	
	   /* The 2nd Query */
	   $query2 = new WP_Query( $args2 );
		
	if ($query2->have_posts())  {
		$post = $query2->the_post();

		$volume_issue=get_field("volume_issue", $post->ID);
		if ( $in_volume_issue === $volume_issue ) {
			$retval=true;
		} else {
			$record = "This chronicle edit is for an older issue!\n";
			file_put_contents($logfile, $record, FILE_APPEND);
		}
	
	}
	else {
		$record = "Search did not find the last Chronicle Issue!!\n";
		file_put_contents($logfile, $record, FILE_APPEND);

	}
	  return $retval;
	
	}


/*
* Creating a function to display hyperlink custom post type
*/

function displayHyperPost ($post, $colcss ) {
	$NumElements=0;
	?>		
					<div class="<?php echo $colcss; ?>">
					<!-- <h3> the_title(); </h3> -->
					<h3><?php the_title(); ?></h3>
				

						<ul>
						
	<?php		

						while ( have_rows('network_links', $post) ) : the_row();

								
						  $display=get_sub_field('display');
						  if ($display) {
							$NumElements++;
							$link=get_sub_field('link');    					
							$description=get_sub_field('description');   ?>	

							<li><a href="<?php echo $link['url']; ?>" target="<?php echo $link['target']; ?>">
							<?php echo $link['title']; ?></a><?php echo ($description<>"")?(" - ".$description):(""); ?></li>
					<?php	
						  }		
						endwhile;
						If ($NumElements==0){   ?>
							<li>Nothing to report at this time.  Try back tomorrow.</li>		

						<?php } ?>

						</ul><br>


					</div>
<?php
	
	}   // End Function


function getContactPhone()
{ 

 
   $args=array(
	'post_type'			=> 'ada_resource',
	'posts_per_page'	=> -1,
    'meta_query' => array(
        array(
           'key'     => 'name',
           'compare' => '=',
           'value'   => 'Region 05 - Great Lakes ADA Center',
       )
        )
);
   // The Query
   $query1 = new WP_Query( $args );
   if ($query1->have_posts())  {
	
       $returnVal = "<ul><p><li>".get_field('phone1_number', $query1->post->ID).' ('.get_field('phone1_label', $query1->post->ID).')'."</li></p></ul>";
   }
   else
   { $returnVal="Error!  No WP_Query record found!"; }

    return ($returnVal);
} 

add_shortcode('get_GLADA_ContactPhone', 'getContactPhone');



//admin_url('admin-ajax.php?action=copyRemoteWebinarTable') 
//http://localhost/adagreatlakes90/wp-admin/admin-ajax.php?action=copyRemoteWebinarTable


add_action( 'wp_ajax_nopriv_copyRemoteWebinarTable', 'copyRemoteWebinarTable' );
add_action( 'wp_ajax_copyRemoteWebinarTable', 'copyRemoteWebinarTable' );
function copyRemoteWebinarTable() {

	global $wpdb;
	
	//$mydb = new wpdb('username','password','database','localhost');
	$mydb = new wpdb('root','Work4u!','access20_AccessBoard','server18.morrisdev.com');
	


	//$sqlStatement = "SELECT startDate, sessionName, webinarURL FROM access20_accessboard.vw_sessions where startDate > '2021-10-2' order by startDate;";
	//$sqlStatement = "SELECT date(startDate) as Date, time(startDate) as Time, sessionName, concat('https://accessibilityonline.org/ada-tech/session/?id=', ses.id) as URL, app.name as Application, ses.application_id FROM access20_accessboard.vw_sessions as ses join access20_accessboard.application app on ses.application_id=app.id where startDate > '2021-10-2' order by startDate;";
	$sqlStatement = "SELECT ses.id, date(startDate) as sesDate, time(startDate) as sesTime, TIMEDIFF(endDate, startDate) as duration, prod.name sessionName, prod.description as descrip, concat('https://accessibilityonline.org/', app.route, '/session/?id=', ses.id) as URL, app.name as app, aprod.application_id as app_id, app.route as app_route, fiscalYear, activated, webinarURL, webinarpasscode as passcode, captioningURL, boxURL, videoTranscript, SurveyGizmo as survey, startDate, endDate
	FROM access20_accessboard.session as ses join access20_accessboard.applicationproduct aprod on ses.id=aprod.Product_id 
										left join access20_accessboard.application app on aprod.application_id=app.id 
											 join product prod on ses.id = prod.id where startDate > NOW() - INTERVAL 120 DAY order by startDate;";
	


	$file = get_stylesheet_directory()."/log/copyRemoteWebinarTable.log";

	date_default_timezone_set("America/Chicago");
	$record="\n\n *** Update GreatLakesADA for ". date("Y-m-d h:i:sa")."\n";
	file_put_contents($file, $record);
	

	$record=admin_url('admin-ajax.php?action=copyRemoteWebinarTable')."\n";
	file_put_contents($file, $record, FILE_APPEND);

	//$sqlStatement="SELECT * FROM adagreatlakes13_db.resource where status<>0 and name<>'' order by status, ltrim(name) desc;";
		
	$record="SqlStatement=".$sqlStatement."\n\n";
	file_put_contents($file, $record, FILE_APPEND);
	
	// Open the remote table
	$rows = $mydb->get_results( $sqlStatement);
		
	// prepare the local input table
	$tableName = $wpdb->prefix."webinar_session";
	$wpdb->query('TRUNCATE TABLE '.$tableName.';');

	if($wpdb->last_error !== '') {

        $str   = htmlspecialchars( $wpdb->last_result, ENT_QUOTES );
        $query = htmlspecialchars( $wpdb->last_query, ENT_QUOTES );

		$record = "wpdb Error! Error: ".$wpdb->last_error.", last result: ".$str.", Query: ".$query."\n";
		file_put_contents($file, $record, FILE_APPEND);          
	}


	$record = "ID, Date, Time, Duration, SessionName, Description, URL, App, App_id, fiscalYear, activated, webinarURL, passcode, captioningURL, boxURL, videoTranscript, Survey\n";
	file_put_contents($file, $record, FILE_APPEND);        

	  
	foreach ( $rows as $row )   {
		//$record="Date = ".$row->Date.", SessionName = ".$row->sessionName.", URL = ".$row->URL."\n";
		$id = $row->id; 
		$sesDate = $row->sesDate; 
		$sesTime = $row->sesTime;
		$duration = $row->duration;
		$sessionName = $row->sessionName;
		$description = $row->descrip;
		$URL = $row->URL;
		$app = $row->app;
		$app_id = $row->app_id;
		$fiscalYear = $row->fiscalYear;
		$activated = $row->activated;
		$passcode = $row->passcode;
		$webinarURL = $row->webinarURL;
		$captioningURL = $row->captioningURL;
		$boxURL = $row->boxURL;
		$videoTranscript = $row->videoTranscript;
		$survey = $row->survey;

		$record = $id.", ".$sesDate.", ".$sesTime.", ".$duration.", '".$sessionName."', '".substr($description,0,20)."', ".$URL.", '".$app."', ".$app_id.", ".$fiscalYear.", ".$activated.", ".$webinarURL.", ".$passcode.", ".$captioningURL.", ".$boxURL.", ".$videoTranscript.", ".$survey."\n";
		file_put_contents($file, $record, FILE_APPEND);  
		

		//'app_route' => $row->app_route,
		$inserted_resource = $wpdb->insert($tableName, array(
			'id' => $row->id,
			'sesDate' => $row->sesDate, 
			'sesTime' => $row->sesTime,
			'duration' => $row->duration,
			'sessionName' => $row->sessionName,	
			'description' => $row->descrip,
			'URL' => $row->URL,
			'app' => $row->app,
			'app_id' => $row->app_id,			
			'fiscalYear' => $row->fiscalYear,
			'activated' => $row->activated,
			'webinarURL' => $row->webinarURL,
			'passcode' => $row->passcode,
			'captioningURL' => $row->captioningURL,
			'boxURL' => $row->boxURL,
			'videoTranscript' => $row->videoTranscript,
			'survey' => $row->survey,
			'startDate' => $row->startDate,
			'endDate' => $row->endDate
			));
			
			if( is_wp_error( $inserted_resource ) || $inserted_resource === 0 ) {
				$record="?*?*?*?* Resource NOT inserted!!! inserted_resource = ".$inserted_resource."!!!\n\n";				
				file_put_contents($file, $record, FILE_APPEND);
	  
			  //die('Could not insert brewery: ' . $brewery_slug);
			  //error_log( 'Could not insert resource: ' . $resource_slug );
			  //continue;
			  }


	}

	//updateTrainingCalendarFromWebinarTable();
	
	  //admin-ajax.php?action=read_table_and_output_to_file 
	
	  return;
	
	}


function displayTrainingCalendar() {

		global $wpdb;

		$parametersTable = $wpdb->prefix."training_calendar_params";
		$sqlStatement="SELECT display_months, download_date FROM ".$parametersTable." where id = 1;";
                
		$rows = $wpdb->get_results( $sqlStatement);
		foreach( $rows as $row ) {		
		  $displayMonths =$row->display_months;
		  $downloadDate = $row->download_date;
		}
	
	    $numDaysGoingForward = $displayMonths*30;
		
	?>
	<br>
	<ul>
		
	<?php 
    
    $tableName = $wpdb->prefix."webinar_session";
    $sqlStatement="SELECT id, sesDate, sesTime, duration, sessionName, description, app, app_id, URL, fiscalYear, captioningURL, webinarURL, passcode, boxURL, videoTranscript, survey, startDate, endDate, activated FROM ".$tableName." where (startDate > NOW() - INTERVAL .2 DAY and startDate < NOW() + INTERVAL +".$numDaysGoingForward." DAY) order by startDate;";
                
    //$wpdb->show_errors();
    $rows = $wpdb->get_results( $sqlStatement);
    //$wpdb->print_error();

    //<p> <?php echo $print->date_end.', '.$print->period;	</p>

        
    foreach( $rows as $row ) {
		
        // echo '<pre>';
        // print_r($row->sesDate." ".$row->sesTime);
        // echo '</pre>';

		
		$date2=date_create(substr($row->sesDate, 0, 10)." ".$row->sesTime,timezone_open("America/New_York"));
        
		$sesDate = date_format($date2,"l M j, Y @ g:i a");
				
        $webinarSeries = $row->app;
		$linkURL = $row->URL;
        $linkName = $row->sessionName;
        $linkTarget="_blank";
?>
        <li><?php echo $webinarSeries ?><br>
		<a href="<?php echo $linkURL; ?>" target="<?php echo $linkTarget; ?>">
        <?php echo $linkName; ?><?php echo ' - '.$sesDate.' (Eastern Time)'; ?></a></li>

    

        <?php } ?>
	
	</ul>
	
<?php
	
	return;	
}


function displayTrainingCalendar_monthlyMaintenance($numDaysGoingback, $numDaysGoingForward) {

    global $wpdb;
    
$tableName = $wpdb->prefix."webinar_session";
$sqlStatement="SELECT id, sesDate, sesTime, duration, sessionName, description, app, app_id, URL, fiscalYear, captioningURL, webinarURL, passcode, boxURL, videoTranscript, survey, startDate, endDate, activated FROM ".$tableName." where (startDate > NOW() - INTERVAL ".$numDaysGoingback." DAY and startDate < NOW() + INTERVAL +".$numDaysGoingForward." DAY) order by startDate;";
            
$rows = $wpdb->get_results( $sqlStatement);

$trainingCalander="";    
foreach( $rows as $row ) {

    $date2=date_create(substr($row->sesDate, 0, 10));    
    $sesDate = date_format($date2,"l M j, Y");
    
    $webinarSeries = $row->app;
    $linkURL = $row->URL;
    $linkName = $row->sessionName;
    $linkTarget="_blank";

    $trainingCalander=$trainingCalander.$webinarSeries."<br>"."<a href=".$linkURL." target=".$linkTarget.">".$linkName.' - '.$sesDate."</a><br><br>";
}

return $trainingCalander;

}

function CreateUrls($inTableName) {

	global $wpdb;
	
	$tableName = $wpdb->prefix.$inTableName;
	
	$sqlStatement="SELECT text, url, if(instr(url, 'www.')>0, 1, if(instr(url, 'http')>0, 1, 0)) as is_url  FROM ".$tableName.";";

			
	$rows = $wpdb->get_results( $sqlStatement);
	//$wpdb->print_error();

		// $file = get_stylesheet_directory()."/log/creatingUrls.log";
		// date_default_timezone_set("America/Chicago");
		// $record="\n\n *** Update GreatLakesADA for ". date("Y-m-d h:i:sa")."\n";
		// file_put_contents($file, $record);

		// Collect section headers so we can build our menu
		$sections=[]; 
			
		//Create menus
	?>
	<br />
	<p>  Choose from the menu below to go to the associated section.</p>
	<br />
	<ul>

<?php 
		$categoryMenu="";
		$i=0;
		foreach( $rows as $row ) {

			if ( !$row->is_url ) {
				$originalvalue = $row->text;
				$i=strripos($originalvalue, "(");
				if ($i=="") {
					$line1=$originalvalue;
					$line2="";
				} else {
					$line1=substr($originalvalue,0,$i);
					$line2=strrchr($originalvalue, "(");
				}
				$menuName = $row->url;
				$slug = slugify( $menuName );

				array_push($sections, array($originalvalue, $line1, $line2, $menuName, $slug));
		?>

		<li><a href=<?php echo '#'.$slug; ?>><?php echo $menuName; ?></a><?php echo ' - '.$line1; ?></li>
		
		<?php
  		// While we're at it, let's build our horizontal menu
		  $categoryMenu=$categoryMenu.' | <a href="#'.$slug.'">'.$menuName.'</a> ';
		  $i++;

		//   $record="sectionName=".$originalvalue.", i=".$i.", sectionName1=".$line1.", sectionName2=".$line2."\n";
		//   file_put_contents($file, $record, FILE_APPEND);
		}
	}

	$sectioncount = count($sections);

	// $record="sectioncount=".$sectioncount.", i=".$i."\n";
	// file_put_contents($file, $record, FILE_APPEND);

    $categoryMenu=$categoryMenu.' | ';

		?>
</ul>
<br />
<br />
<hr class="wp-block-separator is-style-dots"/>
<br>	

	<div class="alignfull" >
	<div class="center" >
	<br>	
	<ul>
	
	<?php
	$i=0;
	foreach( $rows as $row ) {
	
	// echo '<pre>';
	// print_r($post);
	// echo '</pre>';
	
		
	$linkURL = $row->url;
	$linkName = $row->text;
	$linkTarget="_blank";


	if ( !$row->is_url ) {
		//$slug = slugify($linkURL);
		$slug = $sections[$i][4];
		$line1 = $sections[$i][1];
		$line2 = $sections[$i][2];
		if ( $i<>0 ) {
?>			
  		<br>
  		<br>
  		<hr class="wp-block-separator is-style-dots"/>
  		<br>
		<p class="has-text-align-center"><?php echo ($categoryMenu); ?></p>
  		<br>
  		<br>
  		<hr class="wp-block-separator is-style-dots"/>
  		<br>
<?php
		}
		?>
		<br>
  		<div id="<?php echo $slug; ?>"></div>
		<br>
		<hr>
		<h3 class="has-text-align-center"><?php echo ($line1); ?></h3>
		<h4 class="has-text-align-center"><i><?php echo ($line2); ?></i></h4>
		<hr>
		<br>

<?php 
		$i++;

	} else {

		?>
		<li><a href="<?php echo $linkURL; ?>" target="<?php echo $linkTarget; ?>">
		<?php echo $linkName; ?></a></li><br>
		
	<?php } 
	}	

	?>
	</ul>
</div>
</div>
<?php	
	return;	
	}


	function slugify($text){

		// remove unwanted characters
		$text = preg_replace('~[^-\w]+~', '', $text);
	  
		// trim
		$text = trim($text, '-');
	  
		// remove duplicate -
		$text = preg_replace('~-+~', '-', $text);
	  
		// lowercase
		$text = strtolower($text);
	  
		if (empty($text)) {
		  return 'n-a';
		}
	  
		return $text;
	  }


 function chronicle_displayfields($inID) {


	$chronicle_sections=array(array("Trainings and Events", "Calendar", "Trainings and Events"),
					array("News From the Federal Agencies", "News", "News From the Federal Agencies"),
					array("In Focus", "Focus", "Focusing in on news you may have missed"),
					array("The Docket", "Docket", "Federal court rulings that relate to the ADA"),
					array("Question", "Question", "Peter answers a frequently asked question"));

	$sectioncount = count($chronicle_sections);
	// We need to output the chronicle sections in the order they come in
	$sectionsoutput=[];
	// sub-section of Chronicle section "Question"
	$resources="";

?>

<style type="text/css">

.field_withborder {
border: 1px solid var(--global--color-primary);
padding: 0px 0px;
}
  </style>

<div class="center2">

<h2 style="text-align:center">  Welcome to the Center's newsletter!</h2><br>
<p>  This issue is devided into the following sections:</p><br>


<ul>

<?php 
		$chronicleMenu="";

		$fields = get_field_objects($inID);
		$i=0;
		foreach( $fields as $field ):
			if($i < 3 ) {
				// skip volume, issue and month fields
				array_push($sectionsoutput, array("", "", ""));
				$i++;
				continue;
			}
			elseif ($field['label']==="volume_issue") {
				array_push($sectionsoutput, array("", "", ""));
				$i++;
				continue;
			}
			elseif ($field['label']==="Resources") {
				array_push($sectionsoutput, array("", "", ""));
				$i++;
				$resources=$field['value'];
				continue;
			}	
			elseif (! empty($field['value']) ) {

				for ($j=0; $j<$sectioncount; $j++) {
					if( strcmp($chronicle_sections[$j][0], wp_strip_all_tags($field['label']))==0) {
						array_push($sectionsoutput, $chronicle_sections[$j]);
						break;
				  }
				}
				if ($j>=$sectioncount) {
					// We have a new section that doesn't have a corresponding entry in the $chronicle_sections array above
					array_push($sectionsoutput, array(wp_strip_all_tags($field['label']), wp_strip_all_tags($field['label']), ""));
				}


				$menuName = $sectionsoutput[$i][1];
				$slug = slugify( $menuName );
				if ($sectionsoutput[$i][2]==="") {
					$line1="";
				} else {
					$line1=" - ".$sectionsoutput[$i][2];
				}
				

			?>

				<li><a href=<?php echo '#'.$slug; ?>><?php echo $menuName; ?></a><?php echo $line1; ?></li>
				
			<?php
   
   				$chronicleMenu=$chronicleMenu.' | <a href="#'.$slug.'">'.$menuName.'</a> ';
		 	} else {
				// This is a section that is not populated for this issue of the Chronicle
				array_push($sectionsoutput, array("", "", ""));

			 } 

			 $i++;
 
			   // While we're at it, let's build our horizontal menu
		 endforeach; 

		 $chronicleMenu=$chronicleMenu.' | ';

		 ?>
 </ul>
 </div>
 <br />
 
<?php

		//$fields = get_field_objects($inID);
		$i=0;
		foreach( $fields as $field ):
			if($i < 3 ) {
				// skip volume, issue and month fields
				$i++;
				continue;
			}
			elseif ($field['label']==="volume_issue")  {
				// skip volume_issue, our special field for sorting
				$i++;
				continue;
			}	
			elseif ($field['label']==="Resources") {
				// skip should be output with Question 
				$i++;
				continue;
			}	
			elseif (! empty($field['value']) ) {
				$slug = slugify( $sectionsoutput[$i][1] );

				if (wp_is_mobile()) { ?>	

  				<div id="<?php echo $slug; ?>"></div>
				<br>

				<div>
					<br><h2 style="text-align:center"><?php echo wp_strip_all_tags($field['label']); ?></h2>
					<p class="has-text-align-center"><?php echo ($chronicleMenu); ?></p>
					<br>
					<p> <?php echo $field['value']; ?></p>
					<?php
					if((wp_strip_all_tags($field['label'])=="Question") and ($resources<>"")) { ?>
						<h3 style="text-align:left">Resources</h3>
						<p> <?php echo $resources; ?></p>
				<?php } ?>
				</div>
			<?php	} else { ?>
  				<div id="<?php echo $slug; ?>"></div>
				<br>

				<div class="entry-content field_withborder" >
				<br><h2 style="text-align:center"><?php echo wp_strip_all_tags($field['label']); ?></h2>
				<p class="has-text-align-center"><?php echo ($chronicleMenu); ?></p>

				<br>
					<p> <?php echo $field['value']; ?></p>
					<?php
					if((wp_strip_all_tags($field['label'])=="Question") and ($resources<>"")) { ?>
						<h3 style="text-align:left">Resources</h3>
						<p> <?php echo $resources; ?></p>
				<?php } ?>
				</div>
			<?php	} 		
		 }  
		 $i++;
		 endforeach; 
		
	// echo '<pre>';
	//     print_r( $term->name );
	// 	print_r( $term);
	// echo '</pre>';
	// die;			
	return;	
}


function display_coloredBox($text, $background, $text_color, $url, $textBox){

    ?>


    <style> 
    .center {
      border-radius: 25px;
      margin: auto;
      width: 80%;
      padding: 10px;
      color: <?php echo $text_color; ?>;
      background-color: <?php echo $background; ?>;      
    }
    
    /* unvisited link */
    a:link {
      color: <?php echo $text_color; ?>;
    }
    
    /* visited link */
    a:visited {
      color: <?php echo $text_color; ?>;
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
    
    <div style="background-color:<?php echo $background; ?>; color:<?php echo $text_color; ?>;" class="center">
        
    <?php
    
    // echo '<pre>';
    // print_r( 'link>>>>>'.$link.'<<<<' );
    // echo '</pre>';
    
    
    If ($url != null) {
    
    ?>
        <h3><a style="color:<?php echo $text_color; ?>" href="<?php echo $url; ?>" target="_blank">
        <?php echo $text; ?></a></h3>
    
    <?php } else {?>

        <h3><?php echo $text; ?></h3>

    <?php } ?>

	<p style="color:<?php echo $text_color; ?>"><?php echo $textBox; ?></p>


    </div>
    
<?php


return;
}



function display_ADA_menu($inHeading){
	?>
	<br><p><?php echo $inHeading; ?></p><br>
	<li><a href="<?php echo site_url('ada/#titleI'); ?>" target="_blank">Title I: Employment</a></li>
	<li><a href="<?php echo site_url('ada/#titleII'); ?>" target="_blank">Title II: State & Local Governments</a></li>
	<li><a href="<?php echo site_url('ada/#titleIII'); ?>" target="_blank">Title III: Places of Public Accommodations</a></li>
	<li><a href="<?php echo site_url('ada/#titleIV'); ?>" target="_blank">Title IV: Telecommunications</a></li>
	<li><a href="<?php echo site_url('ada/#titleV'); ?>" target="_blank">Title V: Miscellaneous Provisions</a></li>
	<br>
	
	<?php
	
	return;
	
	}

?>