<?php

/**
 * File containing the Content parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\ParserTools;
use App\eZ\Platform\API\Repository\ContentService;
use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\FieldTypeParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\Core\Repository\Values;
use App\eZ\Platform\API\Repository\Values\Content\Field;

/**
 * Parser for Version.
 *
 * @todo Integrate FieldType fromHash()
 * @todo Caching for extracted embedded objects
 */
class Content extends BaseParser
{
    /**
     * VersionInfo parser.
     *
     * @var \App\eZ\Platform\Core\Repository\Input\Parser\VersionInfo
     */
    protected $versionInfoParser;

    /** @var \App\eZ\Platform\Core\Repository\Input\ParserTools */
    protected $parserTools;

    /** @var \App\eZ\Platform\API\Repository\ContentService */
    protected $contentService;

    /** @var \App\eZ\Platform\Core\Repository\Input\FieldTypeParser */
    protected $fieldTypeParser;

    /**
     * @param \App\eZ\Platform\Core\Repository\Input\ParserTools $parserTools
     * @param \App\eZ\Platform\API\Repository\ContentService $contentService
     * @param \App\eZ\Platform\Core\Repository\Input\Parser\VersionInfo $versionInfoParser
     * @param \App\eZ\Platform\Core\Repository\Input\FieldTypeParser $fieldTypeParser
     */
    public function __construct(ParserTools $parserTools, ContentService $contentService, VersionInfo $versionInfoParser, FieldTypeParser $fieldTypeParser)
    {
        $this->parserTools = $parserTools;
        $this->contentService = $contentService;
        $this->versionInfoParser = $versionInfoParser;
        $this->fieldTypeParser = $fieldTypeParser;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $versionInfo = $this->versionInfoParser->parse(
            $data['CurrentVersion']['Version']['VersionInfo'],
            $parsingDispatcher
        );
        $fields = $this->parseFields(
            $data['CurrentVersion']['Version']['Fields'],
            '/api/ezp/v2/content/objects/' . $versionInfo->contentInfoId
        );

        return new Values\Content\Content(
            $this->contentService,
            array(
                'versionInfo' => $versionInfo,
                'internalFields' => $fields,
            )
        );
    }

    /**
     * Parses the fields from the given $rawFieldsData.
     *
     * @param array $rawFieldsData
     * @param string $contentId
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Field[]
     */
    protected function parseFields(array $rawFieldsData, $contentId)
    {
        $fields = array();

        if (isset($rawFieldsData['field'])) {
            foreach ($rawFieldsData['field'] as $rawFieldData) {
                $fields[] = new Field(
                    array(
                        'id' => $rawFieldData['id'],
                        'fieldDefIdentifier' => $rawFieldData['fieldDefinitionIdentifier'],
                        'languageCode' => $rawFieldData['languageCode'],
                        'value' => $this->fieldTypeParser->parseFieldValue(
                            $contentId,
                            $rawFieldData['fieldDefinitionIdentifier'],
                            $rawFieldData['fieldValue']
                        ),
                    )
                );
            }
        }

        return $fields;
    }
}
