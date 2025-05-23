<?php
/**
 * Copyright 2022-2025 NETCAT (www.netcat.pl)
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 * @author NETCAT <firma@netcat.pl>
 * @copyright 2022-2025 NETCAT (www.netcat.pl)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace VIESAPI;

/**
 * VIES API service client
 */
class VIESAPIClient
{
    const VERSION = '1.3.1';

    const PRODUCTION_URL = 'https://viesapi.eu/api';
    const TEST_URL = 'https://viesapi.eu/api-test';
    
    const TEST_ID = 'test_id';
    const TEST_KEY = 'test_key';

    private $url;
    private $id;
    private $key;
    private $app;

    private $errcode;
    private $err;

    /**
     * VIES API PSR-0 autoloader
     */
    public static function autoload($className)
    {
        $files = array(
			'Error.php',
            'LegalForm.php',
			'AccountStatus.php',
            'NameComponents.php',
            'AddressComponents.php',
            'BatchResult.php',
            'VIESData.php',
            'VIESError.php',
            'NIP.php',
            'EUVAT.php',
            'Number.php'
        );
        
        foreach ($files as $file) {
            $path = __DIR__ . DIRECTORY_SEPARATOR . $file;
            
            if (file_exists($path)) {
                require_once $path;
            }
        }
    }

    /**
     * Register VIES API's PSR-0 autoloader
     */
    public static function registerAutoloader()
    {
        spl_autoload_register(__NAMESPACE__ . '\\VIESAPIClient::autoload');
    }

    /**
     * Construct new service client object
     * @param string $id VIES API key identifier
     * @param string $key VIES API key
     */
    public function __construct($id = null, $key = null)
    {
        $this->url = self::TEST_URL;
        $this->id = self::TEST_ID;
        $this->key = self::TEST_KEY;
        
        if (!empty($id) && !empty($key)) {
            $this->url = self::PRODUCTION_URL;
            $this->id = $id;
            $this->key = $key;
        }
        
        $this->app = '';
        $this->clear();
    }

    /**
     * Set non default service URL
     * @param string $url service URL
     */
    public function set_url($url)
    {
        $this->url = $url;
    }

    /**
     * Set application info
     * @param string $app app info
     */
    public function set_app($app)
    {
        $this->app = $app;
    }

    /**
     * Get VIES data for specified number
     * @param string $euvat EU VAT number with 2-letter country prefix
     * @return VIESData|false
     */
    public function get_vies_data($euvat)
    {
        // clear error
        $this->clear();
    
        // validate number and construct path
        if (! ($suffix = $this->get_path_suffix(Number::EUVAT, $euvat))) {
            return false;
        }
    
        $url = ($this->url . '/get/vies/' . $suffix);
    
        // send request
        $doc = $this->get($url);
    
        if (! $doc) {
            return false;
        }
    
        // parse response
        $vies = new VIESData();
    
        $vies->uid = $this->xpath($doc, '/result/vies/uid/text()');
        $vies->country_code = $this->xpath($doc, '/result/vies/countryCode/text()');
        $vies->vat_number = $this->xpath($doc, '/result/vies/vatNumber/text()');
        
        $vies->valid = ($this->xpath($doc, '/result/vies/valid/text()') == 'true' ? true : false);
        
        $vies->trader_name = $this->xpath($doc, '/result/vies/traderName/text()');
        $vies->trader_company_type = $this->xpath($doc, '/result/vies/traderCompanyType/text()');
        $vies->trader_address = $this->xpath($doc, '/result/vies/traderAddress/text()');
        
        $vies->id = $this->xpath($doc, '/result/vies/id/text()');
        $vies->date = $this->xpath_date($doc, '/result/vies/date/text()');
        $vies->source = $this->xpath($doc, '/result/vies/source/text()');

		return $vies;
    }

