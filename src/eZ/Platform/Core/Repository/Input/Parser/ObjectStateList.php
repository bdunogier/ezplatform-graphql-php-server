<?php

/**
 * File containing the ObjectStateList parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;

/**
 * Parser for ObjectStateList.
 */
class ObjectStateList extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState[]
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $states = array();
        foreach ($data['ObjectState'] as $rawStateData) {
            $states[] = $parsingDispatcher->parse(
                $rawStateData,
                $rawStateData['_media-type']
            );
        }

        return $states;
    }
}
