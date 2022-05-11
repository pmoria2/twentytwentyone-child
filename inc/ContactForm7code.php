<?php

// hook into wpcf7_before_send_mail
add_action( 'wpcf7_before_send_mail', 'cf7dynamicnotifications'); // Hooking into wpcf7_before_send_mail

function cf7dynamicnotifications($contact_form) // Create our function to be used in the above hook
{
   $submission = WPCF7_Submission::get_instance(); // Create instance of WPCF7_Submission class
   $posted_data = $submission->get_posted_data(); // Get all of the submitted form data


   $date = new DateTime("now", new DateTimeZone('America/Chicago') );


   $inStaffName=$posted_data['attention'];
   $recipient_email = getStaffEmail($inStaffName);
   $admin_email = get_option('admin_email');  // in case there is a problem with $recipient_email

   // Check for errors
   //----------------------

   $errormessage = "Error, cannot find";
   $Errorlength = strlen($errormessage);
   //$isErrorMessage = strncmp($errormessage, $recipient_email, $postlength);

   if(substr($recipient_email,0,$Errorlength)==$errormessage) {
	// Staff Name not found
	$errormessage = $recipient_email;
	$recipient_email = $admin_email;
	
	}
	elseif (strpos($recipient_email,'@') == false) {
	   // Not valid email address
	   $errormessage = 'Error, email address is missing, or is not valid!';
	   $recipient_email = $admin_email;
   }
   else {

	$errormessage = "No Errors!";

   }


	$logfile = get_stylesheet_directory()."/report_getStaffEmail.txt";
    file_put_contents($logfile, $date->format('Y-m-d H:i:s')." In Staff Name = ".$inStaffName.", Email Address =".$recipient_email.", Error message: ".$errormessage."\n", FILE_APPEND);



	$testCases = array("Patrick Moriarty", "Claudia Diaz");

	if(! in_array($inStaffName, $testCases) and $recipient_email != $admin_email  ) { 
	   
		$recipient_email = 'pm250624@gmail.com';
	}

  
   // set the email address to recipient
   $mailProp = $contact_form->get_properties('mail');
   $mailProp['mail']['recipient'] = $recipient_email;

   // update the form properties
   $contact_form->set_properties(array('mail' => $mailProp['mail']));
   return;
}





function getStaffEmail($inStaffName)
{

If ($inStaffName=='General Inquiry') {
	$returnVal = get_option('admin_email'); 
	return ($returnVal);
}

  
$args=array(
	'post_type'			=> 'staff',
    'meta_query' => array(
        array(
           'key'     => 'name',
           'compare' => '=',
           'value'   =>  $inStaffName,
       )
        )
);
   // The Query
   $query1 = new WP_Query( $args );
   if ($query1->have_posts())  {

    $returnVal = get_field('email', $query1->post->ID);

   }
   else {

	$returnVal = "Error, cannot find Great Lakes ADA Center staff record!!";

   }

 
    return ($returnVal);

} 



/*

  add_action( 'wpcf7_init', 'wpcf7_add_form_tag_text' );
 
  function wpcf7_add_form_tag_text() {
	wpcf7_add_form_tag(
	  array( 'text', 'text*', 'email', 'email*', 'url', 'url*', 'tel', 'tel*' ),
	  'wpcf7_text_form_tag_handler',
	  array( 'name-attr' => true )
	);
  }


*/

/*

  add_action( 'wpcf7_init', 'custom_add_form_tag_staffemail' );
 
  function custom_add_form_tag_staffemail() {
	wpcf7_add_form_tag( 'staffemail', 'custom_staffemail_form_tag_handler' ); // "clock" is the type of the form-tag
  }

   
  function custom_staffemail_form_tag_handler( $tag )  {

	echo '<pre>';
    print_r( $_GET[ 'attention' ] );
    echo '</pre>';
  die;

	return 'pm250624@gmail.com';
  }

*/

/*
add_action( 'wpcf7_init', 'custom_add_form_tag_clock' );
 
function custom_add_form_tag_clock() {
  //wpcf7_add_form_tag( 'clock', 'custom_clock_form_tag_handler' ); // "clock" is the type of the form-tag

  wpcf7_add_form_tag( 'staffemail', 'custom_clock_form_tag_handler' ); // "clock" is the type of the form-tag
}
 
function custom_clock_form_tag_handler( $tag ) {
	$email='pm250624@gmail.com';

	echo '<pre>';
    print_r( $_GET[ 'attention' ] );
    echo '</pre>';
  //die;


  return  $email;

}
*/


/*

  add_action( 'wpcf7_init', 'custom_add_form_tag_clock' );
 
  function custom_add_form_tag_clock() {
	wpcf7_add_form_tag( 'clock', 'custom_clock_form_tag_handler' ); // "clock" is the type of the form-tag
  }
   
  function custom_clock_form_tag_handler( $tag ) {

	echo '<pre>';
    print_r( $_GET[ 'attention' ] );
    echo '</pre>';
  //die;

	return date_i18n( get_option( 'time_format' ) );
  }
  
*/

/*

  add_filter( 'shortcode_atts_wpcf7', 'custom_shortcode_atts_wpcf7_filter', 10, 3 );
 
  function custom_shortcode_atts_wpcf7_filter( $out, $pairs, $atts ) {
	$my_attr = 'destination-email';
   
		$file = get_stylesheet_directory()."/wpcf7.txt";
	  
		$record= "In Wpcf7_filter!!\n";
	  
		file_put_contents($file, $record, FILE_APPEND);
	  
  
  echo '<pre>';
  print_r( $_GET[ 'attention' ] );
  echo '</pre>';
  //die;

  
  
	if ( isset( $atts[$my_attr] ) ) {
	  $out[$my_attr] = $atts[$my_attr];
	}
   
	return $out;
  }

*/

?>