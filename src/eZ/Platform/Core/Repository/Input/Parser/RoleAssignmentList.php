<?php

/**
 * File containing the RoleAssignmentList parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;

/**
 * Parser for RoleAssignmentList.
 */
class RoleAssignmentList extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\RoleAssignment[]
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $roleAssignments = array();
        foreach ($data['RoleAssignment'] as $rawRoleAssignmentData) {
            $roleAssignments[] = $parsingDispatcher->parse(
                $rawRoleAssignmentData,
                $rawRoleAssignmentData['_media-type']
            );
        }

        return $roleAssignments;
    }
}
