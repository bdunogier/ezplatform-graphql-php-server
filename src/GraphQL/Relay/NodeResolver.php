<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Relay;

use App\eZ\Platform\API\Repository\ContentService;
use App\eZ\Platform\API\Repository\ContentTypeService;
use App\eZ\Platform\API\Repository\Values\Content\ContentInfo;
use App\Schema\Domain\Content\NameHelper;
use Overblog\GraphQLBundle\Relay\Node\GlobalId;
use Overblog\GraphQLBundle\Resolver\TypeResolver;

class NodeResolver
{
    /**
     * @var ContentService
     */
    private $contentService;

    /**
     * @var TypeResolver
     */
    private $typeResolver;

    /**
     * @var ContentTypeService
     */
    private $contentTypeService;

    /**
     * @var NameHelper
     */
    private $nameHelper;

    public function __construct(ContentService $contentService, TypeResolver $typeResolver, ContentTypeService $contentTypeService, NameHelper $nameHelper)
    {
        $this->contentService = $contentService;
        $this->typeResolver = $typeResolver;
        $this->contentTypeService = $contentTypeService;
        $this->nameHelper = $nameHelper;
    }

    /**
     * @param $globalId
     *
     * @return null|\App\eZ\Platform\API\Repository\Values\Content\ContentInfo
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException
     */
    public function resolveNode($globalId)
    {
        $params = GlobalId::fromGlobalId($globalId);

        if (in_array($params['type'], ['Content', 'DomainContent'])) {
            return $this->contentService->loadContentInfo($params['id']);
        }

        return null;
    }

    /**
     * @param $object
     *
     * @return \GraphQL\Type\Definition\Type
     */
    public function resolveType($object)
    {
        if ($object instanceof ContentInfo) {
            return $this->typeResolver->resolve(
                $this->nameHelper->domainContentName(
                    $this->contentTypeService->loadContentType($object->contentTypeId)
                )
            );
        }
    }
}
