<?php

/**
 * File containing the ContentTypeList parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;

/**
 * Parser for ContentTypeList.
 */
class ContentTypeList extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentType[]
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $contentTypes = array();
        foreach ($data['ContentType'] as $rawContentTypeData) {
            $contentTypes[] = $parsingDispatcher->parse(
                $rawContentTypeData,
                $rawContentTypeData['_media-type']
            );
        }

        return $contentTypes;
    }
}
