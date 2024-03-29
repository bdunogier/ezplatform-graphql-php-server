<?php

/**
 * File containing the Relation parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\Core\Repository\Values;
use App\eZ\Platform\API\Repository\ContentService;

/**
 * Parser for Relation.
 */
class Relation extends BaseParser
{
    /**
     * Content Service.
     *
     * @var \App\eZ\Platform\Core\REST\Input\ContentService
     */
    protected $contentService;

    /**
     * @param \App\eZ\Platform\API\Repository\ContentService $contentService
     */
    public function __construct(ContentService $contentService)
    {
        $this->contentService = $contentService;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \App\eZ\Platform\API\Repository\Values\Relation\Version
     *
     * @todo Error handling
     * @todo Should the related ContentInfo structs really be loaded here or do
     *       we need lazy loading for this?
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        return new Values\Content\Relation(
            array(
                'id' => $data['_href'],
                'sourceContentInfo' => $this->contentService->loadContentInfo(
                    $data['SourceContent']['_href']
                ),
                'destinationContentInfo' => $this->contentService->loadContentInfo(
                    $data['DestinationContent']['_href']
                ),
                'type' => $this->convertRelationType($data['RelationType']),
                // @todo: Handle SourceFieldDefinitionIdentifier
            )
        );
    }

    /**
     * Converts the string representation of the relation type to its constant.
     *
     * @param string $stringType
     *
     * @return int
     */
    protected function convertRelationType($stringType)
    {
        $stringTypeList = explode(',', strtoupper($stringType));
        $relationType = 0;

        foreach ($stringTypeList as $stringTypeValue) {
            switch ($stringTypeValue) {
                case 'COMMON':
                    $relationType |= Values\Content\Relation::COMMON;
                    break;
                case 'EMBED':
                    $relationType |= Values\Content\Relation::EMBED;
                    break;
                case 'LINK':
                    $relationType |= Values\Content\Relation::LINK;
                    break;
                case 'FIELD':
                    $relationType |= Values\Content\Relation::FIELD;
                    break;
                case 'ASSET':
                    $relationType |= Values\Content\Relation::ASSET;
                    break;
                default:
                    throw new \RuntimeException(
                        sprintf('Unknown Relation type: "%s"', $stringTypeValue)
                    );
            }
        }

        return $relationType;
    }
}
