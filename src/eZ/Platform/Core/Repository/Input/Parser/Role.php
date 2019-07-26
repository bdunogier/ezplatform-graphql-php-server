<?php

/**
 * File containing the Role parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use eZ\Publish\Core\REST\Client;

/**
 * Parser for Role.
 */
class Role extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\Role
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        return new Client\Values\User\Role(
            array(
                'id' => $data['_href'],
                'identifier' => $data['identifier'],
            )
        );
    }
}
