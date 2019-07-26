<?php

/**
 * File containing the Section parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\API\Repository\Values;

/**
 * Parser for Section.
 */
class Section extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Section
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        return new Values\Content\Section(
            array(
                'id' => $data['_href'],
                'identifier' => $data['identifier'],
                'name' => $data['name'],
            )
        );
    }
}
