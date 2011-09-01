<?php 

	/**
	 * Eventful Demo
	 * @author James Brook <james@bluebaytravel.co.uk>
	 * @version 0.0.1
	 */

	require_once "Eventful.php";

	$AppKey = "ENTER_APP_KEY";

	$eV = new Eventful($AppKey);

	$evLogin = $eV->login('USERNAME', 'PASSWORD');
	if($evLogin) {
		$evArgs = array(
			'location' => 'Mexico'
		);

		$cEvent = $eV->call('events/search', $evArgs);

		echo "<pre>" . print_r($cEvent, true) . "</pre>";
	}else{
		die("<strong>Error logging into Eventful API</strong>");
	}


?>