<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Resolver;

use App\eZ\Platform\API\Repository\ContentService;
use App\eZ\Platform\API\Repository\LocationService;

/**
 * @internal
 */
class LocationResolver
{
    /**
     * @var \App\eZ\Platform\API\Repository\LocationService
     */
    private $locationService;

    /**
     * @var ContentService
     */
    private $contentService;

    public function __construct(LocationService $locationService, ContentService $contentService)
    {
        $this->locationService = $locationService;
        $this->contentService = $contentService;
    }

    public function resolveLocation($args)
    {
        if (isset($args['id'])) {
            return $this->locationService->loadLocation($args['id']);
        }
    }

    public function resolveLocationsByContentId($contentId)
    {
        return $this->locationService->loadLocations(
            $this->contentService->loadContentInfo($contentId)
        );
    }

    public function resolveLocationById($locationId)
    {
        return $this->locationService->loadLocation($locationId);
    }

    public function resolveLocationChildren($args)
    {
        if (isset($args['id'])) {
            return $this->locationService->loadLocationChildren(
                $this->locationService->loadLocation($args['id'])
            )->locations;
        }
    }
}
