<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Resolver;

use App\eZ\Platform\API\Repository\URLAliasService;
use App\eZ\Platform\API\Repository\Values\Content\Location;
use App\eZ\Platform\API\Repository\Values\Content\URLAlias;
use Overblog\GraphQLBundle\Resolver\TypeResolver;

/**
 * @internal
 */
class UrlAliasResolver
{
    /**
     * @var \App\eZ\Platform\API\Repository\URLAliasService
     */
    private $urlAliasService;

    /**
     * @var TypeResolver
     */
    private $typeResolver;

    public function __construct(TypeResolver $typeResolver, URLAliasService $urlAliasService)
    {
        $this->urlAliasService = $urlAliasService;
        $this->typeResolver = $typeResolver;
    }

    public function resolveLocationUrlAliases(Location $location, $args)
    {
        return $this->urlAliasService->listLocationAliases(
            $location,
            isset($args['custom']) ? $args['custom'] : false
        );
    }

    public function resolveUrlAliasType(URLAlias $urlAlias)
    {
        switch ($urlAlias->type) {
            case URLAlias::LOCATION:
                return $this->typeResolver->resolve('LocationUrlAlias');
            case URLAlias::RESOURCE:
                return $this->typeResolver->resolve('ResourceUrlAlias');
            case URLAlias::VIRTUAL:
                return $this->typeResolver->resolve('VirtualUrlAlias');
        }
    }
}
