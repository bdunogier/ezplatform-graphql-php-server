<?php

/**
 * File containing the ContentInfo parser class.
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
 * Parser for ContentInfo.
 */
class ContentInfo extends BaseParser
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
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentInfo
     *
     * @todo Error handling
     * @todo What about missing properties? Set them here, using the service to
     *       load? Or better set them in the service, since loading is really
     *       unsuitable here?
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $contentTypeId = $this->parserTools->parseObjectElement($data['ContentType'], $parsingDispatcher);
        $ownerId = $this->parserTools->parseObjectElement($data['Owner'], $parsingDispatcher);

        // the MainLocationId isn't set when the ContentInfo comes from a Location response
        $mainLocationId = isset($data['MainLocation'])
            ? $this->parserTools->parseObjectElement($data['MainLocation'], $parsingDispatcher)
            : null;

        $sectionId = $this->parserTools->parseObjectElement($data['Section'], $parsingDispatcher);

        $locationListReference = $this->parserTools->parseObjectElement($data['Locations'], $parsingDispatcher);
        $versionListReference = $this->parserTools->parseObjectElement($data['Versions'], $parsingDispatcher);
        $currentVersionReference = $this->parserTools->parseObjectElement($data['CurrentVersion'], $parsingDispatcher);

        if (isset($data['CurrentVersion']['Version'])) {
            $this->parserTools->parseObjectElement($data['CurrentVersion']['Version'], $parsingDispatcher);
        }

        return new Values\RestContentInfo(
            array(
                'id' => $data['_href'],
                'name' => $data['Name'],
                'contentTypeId' => $contentTypeId,
                'ownerId' => $ownerId,
                'modificationDate' => new \DateTime($data['lastModificationDate']),
                'publishedDate' => ($publishedDate = (!empty($data['publishedDate'])
                    ? new \DateTime($data['publishedDate'])
                    : null)),

                'published' => ($publishedDate !== null),
                'alwaysAvailable' => (strtolower($data['alwaysAvailable']) === 'true'),
                'remoteId' => $data['_remoteId'],
                'mainLanguageCode' => $data['mainLanguageCode'],
                'mainLocationId' => $mainLocationId,
                'sectionId' => $sectionId,
                'versionListReference' => $versionListReference,
                'locationListReference' => $locationListReference,
                'currentVersionReference' => $currentVersionReference,
            )
        );
    }
}
