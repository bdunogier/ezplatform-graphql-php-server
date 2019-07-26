<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Resolver;

use App\eZ\Platform\API\Repository\Exceptions\NotFoundException;
use App\eZ\Platform\API\Repository\ObjectStateService;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;

/**
 * @internal
 */
class ObjectStateGroupResolver
{
    /** @var \App\eZ\Platform\API\Repository\ObjectStateService */
    private $objectStateService;

    /**
     * @param \App\eZ\Platform\API\Repository\ObjectStateService $objectStateService
     */
    public function __construct(ObjectStateService $objectStateService)
    {
        $this->objectStateService = $objectStateService;
    }

    /**
     * @param \Overblog\GraphQLBundle\Definition\Argument $args
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function resolveObjectStateGroupById(Argument $args): ObjectStateGroup
    {
        try {
            return $this->objectStateService->loadObjectStateGroup($args['id']);
        } catch (NotFoundException $e) {
            throw new UserError("Object State Group with ID: {$args['id']} was not found.");
        }
    }

    /**
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup[]
     */
    public function resolveObjectStateGroups(): array
    {
        return $this->objectStateService->loadObjectStateGroups();
    }
}
