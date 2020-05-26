<?php

	function generateColor($string) {
		return '#' . substr(hash('ripemd160', $string), 0, 6);
	}