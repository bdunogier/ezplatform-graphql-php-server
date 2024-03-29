<?php

/**
 * File containing the eZ\Platform\API\Repository\ObjectStateService interface.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository;

use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateUpdateStruct;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateCreateStruct;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupUpdateStruct;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup;
use App\eZ\Platform\API\Repository\Values\Content\ContentInfo;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupCreateStruct;

/**
 * ObjectStateService service.
 *
 * @example Examples/objectstates.php tbd.
 */
interface ObjectStateService
{
    /**
     * Creates a new object state group.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create an object state group
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the object state group with provided identifier already exists
     *
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupCreateStruct $objectStateGroupCreateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function createObjectStateGroup(ObjectStateGroupCreateStruct $objectStateGroupCreateStruct);

    /**
     * Loads a object state group.
     *
     * @param mixed $objectStateGroupId
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if the group was not found
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function loadObjectStateGroup($objectStateGroupId, array $prioritizedLanguages = []);

    /**
     * Loads all object state groups.
     *
     * @param int $offset
     * @param int $limit
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup[]
     */
    public function loadObjectStateGroups($offset = 0, $limit = -1, array $prioritizedLanguages = []);

    /**
     * This method returns the ordered list of object states of a group.
     *
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState[]
     */
    public function loadObjectStates(ObjectStateGroup $objectStateGroup, array $prioritizedLanguages = []);

    /**
     * Updates an object state group.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to update an object state group
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the object state group with provided identifier already exists
     *
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupUpdateStruct $objectStateGroupUpdateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function updateObjectStateGroup(ObjectStateGroup $objectStateGroup, ObjectStateGroupUpdateStruct $objectStateGroupUpdateStruct);

    /**
     * Deletes a object state group including all states and links to content.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to delete an object state group
     *
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     */
    public function deleteObjectStateGroup(ObjectStateGroup $objectStateGroup);

    /**
     * Creates a new object state in the given group.
     *
     * Note: in current kernel: If it is the first state all content objects will
     * set to this state.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create an object state
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the object state with provided identifier already exists in the same group
     *
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectStateCreateStruct $objectStateCreateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState
     */
    public function createObjectState(ObjectStateGroup $objectStateGroup, ObjectStateCreateStruct $objectStateCreateStruct);

    /**
     * Loads an object state.
     *
     * @param mixed $stateId
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if the state was not found
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState
     */
    public function loadObjectState($stateId, array $prioritizedLanguages = []);

    /**
     * Updates an object state.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to update an object state
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the object state with provided identifier already exists in the same group
     *
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectState $objectState
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectStateUpdateStruct $objectStateUpdateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState
     */
    public function updateObjectState(ObjectState $objectState, ObjectStateUpdateStruct $objectStateUpdateStruct);

    /**
     * Changes the priority of the state.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to change priority on an object state
     *
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectState $objectState
     * @param int $priority
     */
    public function setPriorityOfObjectState(ObjectState $objectState, $priority);

    /**
     * Deletes a object state. The state of the content objects is reset to the
     * first object state in the group.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to delete an object state
     *
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectState $objectState
     */
    public function deleteObjectState(ObjectState $objectState);

    /**
     * Sets the object-state of a state group to $state for the given content.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the object state does not belong to the given group
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to change the object state
     *
     * @param \eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectState $objectState
     */
    public function setContentState(ContentInfo $contentInfo, ObjectStateGroup $objectStateGroup, ObjectState $objectState);

    /**
     * Gets the object-state of object identified by $contentId.
     *
     * The $state is the id of the state within one group.
     *
     * @param \eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState
     */
    public function getContentState(ContentInfo $contentInfo, ObjectStateGroup $objectStateGroup);

    /**
     * Returns the number of objects which are in this state.
     *
     * @param \eZ\Platform\API\Repository\Values\ObjectState\ObjectState $objectState
     *
     * @return int
     */
    public function getContentCount(ObjectState $objectState);

    /**
     * Instantiates a new Object State Group Create Struct and sets $identified in it.
     *
     * @param string $identifier
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupCreateStruct
     */
    public function newObjectStateGroupCreateStruct($identifier);

    /**
     * Instantiates a new Object State Group Update Struct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupUpdateStruct
     */
    public function newObjectStateGroupUpdateStruct();

    /**
     * Instantiates a new Object State Create Struct and sets $identifier in it.
     *
     * @param string $identifier
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateCreateStruct
     */
    public function newObjectStateCreateStruct($identifier);

    /**
     * Instantiates a new Object State Update Struct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateUpdateStruct
     */
    public function newObjectStateUpdateStruct();
}