    /**
     * Get VIES data for specified number with trader address parsed into components
     * @param string $euvat EU VAT number with 2-letter country prefix
     * @return VIESData|false
     */
    public function get_vies_data_parsed($euvat)
    {
        // clear error
        $this->clear();

        // validate number and construct path
        if (! ($suffix = $this->get_path_suffix(Number::EUVAT, $euvat))) {
            return false;
        }

        $url = ($this->url . '/get/vies/parsed/' . $suffix);

        // send request
        $doc = $this->get($url);

        if (! $doc) {
            return false;
        }

        // parse response
        $vies = new VIESData();

        $vies->uid = $this->xpath($doc, '/result/vies/uid/text()');
        $vies->country_code = $this->xpath($doc, '/result/vies/countryCode/text()');
        $vies->vat_number = $this->xpath($doc, '/result/vies/vatNumber/text()');

        $vies->valid = ($this->xpath($doc, '/result/vies/valid/text()') == 'true');

        $vies->trader_name = $this->xpath($doc, '/result/vies/traderName/text()');

        if (!empty($this->xpath($doc, '/result/vies/traderNameComponents/name/text()'))) {
            $vies->trader_name_components = new NameComponents();
            $vies->trader_name_components->name = $this->xpath($doc, '/result/vies/traderNameComponents/name/text()');
            $vies->trader_name_components->legal_form = $this->xpath($doc, '/result/vies/traderNameComponents/legalForm/text()');
            $vies->trader_name_components->legal_form_canonical_id = intval($this->xpath($doc, '/result/vies/traderNameComponents/legalFormCanonicalId/text()'));
            $vies->trader_name_components->legal_form_canonical_name = $this->xpath($doc, '/result/vies/traderNameComponents/legalFormCanonicalName/text()');
        }

        $vies->trader_company_type = $this->xpath($doc, '/result/vies/traderCompanyType/text()');
        $vies->trader_address = $this->xpath($doc, '/result/vies/traderAddress/text()');

        if (!empty($this->xpath($doc, '/result/vies/traderAddressComponents/country/text()'))) {
            $vies->trader_address_components = new AddressComponents();
            $vies->trader_address_components->country = $this->xpath($doc, '/result/vies/traderAddressComponents/country/text()');
            $vies->trader_address_components->postal_code = $this->xpath($doc, '/result/vies/traderAddressComponents/postalCode/text()');
            $vies->trader_address_components->city = $this->xpath($doc, '/result/vies/traderAddressComponents/city/text()');
            $vies->trader_address_components->street = $this->xpath($doc, '/result/vies/traderAddressComponents/street/text()');
            $vies->trader_address_components->street_number = $this->xpath($doc, '/result/vies/traderAddressComponents/streetNumber/text()');
            $vies->trader_address_components->house_number = $this->xpath($doc, '/result/vies/traderAddressComponents/houseNumber/text()');
        }

        $vies->id = $this->xpath($doc, '/result/vies/id/text()');
        $vies->date = $this->xpath_date($doc, '/result/vies/date/text()');
        $vies->source = $this->xpath($doc, '/result/vies/source/text()');

        return $vies;
    }

    /**
     * Upload batch of VAT numbers and get their current VAT statuses and traders data
     * @param array $numbers array of EU VAT numbers
     * @return string|false batch token for checking status and getting the result
     */
    public function get_vies_data_async($numbers)
    {
        // clear error
        $this->clear();

        // validate input
        if (count($numbers) < 2 || count($numbers) > 99) {
            $this->set(Error::CLI_BATCH_SIZE);
            return false;
        }

        $req = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n"
            . "<request>\r\n"
            . "  <batch>\r\n"
            . "    <numbers>\r\n";

        foreach ($numbers as $number) {
            if (! EUVAT::is_valid($number)) {
                $this->set(Error::CLI_EUVAT);
                return false;
            }

            $req .= "      <number>" . EUVAT::normalize($number) . "</number>\r\n";
        }

        $req .= "    </numbers>\r\n"
            . "  </batch>\r\n"
            . "</request>";

        // send request
        $doc = $this->post($this->url . '/batch/vies', 'text/xml; charset=UTF-8', $req);

        if (! $doc) {
            return false;
        }

        // parse response
        $token = $this->xpath($doc, '/result/batch/token/text()');

        if (! $token) {
            $this->set(Error::CLI_RESPONSE);
            return false;
        }

        return $token;
    }

