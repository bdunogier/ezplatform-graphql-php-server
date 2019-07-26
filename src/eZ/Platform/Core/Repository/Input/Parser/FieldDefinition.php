<?php

/**
 * File containing the FieldDefinition parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\ParserTools;
use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\Core\Repository\Input\FieldTypeParser;
use App\eZ\Platform\Core\Repository\Values;

/**
 * Parser for Version.
 *
 * @todo Caching for extracted embedded objects
 */
class FieldDefinition extends BaseParser
{
    /** @var \App\eZ\Platform\Core\Repository\Input\ParserTools */
    protected $parserTools;

    /** @var \App\eZ\Platform\Core\Repository\Input\FieldTypeParser */
    protected $fieldTypeParser;

    /**
     * @param \App\eZ\Platform\Core\Repository\Input\ParserTools $parserTools
     * @param \App\eZ\Platform\Core\Repository\Input\FieldTypeParser $fieldTypeParser
     */
    public function __construct(ParserTools $parserTools, FieldTypeParser $fieldTypeParser)
    {
        $this->parserTools = $parserTools;
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
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        return new Values\ContentType\FieldDefinition(
            array(
                'id' => $data['_href'],
                'identifier' => $data['identifier'],
                'fieldTypeIdentifier' => $data['fieldType'],
                'fieldGroup' => $data['fieldGroup'],
                'position' => (int)$data['position'],
                'isTranslatable' => $this->parserTools->parseBooleanValue($data['isTranslatable']),
                'isRequired' => $this->parserTools->parseBooleanValue($data['isRequired']),
                'isInfoCollector' => $this->parserTools->parseBooleanValue($data['isInfoCollector']),
                'isSearchable' => $this->parserTools->parseBooleanValue($data['isSearchable']),
                'names' => isset($data['names']) ?
                    $this->parserTools->parseTranslatableList($data['names']) :
                    null,
                'descriptions' => isset($data['descriptions']) ?
                    $this->parserTools->parseTranslatableList($data['descriptions']) :
                    null,

                'defaultValue' => $this->fieldTypeParser->parseValue(
                    $data['fieldType'],
                    $data['defaultValue']
                ),
                'fieldSettings' => $this->fieldTypeParser->parseFieldSettings(
                    $data['fieldType'],
                    $data['fieldSettings']
                ),
                'validators' => $this->fieldTypeParser->parseValidatorConfiguration(
                    $data['fieldType'],
                    $data['validatorConfiguration']
                ),
            )
        );
    }
}
