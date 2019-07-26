<?php

/**
 * File containing the ContentTypeGroup parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParserTools;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\Core\Repository\Values;

/**
 * Parser for ContentTypeGroup.
 */
class ContentTypeGroup extends BaseParser
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
     * @return \App\eZ\Platform\API\Repository\Values\Content\Section
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $creatorId = $this->parserTools->parseObjectElement($data['Creator'], $parsingDispatcher);
        $modifierId = $this->parserTools->parseObjectElement($data['Modifier'], $parsingDispatcher);

        return new Values\ContentType\ContentTypeGroup(
            array(
                'id' => $data['_href'],
                'identifier' => $data['identifier'],
                'creationDate' => new \DateTime($data['created']),
                'modificationDate' => new \DateTime($data['modified']),
                'creatorId' => $creatorId,
                'modifierId' => $modifierId,
            )
        );
    }
}