    /**
     * Check batch result and download data
     * @param string $token batch token received from get_vies_data_async function
     * @return BatchResult|false batch result
     */
    public function get_vies_data_async_result($token)
    {
        // clear error
        $this->clear();

        // validate input
        if (! $this->is_guid($token)) {
            $this->set(Error::CLI_INPUT);
            return false;
        }

        // send request
        $doc = $this->get($this->url . '/batch/vies/' . urlencode($token));

        if (! $doc) {
            return false;
        }

        // parse response
        $br = new BatchResult();

        for ($i = 1; ; $i++) {
            $uid = $this->xpath($doc, '/result/batch/numbers/vies[' . $i . ']/uid/text()');

            if (! $uid) {
                break;
            }

            $vd = new VIESData();
            $vd->uid = $uid;
            $vd->country_code = $this->xpath($doc, '/result/batch/numbers/vies[' . $i . ']/countryCode/text()');
            $vd->vat_number = $this->xpath($doc, '/result/batch/numbers/vies[' . $i . ']/vatNumber/text()');
            $vd->valid = ($this->xpath($doc, '/result/batch/numbers/vies[' . $i . ']/valid/text()') == 'true' ? true : false);
            $vd->trader_name = $this->xpath($doc, '/result/batch/numbers/vies[' . $i . ']/traderName/text()');
            $vd->trader_company_type = $this->xpath($doc, '/result/batch/numbers/vies[' . $i . ']/traderCompanyType/text()');
            $vd->trader_address = $this->xpath($doc, '/result/batch/numbers/vies[' . $i . ']/traderAddress/text()');
            $vd->id = $this->xpath($doc, '/result/batch/numbers/vies[' . $i . ']/id/text()');
            $vd->date = $this->xpath_date($doc, '/result/batch/numbers/vies[' . $i . ']/date/text()');
            $vd->source = $this->xpath($doc, '/result/batch/numbers/vies[' . $i . ']/source/text()');

            $br->numbers[] = $vd;
        }

        for ($i = 1; ; $i++) {
            $uid = $this->xpath($doc, '/result/batch/errors/error[' . $i . ']/uid/text()');

            if (! $uid) {
                break;
            }

            $ve = new VIESError();
            $ve->uid = $uid;
            $ve->country_code = $this->xpath($doc, '/result/batch/errors/error[' . $i . ']/countryCode/text()');
            $ve->vat_number = $this->xpath($doc, '/result/batch/errors/error[' . $i . ']/vatNumber/text()');
            $ve->error = $this->xpath($doc, '/result/batch/errors/error[' . $i . ']/error/text()');
            $ve->date = $this->xpath_date($doc, '/result/batch/errors/error[' . $i . ']/date/text()');
            $ve->source = $this->xpath($doc, '/result/batch/errors/error[' . $i . ']/source/text()');

            $br->errors[] = $ve;
        }

        return $br;
    }

    /**
     * Get current account status
     * @return AccountStatus|false
     */
    public function get_account_status()
    {
        // clear error
        $this->clear();
    
        // construct path
        $url = ($this->url . '/check/account/status');
    
        // send request
        $doc = $this->get($url);
    
        if (! $doc) {
            return false;
        }
    
        // parse response
        $as = new AccountStatus();
    
        $as->uid = $this->xpath($doc, '/result/account/uid/text()');
        $as->type = $this->xpath($doc, '/result/account/type/text()');
        $as->valid_to = $this->xpath_datetime($doc, '/result/account/validTo/text()');
		$as->billing_plan_name = $this->xpath($doc, '/result/account/billingPlan/name/text()');
		
		$as->subscription_price = floatval($this->xpath($doc, '/result/account/billingPlan/subscriptionPrice/text()'));
		$as->item_price = floatval($this->xpath($doc, '/result/account/billingPlan/itemPrice/text()'));
		$as->item_price_status = floatval($this->xpath($doc, '/result/account/billingPlan/itemPriceCheckStatus/text()'));
        $as->item_price_parsed = floatval($this->xpath($doc, '/result/account/billingPlan/itemPriceStatusParsed/text()'));

		$as->limit = intval($this->xpath($doc, '/result/account/billingPlan/limit/text()'));
		$as->request_delay = intval($this->xpath($doc, '/result/account/billingPlan/requestDelay/text()'));
		$as->domain_limit = intval($this->xpath($doc, '/result/account/billingPlan/domainLimit/text()'));
		
		$as->overplan_allowed = ($this->xpath($doc, '/result/account/billingPlan/overplanAllowed/text()') == 'true' ? true : false);
		$as->excel_addin = ($this->xpath($doc, '/result/account/billingPlan/excelAddin/text()') == 'true' ? true : false);
		$as->app = ($this->xpath($doc, '/result/account/billingPlan/app/text()') == 'true' ? true : false);
        $as->cli = ($this->xpath($doc, '/result/account/billingPlan/cli/text()') == 'true' ? true : false);
		$as->stats = ($this->xpath($doc, '/result/account/billingPlan/stats/text()') == 'true' ? true : false);
		$as->monitor = ($this->xpath($doc, '/result/account/billingPlan/monitor/text()') == 'true' ? true : false);
		
		$as->func_get_vies_data = ($this->xpath($doc, '/result/account/billingPlan/funcGetVIESData/text()') == 'true' ? true : false);
        $as->func_get_vies_data_parsed = ($this->xpath($doc, '/result/account/billingPlan/funcGetVIESDataParsed/text()') == 'true' ? true : false);

		$as->vies_data_count = intval($this->xpath($doc, '/result/account/requests/viesData/text()'));
        $as->vies_data_parsed_count = intval($this->xpath($doc, '/result/account/requests/viesDataParsed/text()'));
		$as->total_count = intval($this->xpath($doc, '/result/account/requests/total/text()'));
        
        return $as;
    }

