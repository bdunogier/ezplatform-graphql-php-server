<?php

/**
 * File containing the VersionInfo parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\API\Repository\ContentTypeService;
use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\Core\Repository\Input\ParserTools;
use App\eZ\Platform\API\Repository\ContentService;
use eZ\Publish\Core\REST\Server\Values\Version as VersionValue;

/**
 * Parser for VersionInfo.
 */
class Version extends BaseParser
{
    /** @var \App\eZ\Platform\Core\Repository\Input\ParserTools */
    protected $parserTools;

    /**
     * Content Service.
     *
     * @var \App\eZ\Platform\Core\Repository\ContentService
     */
    protected $contentService;

    /** @var \App\eZ\Platform\API\Repository\ContentTypeService */
    private $contentTypeService;

    /**
     * @param \App\eZ\Platform\Core\Repository\Input\ParserTools $parserTools
     * @param \App\eZ\Platform\API\Repository\ContentService $contentService
     * @param \App\eZ\Platform\API\Repository\ContentTypeService $contentTypeService
     */
    public function __construct(ParserTools $parserTools, ContentService $contentService, ContentTypeService $contentTypeService)
    {
        $this->parserTools = $parserTools;
        $this->contentService = $contentService;
        $this->contentTypeService = $contentTypeService;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \eZ\Publish\Core\REST\Server\Values\Version
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $contentId = $this->requestParser->parseHref($data['VersionInfo']['Content']['_href'], 'contentId');

        $content = $this->contentService->loadContent($contentId, null, $data['VersionInfo']['versionNo']);
        $contentType = $this->contentTypeService->loadContentType($content->contentInfo->contentTypeId);
        $relations = $this->contentService->loadRelations($content->versionInfo);

        return new VersionValue($content, $contentType, $relations);
    }
}
