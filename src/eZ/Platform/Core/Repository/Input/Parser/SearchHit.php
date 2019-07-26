<?php

/**
 * File containing the ViewInput parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\Core\Repository\Exceptions;
use App\eZ\Platform\API\Repository\Values\Content\Query;
use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\API\Repository\Values\Content\Search\SearchHit as SearchHitValue;

/**
 * Parser for SearchHit.
 */
class SearchHit extends BaseParser
{
    /**
     * Parses input structure to a RestViewInput struct.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @throws \App\eZ\Platform\Core\Repository\Exceptions\Parser
     *
     * @return \eZ\Publish\Core\REST\Server\Values\RestViewInput
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $valueData = [];

        if (!array_key_exists('value', $data) || !is_array($data['value'])) {
            throw new Exceptions\Parser("Missing or invalid data property in SearchHit");
        }
        $value = $data['value'];

        if (array_key_exists('Content', $value)) {
            $valueData['valueObject'] = $parsingDispatcher->parse($value['Content'], $value['Content']['_media-type']);
        }

        if (array_key_exists('Location', $value)) {
            $valueData['valueObject'] = $parsingDispatcher->parse($value['Location'], $value['Location']['_media-type']);
        }

        $valueData['score'] = $data['_score'];
        $valueData['index'] = $data['_index'];

        return new SearchHitValue($valueData);
    }
}
