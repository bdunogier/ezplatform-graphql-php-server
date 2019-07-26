<?php

/**
 * File containing the ObjectStateService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\Core\Repository\RequestParser;
use App\eZ\Platform\Core\Repository\Input\Dispatcher;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use App\eZ\Platform\Core\Repository\Message;
use App\eZ\Platform\API\Repository\ObjectStateService as APIObjectStateService;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateUpdateStruct;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateCreateStruct;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupUpdateStruct;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup;
use App\eZ\Platform\API\Repository\Values\Content\ContentInfo;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState;
use App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupCreateStruct;
use App\eZ\Platform\Core\Repository\Values\ContentObjectStates;

/**
 * ObjectStateService service.
 */
class ObjectStateService implements APIObjectStateService, Sessionable
{
    /** @var \App\eZ\Platform\Core\Repository\HttpClient */
    private $client;

    /** @var \App\eZ\Platform\Core\Repository\Input\Dispatcher */
    private $inputDispatcher;

    /** @var \App\eZ\Platform\Core\Repository\Output\Visitor */
    private $outputVisitor;

    /** @var \App\eZ\Platform\Core\Repository\RequestParser */
    private $requestParser;

    /**
     * @param \App\eZ\Platform\Core\Repository\\Symfony\Contracts\HttpClient\HttpClientInterface $ezpRestClient
     * @param \App\eZ\Platform\Core\Repository\Input\Dispatcher $inputDispatcher
     * @param \App\eZ\Platform\Core\Repository\Output\Visitor $outputVisitor
     * @param \App\eZ\Platform\Core\Repository\RequestParser $requestParser
     */
    public function __construct(\Symfony\Contracts\HttpClient\HttpClientInterface $ezpRestClient, Dispatcher $inputDispatcher, Visitor $outputVisitor, RequestParser $requestParser)
    {
        $this->client = $ezpRestClient;
        $this->inputDispatcher = $inputDispatcher;
        $this->outputVisitor = $outputVisitor;
        $this->requestParser = $requestParser;
    }

    /**
     * Set session ID.
     *
     * Only for testing
     *
     * @param mixed $id
     *
     * @private
     */
    public function setSession($id)
    {
        if ($this->outputVisitor instanceof Sessionable) {
            $this->outputVisitor->setSession($id);
        }
    }

