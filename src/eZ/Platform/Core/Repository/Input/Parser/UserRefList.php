<?php

/**
 * File containing the RoleList parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\Core\Repository\Exceptions;

/**
 * Parser for UserRefList.
 */
class UserRefList extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\Role[]
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('User', $data) || !is_array($data['User'])) {
            throw new Exceptions\Parser("Missing 'User' element in UserRefList.");
        }

        // workaround for list with 1 items parsed as non arrays
        if (!isset($data['User'][0])) {
            $data['User'] = array($data['User']);
        }

        $userRefList = array();
        foreach ($data['User'] as $userRefData) {
            $hrefData = $this->requestParser->parse($userRefData['_href']);
            if (!isset($hrefData['userId'])) {
                throw new Exceptions\Parser("Invalid application/vnd.ez.api.User href");
            }
            $userRefList[] = $hrefData['userId'];
        }
        return $userRefList;
    }
}
