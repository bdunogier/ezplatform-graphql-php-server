<?php

/**
 * File containing the LocationList parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;

/**
 * Parser for LocationList.
 */
class LocationList extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location[]
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $locations = array();
        foreach ($data['Location'] as $rawLocationData) {
            $locations[] = $parsingDispatcher->parse(
                $rawLocationData,
                $rawLocationData['_media-type']
            );
        }

        return $locations;
    }
}
