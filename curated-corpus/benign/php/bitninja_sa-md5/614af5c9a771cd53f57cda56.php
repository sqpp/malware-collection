<?php

include('../../../wp-config.php' ); 

global $wpdb;

// Get the desired action from the POST parameter
$action =  $_POST['crud-action'];


if ($action == 'delete'){

	//Check to ensure that this request is legit
	if (check_admin_referer('delete_showtime_entry', 'delete_entries_nonce_field')){
		
		$wpdb->query( $wpdb->prepare("DELETE FROM $showtimeTable WHERE id = %d", $_POST['id'])	);
		
		if (function_exists('wp_cache_clear_cache')) {  wp_cache_clear_cache(); }

		echo 'good delete';

	} 

} else if ($action == 'create') {
	
	//Check to ensure that this request is legit
	if(check_admin_referer('add_showtime_entry', 'showtime_nonce_field')){


			//Set the timezone appropriately
			$tz = get_option('timezone_string');
			date_default_timezone_set($tz);

			extract ($_POST);

			$startDay 	= $_POST['sday'];
			$endDay 	= $_POST['eday'];
			$startTime 	= $_POST['startTime'];
			$endTime	= $_POST['endTime'];
			$imageUrl 	= $_POST['imageUrl'];
			$linkUrl 	= $_POST['linkUrl'];
			$showname   = htmlentities(stripslashes(($_POST['showname'])));

			//Check the $linkURL to make sure it's valid
			if ($linkUrl && filter_var($linkUrl, FILTER_VALIDATE_URL) == false) {
			    echo 'bad linkURL';
			    return;
			}

			//Check the show name to make sure it's there
			if ($showname == ''){
				echo 'bad name';
				return;
			}

			//Format the start and end times, then convert them to a UNIX timestamp set in the early 80s
			//This allows us to recycle the schedule every week

			$showstart 		 = strtotime($startDay.", ".$startTime." August 1, 1982");
			$showstart 		 = $showstart + (1);
			$showend 		 = strtotime($endDay.", ".$endTime." August 1, 1982");

			//Check to make sure the start time is before the end time
			if ($showstart >= $showend){
				echo 'too soon';
				return;
			}

			//Create the start and end clock times
			$startClock = date('g:i a', ($showstart));
			$endClock = date('g:i a', ($showend));

			//Check to see if that slot is already taken by an existing show

			if ( ! $wpdb->query('SELECT id, startTime, endTime, showName FROM '.$showtimeTable.' WHERE startTime <= '.$showstart.' AND endTime >= '.$showend.' ORDER BY startTime') ) {

				$wpdb->query( $wpdb->prepare("INSERT INTO $showtimeTable (dayOfTheWeek, startTime,endTime,startClock, endClock, showName,  imageURL, linkURL) VALUES (%s, %d, %d , %s, %s, %s, %s, %s)", $startDay, $showstart, $showend, $startClock, $endClock, $showname, $imageUrl, $linkUrl )	);

				//Send the object back
				$newShow = $wpdb->get_results($wpdb->prepare("SELECT * FROM $showtimeTable WHERE id = %d", $wpdb->insert_id));
				echo json_encode($newShow);
				if (function_exists('wp_cache_clear_cache')) {  wp_cache_clear_cache(); }

		} else {

			echo 'scheduling conflict';

		}
		
	}

} else if ($action == 'update') {

	//Check to ensure this request is legit
	if(check_admin_referer('save_showtime_entries', 'showtime_entries_nonce_field')){

		//Go through each parameter and update it if necessary

		$showname = $_POST['showName'];
		$linkUrl = $_POST['linkURL'];
		$imageUrl = $_POST['imageURL'];

		if(isset($_POST['showName'])){

		 foreach ($showname as $key => $value) {
		 	$wpdb->query( $wpdb->prepare("UPDATE $showtimeTable SET showname = %s WHERE id= %d ", htmlentities(stripslashes($value)), $key ));
		  }

		}
		if(isset($_POST['linkURL'])){

		 foreach ($linkUrl as $key => $value) {
		 		$wpdb->query( $wpdb->prepare("UPDATE $showtimeTable SET linkURL = %s WHERE id= %d ", $value, $key ));
		  }

		}

		if(isset($_POST['imageURL'])){

		 foreach ($imageUrl as $key => $value) {
				$wpdb->query( $wpdb->prepare("UPDATE $showtimeTable SET imageURL = %s WHERE id= %d ", $value, $key ));

		  }

		}

		if (function_exists('wp_cache_clear_cache')) {  wp_cache_clear_cache(); }

		echo 'good updates';

	}

} else if ($action == 'read'){

	if ($_POST['read-type'] == "current"){
		
		$currentTimestamp = strtotime( date('l').", ".date('g:i:s a')." August 1, 1982");

		$results = array();

		$currentShowEndTime = $currentTimestamp;

		//Get the currently playing show

		$currentShow = $wpdb->get_row($wpdb->prepare ("SELECT * FROM $showtimeTable WHERE startTime < %d && endTime > %d", $currentTimestamp, $currentTimestamp));
		if ($currentShow){
				//Check to see if images are being displayed
				if (get_option('showtime_use_images') == 'no'){
					$currentShow->imageURL = false;
				}

			$results['current-show'] = $currentShow;
			$currentShowEndTime = $currentShow->endTime;
		} else {
			$results['current-show'] = get_option('off_air_message');
		}



		if (get_option('showtime_upcoming') == 'yes'){
			//Get the next scheduled show as well
			$nextShow = $wpdb->get_row($wpdb->prepare ("SELECT * FROM $showtimeTable WHERE startTime >= %d ORDER BY startTime LIMIT 1", $currentShowEndTime));

			//If there are no more shows, then the next show will be the very first one on Sundays
			if (! $nextShow) {
				$nextShow = $wpdb->get_row($wpdb->prepare ("SELECT * FROM $showtimeTable ORDER BY startTime LIMIT %d", 1));
			}

			$results['upcoming-show'] = $nextShow;
		}

		echo json_encode($results);

	} else {

		$daysOfTheWeek = array("Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday");

		$output = array();

		foreach ($daysOfTheWeek as $day) {
			//Add this day's shows to the $output array
			$output[$day] =  $wpdb->get_results( $wpdb->prepare ( "SELECT * FROM $showtimeTable WHERE dayOfTheWeek = '$day' ORDER BY startTime"));
		}

		echo json_encode($output);
	}

}


?>