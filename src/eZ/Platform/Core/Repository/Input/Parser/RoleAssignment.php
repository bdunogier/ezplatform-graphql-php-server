<?php

/**
 * File containing the RoleAssignment parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\API\Repository\Values\User\Limitation as APILimitation;
use eZ\Publish\Core\REST\Client;

/**
 * Parser for RoleAssignment.
 */
class RoleAssignment extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\RoleAssignment
     *
     * @todo Error handling
     * @todo Use dependency injection system for Role Limitation lookup
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $roleLimitation = null;
        if (array_key_exists('limitation', $data)) {
            $limitation = $parsingDispatcher->parse($data['limitation'], $data['limitation']['_media-type']);
            switch ($limitation->getIdentifier()) {
                case APILimitation::SECTION:
                    $roleLimitation = new \App\eZ\Platform\API\Repository\Values\User\Limitation\SectionLimitation();
                    break;

                case APILimitation::SUBTREE:
                    $roleLimitation = new \App\eZ\Platform\API\Repository\Values\User\Limitation\SubtreeLimitation();
                    break;

                default:
                    throw new \eZ\Publish\Core\Base\Exceptions\NotFoundException('RoleLimitation', $limitation->getIdentifier());
            }

            $roleLimitation->limitationValues = $limitation->limitationValues;
        }

        return new Client\Values\User\RoleAssignment(
            array(
                'role' => $parsingDispatcher->parse($data['Role'], $data['Role']['_media-type']),
                'limitation' => $roleLimitation,
            )
        );
    }
}
