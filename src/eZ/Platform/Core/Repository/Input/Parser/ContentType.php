<?php

/**
 * File containing the ContentType parser class.
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
 * Parser for ContentType.
 */
class ContentType extends BaseParser
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
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentType
     *
     * @todo Error handling
     * @todo What about missing properties? Set them here, using the service to
     *       load? Or better set them in the service, since loading is really
     *       unsuitable here?
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $creatorId = $this->parserTools->parseObjectElement($data['Creator'], $parsingDispatcher);
        $modifierId = $this->parserTools->parseObjectElement($data['Modifier'], $parsingDispatcher);

        $fieldDefinitionListReference = $this->parserTools->parseObjectElement($data['FieldDefinitions'], $parsingDispatcher);

        $contentType = new Values\ContentType\ContentType(
            $this->contentTypeService,
            array(
                'id' => $data['_href'],
                'status' => $this->parserTools->parseStatus($data['status']),
                'identifier' => $data['identifier'],
                'names' => isset($data['names']) ? $this->parserTools->parseTranslatableList($data['names']) : null,
                'descriptions' => isset($data['descriptions']) ? $this->parserTools->parseTranslatableList($data['descriptions']) : null,
                'creationDate' => new \DateTime($data['creationDate']),
                'modificationDate' => new \DateTime($data['modificationDate']),
                'creatorId' => $creatorId,
                'modifierId' => $modifierId,
                'remoteId' => $data['remoteId'],
                'urlAliasSchema' => $data['urlAliasSchema'],
                'nameSchema' => $data['nameSchema'],
                'isContainer' => $this->parserTools->parseBooleanValue($data['isContainer']),
                'mainLanguageCode' => $data['mainLanguageCode'],
                'defaultAlwaysAvailable' => $this->parserTools->parseBooleanValue($data['defaultAlwaysAvailable']),
                'defaultSortOrder' => $this->parserTools->parseDefaultSortOrder($data['defaultSortOrder']),
                'defaultSortField' => $this->parserTools->parseDefaultSortField($data['defaultSortField']),

                'fieldDefinitionListReference' => $fieldDefinitionListReference,
            )
        );

        if ($contentType->status === Values\ContentType\ContentType::STATUS_DRAFT) {
            return new Values\ContentType\ContentTypeDraft($contentType);
        }

        return $contentType;
    }
}
