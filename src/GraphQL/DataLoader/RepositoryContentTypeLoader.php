<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\DataLoader;

use App\eZ\Platform\API\Repository\ContentTypeService;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentType;

/**
 * @internal
 */
class RepositoryContentTypeLoader implements ContentTypeLoader
{
    /**
     * @var ContentTypeService
     */
    private $contentTypeService;

    public function __construct(ContentTypeService $contentTypeService)
    {
        $this->contentTypeService = $contentTypeService;
    }

    public function load($contentTypeId): ContentType
    {
        return $this->contentTypeService->loadContentType($contentTypeId);
    }

    public function loadByIdentifier($identifier): ContentType
    {
        return $this->contentTypeService->loadContentTypeByIdentifier($identifier);
    }
}
