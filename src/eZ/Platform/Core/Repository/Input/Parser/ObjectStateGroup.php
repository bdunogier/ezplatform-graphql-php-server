<?php

/**
 * File containing the ObjectStateGroup parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\Core\Repository\Input\ParserTools;
use App\eZ\Platform\Core\Repository\Values\ObjectState\ObjectStateGroup as CoreObjectStateGroup;

/**
 * Parser for ObjectStateGroup.
 */
class ObjectStateGroup extends BaseParser
{
    /** @var \App\eZ\Platform\Core\Repository\Input\ParserTools */
    protected $parserTools;

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
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $names = $this->parserTools->parseTranslatableList($data['names']);

        $descriptions = isset($data['descriptions'])
            ? $this->parserTools->parseTranslatableList($data['descriptions'])
            : array();

        return new CoreObjectStateGroup(
            array(
                'id' => $data['_href'],
                'identifier' => $data['identifier'],
                'mainLanguageCode' => $data['defaultLanguageCode'],
                'languageCodes' => explode(',', $data['languageCodes']),
                'names' => $names,
                'descriptions' => $descriptions,
            )
        );
    }
}
