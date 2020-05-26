<?php

	function formatPhone($phone) {
	
		// WARNING: assumes +1 extension and 10 digit phone number given
		
		if($phone) {
		
			$formatedPhone = substr($phone, 0, 3) .
							 '-' . substr($phone, 3, 3) .
							 '-' . substr($phone, 6, 4);
							 
			$formatedPhone = "<a href=\"tel:+1$formatedPhone\">$formatedPhone</a>";
							 
		} else {
		
			$formatedPhone = "<span class=\"text-muted\">No phone</span>";
			
		}
	
		
		return $formatedPhone;
	}