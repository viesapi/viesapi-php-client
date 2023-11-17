<!doctype html>
<html lang="en">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />

		<title>VIES API Client - Example</title>
	</head>

	<body>
		<?php
			// Enable debug information (only for development)
			ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
			error_reporting(E_ALL);
			
			// Include VIES API
			require_once 'VIESAPI/VIESAPIClient.php';
			
			\VIESAPI\VIESAPIClient::registerAutoloader();
			
			// Create client object and establish connection to the production system
			// id – API identifier
			// key – API key (keep it secret)
			// $viesapi = new \VIESAPI\VIESAPIClient('id', 'key');
			
			// Create client object and establish connection to the test system
			$viesapi = new \VIESAPI\VIESAPIClient();
			
			$euvat = 'PL7171642051';

			// Get current account status
			$account = $viesapi->get_account_status();

			if ($account) {
				echo '<pre>' . print_r($account, true) . '</pre>';
			}
			else {
				echo '<p>Error: ' . $viesapi->get_last_error() . ' (code: ' . $viesapi->get_last_error_code() . ')</p>';
			}

			// Get VIES data from VIES system
			$vies = $viesapi->get_vies_data($euvat);
				
			if ($vies) {
			    echo '<pre>' . print_r($vies, true) . '</pre>';
			}
			else {
                echo '<p>Error: ' . $viesapi->get_last_error() . ' (code: ' . $viesapi->get_last_error_code() . ')</p>';
			}
		?>
	</body>
</html>