	/**
     * Get last error code
     * @return int error code
     */
    public function get_last_error_code()
    {
        return $this->errcode;
    }

    /**
     * Get last error message
     * @return string error message
     */
    public function get_last_error()
    {
        return $this->err;
    }

    /**
     * Clear error
     */
    private function clear()
    {
        $this->errcode = 0;
        $this->err = '';
    }

    /**
     * Set error details
     * @param int $code error code
     * @param string $err error message
     */
    private function set($code, $err = null)
    {
        $this->errcode = $code;
        $this->err = (empty($err) ? Error::message($code) : $err);
    }

    /**
     * Prepare authorization header content
     * @param string $method HTTP method
     * @param string $url target URL
     * @return string|false
     */
    private function auth($method, $url)
    {
        // parse url
        $u = parse_url($url);
        
        if (! array_key_exists('port', $u)) {
            $u['port'] = ($u['scheme'] == 'https' ? '443' : '80');
        }
        
        // prepare auth header
        $nonce = bin2hex(openssl_random_pseudo_bytes(4));
        $ts = time();
        
        $str = "" . $ts . "\n"
            . $nonce . "\n"
            . $method . "\n"
            . $u['path'] . "\n"
            . $u['host'] . "\n"
            . $u['port'] . "\n"
            . "\n";
        
        $mac = base64_encode(hash_hmac('sha256', $str, $this->key, true));
        
        if (! $mac) {
            return false;
        }
        
        return 'Authorization: MAC id="' . $this->id . '", ts="' . $ts . '", nonce="' . $nonce . '", mac="' . $mac . '"';
    }

    /**
     * Prepare user agent information header content
     * @return string
     */
    private function user_agent()
    {
        return 'User-Agent: ' . (! empty($this->app) ? $this->app . ' ' : '') . 'VIESAPIClient/' . self::VERSION
            . ' PHP/' . phpversion();
    }

