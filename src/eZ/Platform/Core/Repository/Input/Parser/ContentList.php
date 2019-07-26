<?php

/**
 * File containing the ContentList parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;

/**
 * Parser for ContentList.
 */
class ContentList extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentInfo[]
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $contents = array();
        foreach ($data['Content'] as $rawContentData) {
            $contents[] = $parsingDispatcher->parse(
                $rawContentData,
                $rawContentData['_media-type']
            );
        }

        return $contents;
    }
}
