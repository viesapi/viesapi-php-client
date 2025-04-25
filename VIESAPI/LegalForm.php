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
 * Legal form types
 */
abstract class LegalForm
{
    const SOLE_PROPRIETORSHIP = 1;
    const LIMITED_LIABILITY_COMPANY = 2;
    const GENERAL_PARTNERSHIP = 3;
    const JOINT_STOCK_COMPANY = 4;
    const LIMITED_PARTNERSHIP = 5;
    const PRIVATE_LIMITED_LIABILITY_COMPANY = 6;
    const SINGLE_MEMBER_JOINT_STOCK_COMPANY = 7;
    const SIMPLE_LIMITED_LIABILITY_COMPANY = 8;
    const SINGLE_MEMBER_LIMITED_LIABILITY_COMPANY = 9;
    const SIMPLIFIED_JOINT_STOCK_COMPANY = 10;
    const SMALL_COMPANY = 11;
    const LIMITED_JOINT_STOCK_PARTNERSHIP = 12;
    const PROFESSIONAL_PARTNERSHIP = 13;
    const LIMITED_LIABILITY_PARTNERSHIP = 14;
    const PRIVATE_PARTNERSHIP = 15;
    const LIMITED_LIABILITY_COMPANY_LIMITED_PARTNERSHIP = 16;
    const LIMITED_LIABILITY_COMPANY_LIMITED_JOINT_STOCK_PARTNERSHIP = 17;
    const PUBLIC_INSTITUTION = 18;
}

/* EOF */
