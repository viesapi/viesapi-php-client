<?php
/**
 * Copyright 2022 NETCAT (www.netcat.pl)
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
 * @copyright 2022 NETCAT (www.netcat.pl)
 * @license http://www.apache.org/licenses/LICENSE-2.0
 */

namespace VIESAPI;

/**
 * Current account status information
 */
class AccountStatus
{
    public $uid;

    public $type;

    public $valid_to;

    public $billing_plan_name;

    public $subscription_price;

    public $item_price;

    public $item_price_status;

    public $limit;

    public $request_delay;

    public $domain_limit;

    public $overplan_allowed;

    public $excel_addin;
	
	public $app;

    public $cli;

    public $stats;

    public $monitor;

    public $func_get_vies_data;

    public $vies_data_count;
	
    public $total_count;

    /**
     * Get object data as string
     * @return string
     */
    public function __toString()
    {
        return 'AccountStatus: [uid = ' . $this->uid
            . ', type = ' . $this->type
            . ', valid_to = ' . $this->valid_to
            . ', billing_plan_name = ' . $this->billing_plan_name

            . ', subscription_price = ' . $this->subscription_price
            . ', item_price = ' . $this->item_price
            . ', item_price_status = ' . $this->item_price_status

            . ', limit = ' . $this->limit
            . ', request_delay = ' . $this->request_delay
            . ', domain_limit = ' . $this->domain_limit

            . ', overplan_allowed = ' . $this->overplan_allowed
            . ', excel_addin = ' . $this->excel_addin
            . ', app = ' . $this->app
            . ', cli = ' . $this->cli
            . ', stats = ' . $this->stats
            . ', monitor = ' . $this->monitor

            . ', func_get_vies_data = ' . $this->func_get_vies_data

            . ', vies_data_count = ' . $this->vies_data_count
            . ', total_count = ' . $this->total_count
            . ']';
    }
}

/* EOF */
