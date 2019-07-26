<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Resolver;

use App\eZ\Platform\API\Repository\Exceptions\NotFoundException;
use App\eZ\Platform\API\Repository\ObjectStateService;
use App\eZ\Platform\API\Repository\Values\Content\ContentInfo;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup;
use GraphQL\Error\UserError;
use Overblog\GraphQLBundle\Definition\Argument;

/**
 * @internal
 */
class ObjectStateResolver
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
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState
     */
    public function resolveObjectStateById(Argument $args): ObjectState
    {
        try {
            return $this->objectStateService->loadObjectState($args['id']);
        } catch (NotFoundException $e) {
            throw new UserError("Object State with ID: {$args['id']} was not found.");
        }
    }

    /**
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState[]
     */
    public function resolveObjectStatesByGroup(ObjectStateGroup $objectStateGroup): array
    {
        return $this->objectStateService->loadObjectStates($objectStateGroup);
    }

    /**
     * @param \Overblog\GraphQLBundle\Definition\Argument $args
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState[]
     */
    public function resolveObjectStatesByGroupId(Argument $args): array
    {
        try {
            $group = $this->objectStateService->loadObjectStateGroup($args['groupId']);
        } catch (NotFoundException $e) {
            throw new UserError("Object State Group with ID: {$args['groupId']} was not found.");
        }

        return $this->objectStateService->loadObjectStates($group);
    }

    /**
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState[]
     */
    public function resolveObjectStateByContentInfo(ContentInfo $contentInfo): array
    {
        $objectStates = [];
        foreach ($this->objectStateService->loadObjectStateGroups() as $group) {
            $objectStates[] = $this->objectStateService->getContentState($contentInfo, $group);
        }

        return $objectStates;
    }
}
