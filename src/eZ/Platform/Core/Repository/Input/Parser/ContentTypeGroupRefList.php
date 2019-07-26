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
use App\eZ\Platform\Core\Repository\Values;
use App\eZ\Platform\API\Repository\ContentTypeService;

class ContentTypeGroupRefList extends BaseParser
{
    /** @var \App\eZ\Platform\Core\Repository\Input\ParserTools */
    protected $parserTools;

    /** @var \App\eZ\Platform\API\Repository\ContentTypeService */
    protected $contentTypeService;

    /**
     * @param ParserTools $parserTools
     * @param \App\eZ\Platform\API\Repository\ContentTypeService $contentTypeService
     */
    public function __construct(ParserTools $parserTools, ContentTypeService $contentTypeService)
    {
        $this->parserTools = $parserTools;
        $this->contentTypeService = $contentTypeService;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \App\eZ\Platform\Core\Repository\Values\ContentTypeGroupRefList
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $contentTypeGroupReferences = array();
        foreach ($data['ContentTypeGroupRef'] as $groupData) {
            $contentTypeGroupReferences[] = $groupData['_href'];
        }

        return new Values\ContentTypeGroupRefList(
            $this->contentTypeService,
            $data['_href'],
            $contentTypeGroupReferences
        );
    }
}
