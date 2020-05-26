<?php

	function formatDate($entered, $left = '') {
	
		// Set Default Timezone
		// Taken from: https://stackoverflow.com/questions/16765158/date-it-is-not-safe-to-rely-on-the-systems-timezone-settings
		// Timezone List: http://www.php.net/manual/en/timezones.php
		date_default_timezone_set('America/Anguilla');
	
	
		// If $left is unspecified, return formated $entered date only
		if(!$left) {
			return date("F j, Y (g:iA)", strtotime($entered));
		}
	

		// Create month-day-year formats of clock-in and clock-out dates
		$monthDayYearEntered = date("F j, Y", strtotime($entered));
		$monthDayYearLeft = date("F j, Y", strtotime($left));


		// Create final prettified date (depending on last step)
		if($monthDayYearEntered === $monthDayYearLeft) {

			$formatedDate =  $monthDayYearLeft . " (" . 
							date("g:iA", strtotime($entered)) . " to " .
							date("g:iA", strtotime($left)) . ")";

		} else {

			$formatedDate =  $monthDayYearEntered . " (" . 
							date("g:iA", strtotime($entered)) . ") – " .
							$monthDayYearLeft . " (" .
							date("g:iA", strtotime($left)) . ")";

		}
		
		return $formatedDate;
		
	}
									
									
									