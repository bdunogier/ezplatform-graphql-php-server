<?php

/**
 * File containing the FieldDefinitionList parser class.
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

/**
 * Parser for FieldDefinitionList.
 */
class FieldDefinitionList extends BaseParser
{
    /** @var \App\eZ\Platform\Core\Repository\Input\ParserTools */
    protected $parserTools;

    /** @var \App\eZ\Platform\API\Repository\ContentTypeService */
    protected $contentTypeService;

    /**
     * @param \App\eZ\Platform\Core\Repository\Input\ParserTools $parserTools
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
     * @return \App\eZ\Platform\Core\Repository\Values\FieldDefinitionList
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $fieldDefinitionReferences = array();

        foreach ($data['FieldDefinition'] as $fieldDefinitionData) {
            $fieldDefinitionReferences[] = $this->parserTools->parseObjectElement(
                $fieldDefinitionData,
                $parsingDispatcher
            );
        }

        return new Values\FieldDefinitionList(
            $this->contentTypeService,
            $fieldDefinitionReferences
        );
    }
}
