<?php

/**
 * File containing the Location parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\Core\Repository\Input\ParserTools;
use App\eZ\Platform\Core\Repository\Values;

/**
 * Parser for Location.
 */
class Location extends BaseParser
{
    /** @var \App\eZ\Platform\Core\Repository\Input\ParserTools */
    protected $parserTools;

    /**
     * @param \App\eZ\Platform\Core\Repository\Input\ParserTools $parserTools
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
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $contentInfo = $parsingDispatcher->parse($data['ContentInfo']['Content']);

        return new Values\Content\Location(
            array(
                'contentInfo' => $contentInfo,
                'id' => $data['_href'],
                'priority' => (int)$data['priority'],
                'hidden' => $data['hidden'] === 'true',
                'invisible' => $data['invisible'] === 'true',
                'remoteId' => $data['remoteId'],
                'parentLocationId' => $data['ParentLocation']['_href'],
                'pathString' => $data['pathString'],
                'depth' => (int)$data['depth'],
                'sortField' => $this->parserTools->parseDefaultSortField($data['sortField']),
                'sortOrder' => $this->parserTools->parseDefaultSortOrder($data['sortOrder']),

                'references' => [
                    'children' => $data['Children'],
                    'content' => $data['Content'],
                    'urlAliases' => $data['UrlAliases'],
                    'parentLocation' => $data['ParentLocation'],
                ]
            )
        );
    }
}
