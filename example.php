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
			
			// Create a client object and establish connection to the production system
			// id – API identifier
			// key – API key (keep it secret)
			// $viesapi = new \VIESAPI\VIESAPIClient('id', 'key');
			
			// Create a client object and establish connection to the test system
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

			// Get VIES data from a VIES system
			$vies = $viesapi->get_vies_data($euvat);
				
			if ($vies) {
			    echo '<pre>' . print_r($vies, true) . '</pre>';
			}
			else {
                echo '<p>Error: ' . $viesapi->get_last_error() . ' (code: ' . $viesapi->get_last_error_code() . ')</p>';
			}

            // Get VIES data from a VIES system (with parsed trader name and address)
            $vies_parsed = $viesapi->get_vies_data_parsed($euvat);

            if ($vies_parsed) {
                echo '<pre>' . print_r($vies_parsed, true) . '</pre>';
            }
            else {
                echo '<p>Error: ' . $viesapi->get_last_error() . ' (code: ' . $viesapi->get_last_error_code() . ')</p>';
            }

            // Upload batch of VAT numbers and get their current VAT statuses and trader data
            $numbers = array(
                $euvat,
                'DK56314210',
                'CZ7710043187'
            );

            $token = $viesapi->get_vies_data_async($numbers);

            if ($token) {
                echo '<pre>Batch token: ' . $token . '</pre>';
            }
            else {
                echo '<p>Error: ' . $viesapi->get_last_error() . ' (code: ' . $viesapi->get_last_error_code() . ')</p>';
                die();
            }

            // Check batch result and download data (at production it usually takes 2-3 min for a result to be ready)
            while (($result = $viesapi->get_vies_data_async_result($token)) === false) {
                if ($viesapi->get_last_error_code() !== \VIESAPI\Error::BATCH_PROCESSING) {
                    echo '<p>Error: ' . $viesapi->get_last_error() . ' (code: ' . $viesapi->get_last_error_code() . ')</p>';
                    die();
                }

                echo '<p>Batch is still processing, waiting...</p>';
                sleep(10);
            }

            // Batch result is ready
            echo '<pre>' . print_r($result, true) . '</pre>';
		?>
	</body>
</html>
