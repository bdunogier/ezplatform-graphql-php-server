<?php

/**
 * File containing the ContentTypeGroupRefList parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\ParserTools;
use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\API\Repository\ContentTypeService;
use App\eZ\Platform\API\Repository\Values;

/**
 * Parser for ContentTypeGroupRefList.
 */
class UrlAliasRefList extends BaseParser
{
    /** @var \App\eZ\Platform\Core\Repository\Input\ParserTools */
    protected $parserTools;

    /**
     * @param ParserTools $parserTools
     */
    public function __construct(ParserTools $parserTools)
    {
        $this->parserTools = $parserTools;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\URLAliasRefList
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $refList = array_map(
            function($ref) {
                return new Values\Content\URLAliasRef([
                    'href' => $ref['_href'],
                    'mediaType' => $ref['_media-type']
                ]);
            },
            $data['UrlAlias']
        );

        return new Values\Content\URLAliasRefList([
            'id' => $data['_href'],
            'refList' => $refList
        ]);
    }
}
