<?php
/**
 * Copyright 2022-2025 NETCAT (www.netcat.pl)
 *
 * Licensed under the Apache License, Version 2.0 (the 'License');
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an 'AS IS' BASIS,
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
 * VIES data
 */
class VIESData
{
    public $uid;
    
    public $country_code;

    public $vat_number;

    public $valid;

    public $trader_name;

    /**
     * @var NameComponents
     */
    public $trader_name_components;

    public $trader_company_type;

    public $trader_address;

    /**
     * @var AddressComponents
     */
    public $trader_address_components;

    public $id;

    public $date;

    public $source;

    /**
     * Get object data as string
     * @return string
     */
    public function __toString()
    {
        return 'VIESData: [uid = ' . $this->uid
            . ', country_code = ' . $this->country_code
            . ', vat_number = ' . $this->vat_number
            . ', valid = ' . ($this->valid ? 'true' : 'false')
            . ', trader_name = ' . $this->trader_name
            . ', trader_name_components = ' . $this->trader_name_components
            . ', trader_company_type = ' . $this->trader_company_type
            . ', trader_address = ' . $this->trader_address
            . ', trader_address_components = ' . $this->trader_address_components
            . ', id = ' . $this->id
            . ', date = ' . $this->date
            . ', source = ' . $this->source
            . ']';
    }
}

/* EOF */
