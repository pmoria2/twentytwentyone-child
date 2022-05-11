<?php

/*
* ajax code for displaying current record when you click on the City_County button
*/
add_action( 'wp_ajax_nopriv_jp_ajax_test', 'slug_ajax_callback' );
add_action( 'wp_ajax_jp_ajax_test', 'slug_ajax_callback' );
function slug_ajax_callback() {
	
	//$nonce = $_GET['nonce'];
	$nonce = $_REQUEST['nonce'];
	//echo "nonce: ".$nonce;
	if ( wp_verify_nonce( $nonce, 'nonce' ) ) {

		//$district_id =  $_GET["district_id"];
		$district_id =  $_GET["district"];
		$logfile = get_stylesheet_directory()."/log/wp_tablecode.log";

		displayTable4 ($district_id, $logfile);

		wp_die( "" );
	}
	else{
        wp_die( 'Nonce error' );
    }


 
}

/*
* A function to display a table
*/    

function displayTable3 ($state_cd, $unit_type, $census_region, $pop_group, $income_group, $plan_decade, $req_met_percentile, $best_prct_percentile, $logfile) {

	global $wpdb;
	
	
	// echo '<pre>';
	//     print_r(  $result );
	// echo '</pre>';
	// die;

	$record="In displayTable3!!! \n";
	file_put_contents($logfile, $record, FILE_APPEND);

	
	$subTitle = "Transition Plan Records";

	$where ="";
	
	if ($unit_type<>"")  {
		$where = " where unit_type='".$unit_type."'";
	}
	
	if ($state_cd<>"" and $where=="")  {
		$where = " where state_cd='".$state_cd."'";
	} elseif ($state_cd<>"")  {
		$where = $where." and state_cd='".$state_cd."'";
	}
	
	if ($census_region<>"" and $where=="")  {
		$where = " where census_region='".$census_region."'";
	} elseif ($census_region<>"")  {
		$where = $where." and census_region='".$census_region."'";
	}
	
	if ($pop_group<>"" and $where=="")  {
		$where = " where pop_group='".$pop_group."'";
	} elseif ($pop_group<>"")  {
		$where = $where." and pop_group='".$pop_group."'";
	}

	if ($income_group<>"" and $where=="")  {
		$where = " where income_group='".$income_group."'";
	} elseif ($income_group<>"")  {
		$where = $where." and income_group='".$income_group."'";
	}

	if ($plan_decade<>"" and $where=="")  {
		$where = " where plan_decade='".$plan_decade."'";
	} elseif ($plan_decade<>"")  {
		$where = $where." and plan_decade='".$plan_decade."'";
	}

	if ($req_met_percentile<>"" and $where=="")  {
		$where = " where req_met_percentile='".$req_met_percentile."'";
	} elseif ($req_met_percentile<>"")  {
		$where = $where." and req_met_percentile='".$req_met_percentile."'";
	}

	if ($best_prct_percentile<>"" and $where=="")  {
		$where = " where best_prct_percentile='".$best_prct_percentile."'";
	} elseif ($best_prct_percentile<>"")  {
		$where = $where." and best_prct_percentile='".$best_prct_percentile."'";
	}
	
	/* city_county, state, unit_type, county, census_region, msa_urbanity, total_population, quart_pop, median_hh_income, income_group, poverty_rate, quart_poverty, median_age, senior_pct_population, quart_pct_senior, disabled_population, disabled_pct_population, quart_pct_disabled, use, audited, audit_quality, retreival_method, plan_year, retreival_year, url, ada_contact, file_name, file_location */
	
	//$sqlStatement="SELECT city_county, unit_type, county, census_region, msa_urbanity, total_population, quart_pop, median_hh_income, income_group, poverty_rate, quart_poverty, median_age, senior_pct_population, quart_pct_senior, disabled_population, disabled_pct_population, quart_pct_disabled, audited, audit_quality, retreival_method, plan_year, retreival_year, ada_contact, file_location FROM transition_plan_v";
	//$sqlStatement="SELECT `city_county`, `state`, `unit_type`, `county`, `census_region`, `msa_urbanity`, `total_population`, `quart_pop`, `median_hh_income`, `income_group`, `poverty_rate`, `quart_poverty`, `median_age`, `senior_pct_population`, `quart_pct_senior`, `disabled_population`, `disabled_pct_population`, `quart_pct_disabled`, `use`, `audited`, `audit_quality`, `retreival_method`, `plan_year`, `retreival_year`, `url`, `ada_contact`, `file_name`, `file_location` FROM `bonkaroo60_db`.`transition_plan_v`;";
	$sqlStatement="SELECT district_id, city_county, state_cd, unit_type, county, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, pop_group, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_score_req_met, audit_score_req_met2, audit_score_best_prct_met, audit_score_best_prct_met2, retreival_method, plan_year, plan_decade, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date, active_record FROM transition_plans_v ".$where." Order by state_cd, city_county, unit_type;";

	

	$record="sql=".$sqlStatement."\n";
	file_put_contents($logfile, $record, FILE_APPEND);
	
	?>
	
	
	<div id="ajax-target2a">
	
	<?php
		//$wpdb->show_errors();
		$result = $wpdb->get_results( $sqlStatement);
		//$wpdb->print_error();
		if ($result) {
			// <?php echo $print->date_end.', '.$print->period;	
			$i=1;
			foreach ( $result as $print )   { 
			
				if ( $i==1): 
				
					$retVal=$print->district_id;				
				?>
					
					<div class="scrollit">
	
					<table>
					<caption><?php echo $subTitle;	?> </caption>
	
					<thead>
					<tr>
					<th style="text-align:center">city_county</th>
					<th>state</th>
					<th>unit_type</th>
					<th>county</th>
					<th>census_region</th>
					<th>msa_urbanity</th>
					<th>total_population</th>
					<th>quart_pop</th>
					<th>median_hh_income</th>
					<th>income_group</th>
					<th>poverty_rate</th>
					<th>quart_poverty</th>
					<th>median_age</th>
					<th>senior_pct_population</th>
					<th>quart_pct_senior</th>
					<th>disabled_population</th>
					<th>disabled_pct_population</th>
					<th>quart_pct_disabled</th>
					<th>retreival_method</th>
					<th>plan_year</th>
					<th>retreival_year</th>
					<th>URL</th>
					<th>ada_contact</th>
					</tr>
					</thead>
					<tbody>
				<?php endif; ?>
	
	
			  <tr>				        
						<td> <div ID=<?php echo $print->district_id; ?> class="btn-group"><button ><?php echo $print->city_county; ?>  </button></div> </td>						
					    <td><?php echo $print->state_cd; ?> </td>
						<td><?php echo $print->unit_type; ?> </td>
						<td><?php echo $print->county; ?> </td>
						<td><?php echo $print->census_region; ?> </td>
						<td><?php echo $print->msa_urbanity; ?> </td>
						<td><?php echo $print->total_pop; ?> </td>
						<td><?php echo $print->quart_pop; ?> </td>
						<td><?php echo $print->median_hh_income; ?> </td>
						<td><?php echo $print->income_group; ?> </td>
						<td><?php echo $print->poverty_rate; ?> </td>
						<td><?php echo $print->quart_poverty; ?> </td>
						<td><?php echo $print->median_age; ?> </td>
						<td><?php echo $print->pct_pop_senior; ?> </td>
						<td><?php echo $print->quart_pct_senior; ?> </td>
						<td><?php echo $print->disabled_pop; ?> </td>
						<td><?php echo $print->pct_pop_disabled; ?> </td>
						<td><?php echo $print->quart_pct_disabled; ?> </td>
						<td><?php echo $print->retreival_method; ?> </td>
						<td><?php echo $print->plan_year; ?> </td>
						<td><?php echo $print->retreival_year; ?> </td>
						<td><?php echo $print->plan_url; ?> </td>
						<td><?php echo $print->ada_contact; ?> </td>
			  </tr>
				<?php $i=$i+1; 
				
			}

		  ?>
	</tbody>
	</table>
	</div>
<?php	
} else {
		?>	
			<h6 style="text-align:center"> No Records Found Matching that Criteria! </h6>

		<?php
		}	
?>
	</div>
	<?php

return $retVal;
	
		} // End function



		function getFieldValues ($inFieldname, $logfile) {

			global $wpdb;
			
			
			// echo '<pre>';
			//     print_r(  $result );
			// echo '</pre>';
			// die;
			
			if ($inFieldname=="pop_group") {
				$sqlStatement="SELECT pop_group, count(*) as 'count' FROM ".$wpdb->prefix."transition_plans where active_record = 1 group by pop_group, pop_group_max order by pop_group_max;";
			} else {
				$sqlStatement="SELECT ".$inFieldname.", count(*) as 'count' FROM transition_plans_v group by ".$inFieldname." order by ".$inFieldname.";";
			}

			
			$record="sql=".$sqlStatement."\n";
			file_put_contents($logfile, $record, FILE_APPEND);

			$retArray = [];
			
				//$wpdb->show_errors();
				$result = $wpdb->get_results( $sqlStatement);
				//$wpdb->print_error();
				if ($result) {
					foreach ( $result as $retValues )   {
						$record="inFieldName=".$inFieldname.", Value=".$retValues->$inFieldname.", count=".$retValues->count."\n";
						file_put_contents($logfile, $record, FILE_APPEND);
			
						array_push($retArray, array($retValues->$inFieldname, $retValues->count));
					}
				}

				return $retArray;
			}
			

			function displayTable4 ($district_id, $logfile) {

				global $wpdb;
				
				
				// echo '<pre>';
				//     print_r(  $result );
				// echo '</pre>';
				// die;
				
				
				$subTitle = "Transition Plan Record";
			
				
				$record="district_id=".$district_id."\n";
				file_put_contents($logfile, $record, FILE_APPEND);
			
				
				//$sqlStatement="SELECT `district_id`, `city_county`, `state_cd`, `unit_type`, `county`, `census_region`, `msa_urbanity`, `total_pop`, `quart_pop`, `median_hh_income`, `income_group`, `poverty_rate`, `quart_poverty`, `median_age`, `pct_pop_senior`, `quart_pct_senior`, `disabled_pop`, `pct_pop_disabled`, `quart_pct_disabled`, `_use`, `audited`, `retreival_method`, `plan_year`, `retreival_year`, `plan_url`, `ada_contact`, `file_name`, `file_location` FROM `transition_plans_v` where district_id='".$district_id."' Order by state_cd, city_county, unit_type;";
				$sqlStatement="SELECT district_id, city_county, state_cd, unit_type, county, census_region, msa_urbanity, total_pop, total_pop2, quart_pop, pop_group, median_hh_income, median_hh_income2, income_group, poverty_rate, poverty_rate2, quart_poverty, median_age, pct_pop_senior, pct_pop_senior2, quart_pct_senior, disabled_pop, disabled_pop2, pct_pop_disabled, pct_pop_disabled2, quart_pct_disabled, _use, audited, audited2, audit_score_req_met, audit_score_req_met2, audit_score_best_prct_met, audit_score_best_prct_met2, retreival_method, plan_year, plan_decade, retreival_year, plan_url, ada_contact, file_name, file_location, upload_date, active_record FROM transition_plans_v  where district_id='".$district_id."' Order by state_cd, city_county, unit_type;";			
			
				$record="Sql Statement=".$sqlStatement."\n";
				file_put_contents($logfile, $record, FILE_APPEND);
			
			
				
				?>
				
				
				<div id="ajax-target2">
			
				
				
				<?php
					//$wpdb->show_errors();
					$result = $wpdb->get_results( $sqlStatement);
					//$wpdb->print_error();
					if ($result) {
	
						// <?php echo $print->date_end.', '.$print->period;	
						foreach ( $result as $print )   {
							
							switch ($print->quart_pop) {
								case 1:
									$quart_pop= "0 to 24th percentile";
								  break;
								case 2:
									$quart_pop= "25th to 49th percentile";
		  						  break;
	 						    case 3:
									$quart_pop= "50th to 74th percentile";
									break;
								case 4:
									$quart_pop= "75th to 100 percentile";
									break;
								default:
									$quart_pop= "N/A";;
							  }

							  switch ($print->quart_poverty) {
								case 1:
									$quart_poverty= "Very Low";
								  break;
								case 2:
									$quart_poverty= "Low";
								  break;
	 						    case 3:
									$quart_poverty= "High";
									break;
								case 4:
									$quart_poverty= "Very High";
									break;
								default:
								    $quart_poverty= "N/A";;
							  }

							  
							  switch ($print->quart_pct_senior) {
								case 1:
									$quart_pct_senior= "0 to 24th percentile";
								  break;
								case 2:
									$quart_pct_senior= "25th to 49th percentile";
								  break;
	 						    case 3:
									$quart_pct_senior= "50th to 74th percentile";
									break;
								case 4:
									$quart_pct_senior= "75th to 100 percentile";
									break;
								default:
									$quart_pct_senior= "N/A";;
							  }

							  switch ($print->quart_pct_disabled) {
								case 1:
									$quart_pct_disabled= "0 to 24th percentile";
								  break;
								case 2:
									$quart_pct_disabled= "25th to 49th percentile";
								  break;
	 						    case 3:
									$quart_pct_disabled= "50th to 74th percentile";
									break;
								case 4:
									$quart_pct_disabled= "75th to 100 percentile";
									break;
								default:
									$quart_pct_disabled= "N/A";;
							  }

							  if ($print->unit_type == 'County') {

								$unit_type='Unit Type: '.$print->unit_type;

							  } else {

								$unit_type='Unit Type: '.$print->unit_type.', '.'County: '.$print->county;

							  }

	?>
						<div>
						<h4 style="text-align:center"><?php echo $print->city_county.", ".$print->state_cd; ?></h4>	

						<ul>
						<li><?php echo $unit_type; ?>
							  <ul>
							  <li><?php echo 'Census Region: '.$print->census_region; ?></li>
							  <li><?php echo 'MSA Urbanity: '.$print->msa_urbanity; ?></li>
						  	  <li><?php echo 'Total Population: '.number_format($print->total_pop); ?></li>
						  	  <li><?php echo 'Population Quartile: '.$quart_pop; ?></li>
							</ul></li>

						  <li><?php echo 'Median Household Income: $'.number_format($print->median_hh_income); ?>
							<ul>
							  <li><?php echo 'Income Group: '.$print->income_group; ?></li>
							  <li><?php echo 'Poverty Rate: '.(100*$print->poverty_rate).'%'; ?></li>							  							  
							  <li><?php echo 'Poverty Quartile: '.$quart_poverty; ?></li>
							</ul></li>
						<li><?php echo 'Median Age: '.$print->median_age; ?> 
							 <ul>
								<li><?php echo '% of Population 65 & Over: '.(100*$print->pct_pop_senior).'%'; ?> </li>
							    <li><?php echo '% 65 & Over Quartile: '.$quart_pct_senior; ?> </li>
							 </ul></li>	
						<li><?php echo 'Disabled Population: '.number_format($print->disabled_pop); ?>
							  <ul>
								<li><?php echo '% of Total Population Disabled: '.(100*$print->pct_pop_disabled).'%'; ?> </li>
								<li><?php echo '% Disabled Quartile: '.$quart_pct_disabled; ?> </li>
							  </ul></li>
						<li><?php echo 'Audit Score - Requirements Met: '.(100*$print->audit_score_req_met).'%, '; ?> </li>
						<li><?php echo 'Audit Score - Best/Good Practice Met: '.(100*$print->audit_score_best_prct_met).'%, '; ?> </li>
						<li><?php echo 'Retrieval Method: '.$print->retreival_method; ?> </li>
						<li><?php echo 'Year of Most Recent Plan: '.$print->plan_year.', '.'Retrieval Year: '.$print->retreival_year; ?> </li>
						<li>Most recently found URL of plan: 
							  <ul>
							  <li><a href="<?php echo $print->plan_url; ?>" target="_blank"><?php echo $print->city_county.", ".$print->state_cd.' Transition Plan' ?></a></li>
							  </ul></li>
						<li><?php echo 'ADA Contact: '.$print->ada_contact; ?> </li>
						</ul>
						<br>
						</div>
			<?php	
				}
	
			} else {
					?>	
						<h6 style="text-align:center"> No Records Found Matching that Criteria! </h6>
			
					<?php
					}	
			?>
				</div>
				<?php
				
} // End function
	
	
?>