    /**
     * Set some common CURL options
     * @param resource $curl
     */
    private function set_curl_opt($curl)
    {
        if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
            // curl on a windows does not know where to look for certificates
            // use local info downloaded from https://curl.haxx.se/docs/caextract.html
            curl_setopt($curl, CURLOPT_CAINFO, __DIR__ . DIRECTORY_SEPARATOR . 'cacert.pem');
        }
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
    }

    /**
     * Get result of HTTP GET request
     * @param string $url target URL
     * @return \SimpleXMLElement|false
     */
    private function get($url)
    {
        // auth
        $auth = $this->auth('GET', $url);
        
        if (! $auth) {
            $this->set(Error::CLI_CONNECT);
            return false;
        }
        
        // headers
        $headers = array(
            'Accept: text/xml',
            $auth,
            $this->user_agent()
        );
        
        // send request
        $curl = curl_init();
        
        if (! $curl) {
            $this->set(Error::CLI_CONNECT);
            return false;
        }
        
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $this->set_curl_opt($curl);
        
        $res = curl_exec($curl);
        
        if (! $res) {
            $this->set(Error::CLI_CONNECT);
            return false;
        }

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if (! $code) {
            $this->set(Error::CLI_RESPONSE);
            return false;
        }

        // parse response
        $doc = simplexml_load_string($res);

        if (! $doc) {
            $this->set(Error::CLI_RESPONSE);
            return false;
        }

        $err = $this->xpath($doc, '/result/error/code/text()');

        if (strlen($err) > 0) {
            $this->set(intval($err), $this->xpath($doc, '/result/error/description/text()'));
            return false;
        }

        if ($code != 200) {
            $this->set(Error::CLI_RESPONSE);
            return false;
        }

        return $doc;
    }

    /**
     * Get result of HTTP POST request
     * @param string $url target URL
     * @param string $content_type content type
     * @param string $content content
     * @return \SimpleXMLElement|false
     */
    private function post($url, $content_type, $content)
    {
        // auth
        $auth = $this->auth('POST', $url);

        if (! $auth) {
            $this->set(Error::CLI_CONNECT);
            return false;
        }

        // headers
        $headers = array(
            'Accept: text/xml',
            $auth,
            'Content-Type: ' . $content_type,
            'Content-Length: ' . strlen($content),
            $this->user_agent()
        );

        // send request
        $curl = curl_init();

        if (! $curl) {
            $this->set(Error::CLI_CONNECT);
            return false;
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $content);
        $this->set_curl_opt($curl);

        $res = curl_exec($curl);

        if (! $res) {
            $this->set(Error::CLI_CONNECT);
            return false;
        }

        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if (! $code) {
            $this->set(Error::CLI_RESPONSE);
            return false;
        }

        // parse response
        $doc = simplexml_load_string($res);

        if (! $doc) {
            $this->set(Error::CLI_RESPONSE);
            return false;
        }

        $err = $this->xpath($doc, '/result/error/code/text()');

        if (strlen($err) > 0) {
            $this->set(intval($err), $this->xpath($doc, '/result/error/description/text()'));
            return false;
        }

        if ($code != 200) {
            $this->set(Error::CLI_RESPONSE);
            return false;
        }

        return $doc;
    }

    /**
     * Get element content as text
     * @param \SimpleXMLElement $doc XML document
     * @param string $path xpath string
     * @return string
     */
    private function xpath($doc, $path)
    {
        $a = $doc->xpath($path);
        
        if (! $a) {
            return '';
        }
        
        if (count($a) != 1) {
            return '';
        }
        
        return trim($a[0]);
    }

    /**
     * Get element content as date in format yyyy-mm-dd
     * @param \SimpleXMLElement $doc XML document
     * @param string $path xpath string
     * @return string output date
     */
    private function xpath_date($doc, $path)
    {
        $val = $this->xpath($doc, $path);
        
        if (empty($val)) {
            return '';
        }
        
        return date('Y-m-d', strtotime($val));
    }

    /**
     * Get element content as date and time in format yyyy-mm-dd hh:mm:ss
     * @param \SimpleXMLElement $doc XML document
     * @param string $path xpath string
     * @return string output date time
     */
    private function xpath_datetime($doc, $path)
    {
        $val = $this->xpath($doc, $path);

        if (empty($val)) {
            return '';
        }

        return date('Y-m-d H:i:s', strtotime($val));
    }

    /**
     * Get path suffix
     * @param int $type search number type as Number::xxx value
     * @param string $number search number value
     * @return string|false
     */
    private function get_path_suffix($type, $number)
    {
        if ($type == Number::NIP) {
            if (! NIP::is_valid($number)) {
                $this->set(Error::CLI_NIP);
                return false;
            }
        
            $path = 'nip/' . NIP::normalize($number);
        } else if ($type == Number::EUVAT) {
            if (! EUVAT::is_valid($number)) {
                $this->set(Error::CLI_EUVAT);
                return false;
            }
        
            $path = 'euvat/' . EUVAT::normalize($number);
        } else {
            $this->set(Error::CLI_NUMBER);
            return false;
        }
        
        return $path;
    }

    /**
     * Checks if specified string is valid GUID
     * @param string $guid input string
     * @return bool true if string is valid GUID
     */
    public function is_guid($guid)
    {
        if (preg_match('/^[a-f\d]{8}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{4}-[a-f\d]{12}$/i', $guid) != 1) {
            return false;
        }

        return true;
    }
}

/* EOF */
