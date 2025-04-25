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
 * Trader name components
 */
class NameComponents
{
    public $name;

    public $legal_form;

    public $legal_form_canonical_id;

    public $legal_form_canonical_name;

    /**
     * Get object data as string
     * @return string
     */
    public function __toString()
    {
        return 'NameComponents: [name = ' . $this->name
            . ', legal_form = ' . $this->legal_form
            . ', legal_form_canonical_id = ' . $this->legal_form_canonical_id
            . ', legal_form_canonical_name = ' . $this->legal_form_canonical_name
            . ']';
    }
}

/* EOF */