    /**
     * Creates a new object state group.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create an object state group
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the object state group with provided identifier already exists
     *
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupCreateStruct $objectStateGroupCreateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function createObjectStateGroup(ObjectStateGroupCreateStruct $objectStateGroupCreateStruct)
    {
        $inputMessage = $this->outputVisitor->visit($objectStateGroupCreateStruct);
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('ObjectStateGroup');

        $result = $this->client->request(
            'POST',
            $this->requestParser->generate('objectstategroups'),
            $inputMessage
        );

        return $this->inputDispatcher->parse($result);
    }

    /**
     * {@inheritdoc}
     */
    public function loadObjectStateGroup($objectStateGroupId, array $prioritizedLanguages = [])
    {
        $response = $this->client->request(
            'GET',
            $objectStateGroupId,
            new Message(
                array('Accept' => $this->outputVisitor->getMediaType('ObjectStateGroup'))
            )
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * {@inheritdoc}
     */
    public function loadObjectStateGroups($offset = 0, $limit = -1, array $prioritizedLanguages = [])
    {
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('objectstategroups'),
            new Message(
                array('Accept' => $this->outputVisitor->getMediaType('ObjectStateGroupList'))
            )
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * {@inheritdoc}
     */
    public function loadObjectStates(ObjectStateGroup $objectStateGroup, array $prioritizedLanguages = [])
    {
        $values = $this->requestParser->parse('objectstategroup', $objectStateGroup->id);
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('objectstates', array('objectstategroup' => $values['objectstategroup'])),
            new Message(
                array('Accept' => $this->outputVisitor->getMediaType('ObjectStateList'))
            )
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * Updates an object state group.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to update an object state group
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the object state group with provided identifier already exists
     *
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupUpdateStruct $objectStateGroupUpdateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup
     */
    public function updateObjectStateGroup(ObjectStateGroup $objectStateGroup, ObjectStateGroupUpdateStruct $objectStateGroupUpdateStruct)
    {
        $inputMessage = $this->outputVisitor->visit($objectStateGroupUpdateStruct);
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('ObjectStateGroup');
        $inputMessage->headers['X-HTTP-Method-Override'] = 'PATCH';

        // Should originally be PATCH, but PHP's shiny new internal web server
        // dies with it.
        $result = $this->client->request(
            'POST',
            $objectStateGroup->id,
            $inputMessage
        );

        return $this->inputDispatcher->parse($result);
    }

    /**
     * Deletes a object state group including all states and links to content.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to delete an object state group
     *
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     */
    public function deleteObjectStateGroup(ObjectStateGroup $objectStateGroup)
    {
        $response = $this->client->request(
            'DELETE',
            $objectStateGroup->id,
            new Message(
                // @todo: What media-type should we set here? Actually, it should be
                // all expected exceptions + none? Or is "ObjectStateGroup" correct,
                // since this is what is to be expected by the resource
                // identified by the URL?
                array('Accept' => $this->outputVisitor->getMediaType('ObjectStateGroup'))
            )
        );

        if (!empty($response->body)) {
            $this->inputDispatcher->parse($response);
        }
    }

    /**
     * Creates a new object state in the given group.
     *
     * Note: in current kernel: If it is the first state all content objects will
     * set to this state.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create an object state
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the object state with provided identifier already exists in the same group
     *
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateCreateStruct $objectStateCreateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState
     */
    public function createObjectState(ObjectStateGroup $objectStateGroup, ObjectStateCreateStruct $objectStateCreateStruct)
    {
        $inputMessage = $this->outputVisitor->visit($objectStateCreateStruct);
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('ObjectState');

        $result = $this->client->request(
            'POST',
            $this->requestParser->generate('objectstates', array('objectstategroup' => $objectStateGroup->id)),
            $inputMessage
        );

        return $this->inputDispatcher->parse($result);
    }

    /**
     * {@inheritdoc}
     */
    public function loadObjectState($stateId, array $prioritizedLanguages = [])
    {
        $response = $this->client->request(
            'GET',
            $stateId,
            new Message(
                array('Accept' => $this->outputVisitor->getMediaType('ObjectState'))
            )
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * Updates an object state.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to update an object state
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the object state with provided identifier already exists in the same group
     *
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState $objectState
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateUpdateStruct $objectStateUpdateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState
     */
    public function updateObjectState(ObjectState $objectState, ObjectStateUpdateStruct $objectStateUpdateStruct)
    {
        $inputMessage = $this->outputVisitor->visit($objectStateUpdateStruct);
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('ObjectState');
        $inputMessage->headers['X-HTTP-Method-Override'] = 'PATCH';

        // Should originally be PATCH, but PHP's shiny new internal web server
        // dies with it.
        $result = $this->client->request(
            'POST',
            $objectState->id,
            $inputMessage
        );

        return $this->inputDispatcher->parse($result);
    }

    /**
     * Changes the priority of the state.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to change priority on an object state
     *
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState $objectState
     * @param int $priority
     */
    public function setPriorityOfObjectState(ObjectState $objectState, $priority)
    {
        throw new \Exception('@todo Implement');
    }

    /**
     * Deletes a object state. The state of the content objects is reset to the
     * first object state in the group.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to delete an object state
     *
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState $objectState
     */
    public function deleteObjectState(ObjectState $objectState)
    {
        $response = $this->client->request(
            'DELETE',
            $objectState->id,
            new Message(
                // @todo: What media-type should we set here? Actually, it should be
                // all expected exceptions + none? Or is "ObjectState" correct,
                // since this is what is to be expected by the resource
                // identified by the URL?
                array('Accept' => $this->outputVisitor->getMediaType('ObjectState'))
            )
        );

        if (!empty($response->body)) {
            $this->inputDispatcher->parse($response);
        }
    }

    /**
     * Sets the object-state of a state group to $state for the given content.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the object state does not belong to the given group
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to change the object state
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState $objectState
     */
    public function setContentState(ContentInfo $contentInfo, ObjectStateGroup $objectStateGroup, ObjectState $objectState)
    {
        $inputMessage = $this->outputVisitor->visit(new ContentObjectStates(array($objectState)));
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('ContentObjectStates');
        $inputMessage->headers['X-HTTP-Method-Override'] = 'PATCH';

        // Should originally be PATCH, but PHP's shiny new internal web server
        // dies with it.
        $values = $this->requestParser->parse('object', $contentInfo->id);
        $result = $this->client->request(
            'POST',
            $this->requestParser->generate('objectObjectStates', array('object' => $values['object'])),
            $inputMessage
        );

        $this->inputDispatcher->parse($result);
    }

    /**
     * Gets the object-state of object identified by $contentId.
     *
     * The $state is the id of the state within one group.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroup $objectStateGroup
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState
     */
    public function getContentState(ContentInfo $contentInfo, ObjectStateGroup $objectStateGroup)
    {
        $values = $this->requestParser->parse('object', $contentInfo->id);
        $groupValues = $this->requestParser->parse('objectstategroup', $objectStateGroup->id);
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('objectObjectStates', array('object' => $values['object'])),
            new Message(
                array('Accept' => $this->outputVisitor->getMediaType('ContentObjectStates'))
            )
        );

        $objectStates = $this->inputDispatcher->parse($response);
        foreach ($objectStates as $state) {
            $stateValues = $this->requestParser->parse('objectstate', $state->id);
            if ($stateValues['objectstategroup'] == $groupValues['objectstategroup']) {
                return $state;
            }
        }
    }

    /**
     * Returns the number of objects which are in this state.
     *
     * @param \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectState $objectState
     *
     * @return int
     */
    public function getContentCount(ObjectState $objectState)
    {
        throw new \Exception('@todo Implement');
    }

    /**
     * Instantiates a new Object State Group Create Struct and sets $identified in it.
     *
     * @param string $identifier
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupCreateStruct
     */
    public function newObjectStateGroupCreateStruct($identifier)
    {
        return new ObjectStateGroupCreateStruct(
            array(
                'identifier' => $identifier,
            )
        );
    }

    /**
     * Instantiates a new Object State Group Update Struct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateGroupUpdateStruct
     */
    public function newObjectStateGroupUpdateStruct()
    {
        return new ObjectStateGroupUpdateStruct();
    }

    /**
     * Instantiates a new Object State Create Struct and sets $identifier in it.
     *
     * @param string $identifier
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateCreateStruct
     */
    public function newObjectStateCreateStruct($identifier)
    {
        return new ObjectStateCreateStruct(
            array(
                'identifier' => $identifier,
            )
        );
    }

    /**
     * Instantiates a new Object State Update Struct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\ObjectState\ObjectStateUpdateStruct
     */
    public function newObjectStateUpdateStruct()
    {
        return new ObjectStateUpdateStruct();
    }
}
