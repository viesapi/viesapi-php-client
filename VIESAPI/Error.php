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
 * VIES API errors
 */
class Error
{
    const NIP_BAD				= 7;
    const CONTENT_SYNTAX		= 8;
    const INVALID_PATH			= 10;
    const EXCEPTION				= 11;
    const NO_PERMISSION			= 12;
    const GEN_INVOICES			= 13;
    const GEN_SPEC_INV			= 14;
    const SEND_INVOICE			= 15;
    const SEND_ANNOUNCEMENT		= 17;
    const INVOICE_PAYMENT		= 18;
    const SEARCH_KEY_EMPTY		= 20;
    const EUVAT_BAD				= 22;
    const VIES_SYNC				= 23;
    const PLAN_FEATURE			= 26;
    const SEARCH_TYPE			= 27;
    const NIP_FEATURE			= 30;
    const TEST_MODE				= 33;
    const ACCESS_DENIED			= 35;
    const MAINTENANCE			= 36;
    const BILLING_PLANS			= 37;
    const DOCUMENT_PDF			= 38;
    const EXPORT_PDF			= 39;
    const GROUP_CHECKS			= 42;
    const CLIENT_COUNTERS		= 43;
    const SEND_REMAINDER		= 47;
    const EXPORT_JPK			= 48;
    const GEN_ORDER_INV			= 49;
    const SEND_EXPIRATION		= 50;
    const ORDER_CANCEL          = 52;
    const AUTH_TIMESTAMP        = 54;
    const AUTH_MAC              = 55;
    const SEND_MAIL             = 56;
    const AUTH_KEY              = 57;
    const VIES_TOO_MANY_REQ     = 58;
    const VIES_UNAVAILABLE      = 59;
    const GEOCODE               = 60;
    const BATCH_SIZE            = 61;
    const BATCH_PROCESSING      = 62;
    const BATCH_REJECTED        = 63;

    const DB_AUTH_IP			= 101;
	const DB_AUTH_KEY_STATUS	= 102;
	const DB_AUTH_KEY_VALUE		= 103;
	const DB_AUTH_OVER_PLAN		= 104;
	const DB_CLIENT_LOCKED		= 105;
	const DB_CLIENT_TYPE		= 106;
	const DB_CLIENT_NOT_PAID	= 107;
	const DB_AUTH_KEYID_VALUE	= 108;

	const CLI_CONNECT           = 201;
	const CLI_RESPONSE          = 202;
	const CLI_NUMBER            = 203;
	const CLI_NIP               = 204;
	const CLI_EUVAT             = 205;
	const CLI_EXCEPTION         = 206;
	const CLI_DATEFORMAT        = 207;
	const CLI_INPUT             = 208;
    const CLI_BATCH_SIZE        = 209;

	private static $codes = array(
		self::CLI_CONNECT     => 'Failed to connect to the VIES API service',
        self::CLI_RESPONSE    => 'VIES API service response has invalid format',
        self::CLI_NUMBER      => 'Invalid number type',
        self::CLI_NIP         => 'NIP is invalid',
        self::CLI_EUVAT       => 'EU VAT ID is invalid',
		self::CLI_EXCEPTION   => 'Function generated an exception',
		self::CLI_DATEFORMAT  => 'Date has an invalid format',
		self::CLI_INPUT       => 'Invalid input parameter',
        self::CLI_BATCH_SIZE  => 'Batch size limit exceeded [2-99]'
    );

    /**
     * Get an error message
     * @param int $code error code
     * @return string error message
     */
	public static function message($code)
	{
	    if ($code < self::CLI_CONNECT || $code > self::CLI_BATCH_SIZE) {
	        return null;
        }

		return self::$codes[$code];
	}
}

/* EOF */
