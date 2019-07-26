<?php

/**
 * File containing the ContentTypeService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\API\Repository\ContentTypeService as APIContentTypeService;
use App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinitionUpdateStruct;
use App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition;
use App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinitionCreateStruct;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentType;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeDraft;
use App\eZ\Platform\API\Repository\Values\User\User;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeUpdateStruct;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeCreateStruct as APIContentTypeCreateStruct;
use App\eZ\Platform\Core\Repository\Values\ContentType\ContentTypeCreateStruct;
use App\eZ\Platform\Core\Repository\Values\ContentType\ContentType as RestContentType;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroup;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroupUpdateStruct;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroupCreateStruct;
use App\eZ\Platform\Core\Repository\Exceptions\NotFoundException;
use App\eZ\Platform\Core\Repository\RequestParser;
use App\eZ\Platform\Core\Repository\Input\Dispatcher;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use App\eZ\Platform\Core\Repository\Message; use App\eZ\Platform\Core\Repository\Exceptions\InvalidArgumentValue;
use App\eZ\Platform\Core\Repository\Exceptions\InvalidArgumentException;
use App\eZ\Platform\Core\Repository\Exceptions\ForbiddenException;
use App\eZ\Platform\Core\Repository\Exceptions\BadStateException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @example Examples/contenttype.php
 */
class ContentTypeService implements APIContentTypeService, Sessionable
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
    public function __construct(HttpClientInterface $ezpRestClient, Dispatcher $inputDispatcher, Visitor $outputVisitor, RequestParser $requestParser)
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
     * Create a Content Type Group object.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create a content type group
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If a group with the same identifier already exists
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroupCreateStruct $contentTypeGroupCreateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroup
     */
    public function createContentTypeGroup(ContentTypeGroupCreateStruct $contentTypeGroupCreateStruct)
    {
        $inputMessage = $this->outputVisitor->visit($contentTypeGroupCreateStruct);
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('ContentTypeGroup');

        $result = $this->client->request(
            'POST',
            $this->requestParser->generate('typegroups'),
            $inputMessage
        );

        try {
            return $this->inputDispatcher->parse($result);
        } catch (ForbiddenException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * {@inheritdoc}
     */
    public function loadContentTypeGroup($contentTypeGroupId, array $prioritizedLanguages = [])
    {
        $response = $this->client->request(
            'GET',
            $contentTypeGroupId,
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('Section')]]
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * {@inheritdoc}
     */
    public function loadContentTypeGroupByIdentifier($contentTypeGroupIdentifier, array $prioritizedLanguages = [])
    {
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('ezpublish_rest_loadContentTypeGroupList'),
            [
                'query' => ['typegroup' => $contentTypeGroupIdentifier],
                'headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentTypeGroupList')],
            ]
        );

        if ($response->getStatusCode() == 307) {
            $response = $this->client->request(
                'GET',
                $response->getHeaders()['Location'],
                ['headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentTypeGroup')]]
            );
        }

        return $this->inputDispatcher->parse($response);
    }

    /**
     * {@inheritdoc}
     */
    public function loadContentTypeGroups(array $prioritizedLanguages = [])
    {
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('ezpublish_rest_loadContentTypeGroupList'),
            ['headers' => ['accept' => $this->outputVisitor->getMediaType('ContentTypeGroupList')]]
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * Update a Content Type Group object.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create a content type group
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If the given identifier (if set) already exists
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroup $contentTypeGroup the content type group to be updated
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroupUpdateStruct $contentTypeGroupUpdateStruct
     */
    public function updateContentTypeGroup(ContentTypeGroup $contentTypeGroup, ContentTypeGroupUpdateStruct $contentTypeGroupUpdateStruct)
    {
        $inputMessage = $this->outputVisitor->visit($contentTypeGroupUpdateStruct);
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('ContentTypeGroup');
        $inputMessage->headers['X-HTTP-Method-Override'] = 'PATCH';

        // Should originally be PATCH, but PHP's shiny new internal web server
        // dies with it.
        $result = $this->client->request(
            'POST',
            $contentTypeGroup->id,
            $inputMessage
        );

        try {
            return $this->inputDispatcher->parse($result);
        } catch (ForbiddenException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Delete a Content Type Group.
     *
     * This method only deletes an content type group which has content types without any content instances
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to delete a content type group
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If  a to be deleted content type has instances
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroup
     */
    public function deleteContentTypeGroup(ContentTypeGroup $contentTypeGroup)
    {
        $response = $this->client->request(
            'DELETE',
            $contentTypeGroup->id,
            [
                'headers' =>
                    // @todo: What media-type should we set here? Actually, it should be
                    // all expected exceptions + none? Or is "Section" correct,
                    // since this is what is to be expected by the resource
                    // identified by the URL?
                    ['Accept' => $this->outputVisitor->getMediaType('ContentTypeGroup')]
            ]
        );

        if (!empty($response->body)) {
            try {
                return $this->inputDispatcher->parse($response);
            } catch (ForbiddenException $e) {
                throw new InvalidArgumentException($e->getMessage(), $e->getCode());
            }
        }
    }

    /**
     * Create a Content Type object.
     *
     * The content type is created in the state STATUS_DRAFT.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create a content type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException In case when
     *         - array of content type groups does not contain at least one content type group
     *         - identifier or remoteId in the content type create struct already exists
     *         - there is a duplicate field identifier in the content type create struct
     * @throws \App\eZ\Platform\API\Repository\Exceptions\ContentTypeFieldDefinitionValidationException
     *         if a field definition in the $contentTypeCreateStruct is not valid
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeCreateStruct $contentTypeCreateStruct
     * @param array $contentTypeGroups Required array of {@link ContentTypeGroup} to link type with (must contain one)
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeDraft
     */
    public function createContentType(APIContentTypeCreateStruct $contentTypeCreateStruct, array $contentTypeGroups)
    {
        $inputMessage = $this->outputVisitor->visit($contentTypeCreateStruct);
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('ContentType');

        if (empty($contentTypeGroups)) {
            throw new InvalidArgumentException(
                "Argument '\$contentTypeGroups' is invalid: Argument must contain at least one ContentTypeGroup"
            );
        }

        /** @var $firstGroup \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroup */
        /* @var $contentTypeGroups \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroup[] */
        $firstGroup = array_pop($contentTypeGroups);
        $response = $this->client->request(
            'POST',
            $this->requestParser->generate(
                'grouptypes',
                $this->requestParser->parse('typegroup', $firstGroup->id)
            ),
            $inputMessage
        );

        try {
            $contentType = $this->inputDispatcher->parse($response);
        } catch (ForbiddenException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode());
        }

        foreach ($contentTypeGroups as $contentTypeGroup) {
            $this->assignContentTypeGroup($contentType, $contentTypeGroup);
        }

        return $this->completeContentType($contentType);
    }

    /**
     * TODO: ContentTypeGroupList reference should really be already available in the ContentType returned from server, so this method can be removed.
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentType $contentType
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentType
     */
    protected function completeContentType(ContentType $contentType)
    {
        // TODO: currently no way to fetch groups of a type draft
        if ($contentType instanceof ContentTypeDraft) {
            return $contentType;
        }

        $response = $this->client->request(
            'GET',
            $this->requestParser->generate(
                'ezpublish_rest_loadGroupsOfContentType',
                $this->requestParser->parse($contentType->id)
            ),
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentTypeGroupRefList')]]
        );

        /** @var $referenceList \App\eZ\Platform\Core\Repository\Values\ContentTypeGroupRefList */
        $referenceList = $this->inputDispatcher->parse($response);

        return new RestContentType(
            $this,
            array(
                'id' => $contentType->id,
                'remoteId' => $contentType->remoteId,
                'identifier' => $contentType->identifier,
                'creatorId' => $contentType->creatorId,
                'modifierId' => $contentType->modifierId,
                'creationDate' => $contentType->creationDate,
                'modificationDate' => $contentType->modificationDate,
                'defaultSortField' => $contentType->defaultSortField,
                'defaultSortOrder' => $contentType->defaultSortOrder,
                'defaultAlwaysAvailable' => $contentType->defaultAlwaysAvailable,
                'names' => $contentType->names,
                'descriptions' => $contentType->descriptions,
                'isContainer' => $contentType->isContainer,
                'mainLanguageCode' => $contentType->mainLanguageCode,
                'nameSchema' => $contentType->nameSchema,
                'urlAliasSchema' => $contentType->urlAliasSchema,
                'status' => $contentType->status,

                'fieldDefinitionListReference' => $contentType->fieldDefinitionListReference,
                'contentTypeGroupListReference' => $referenceList->listReference,

                // dynamic
                //"fieldDefinitions" => $contentType->fieldDefinitions,
                //"contentTypeGroups" => $contentType->contentTypeGroups,
            )
        );
    }

    /**
     * Checks if the given response is an error.
     *
     * @param Message $response
     *
     * @return bool
     */
    protected function isErrorResponse(Message $response)
    {
        return (
            strpos($response->headers['Content-Type'], 'application/vnd.ez.api.ErrorMessage') === 0
        );
    }

    /**
     * Get a Content Type object draft by id.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException If the content type draft owned by the current user can not be found
     *
     * @param mixed $contentTypeId
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeDraft
     */
    public function loadContentTypeDraft($contentTypeId)
    {
        $response = $this->client->request(
            'GET',
            $contentTypeId,
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentType')]]
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * Update a Content Type object.
     *
     * Does not update fields (fieldDefinitions), use {@link updateFieldDefinition()} to update them.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to update a content type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If the given identifier or remoteId already exists or there is no draft assigned to the authenticated user
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeDraft $contentTypeDraft
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeUpdateStruct $contentTypeUpdateStruct
     */
    public function updateContentTypeDraft(ContentTypeDraft $contentTypeDraft, ContentTypeUpdateStruct $contentTypeUpdateStruct)
    {
        throw new \RuntimeException('@todo: Implement.');
    }

    /**
     * Adds a new field definition to an existing content type.
     *
     * The content type must be in state DRAFT.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the identifier in already exists in the content type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to edit a content type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\ContentTypeFieldDefinitionValidationException
     *         if a field definition in the $contentTypeCreateStruct is not valid
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException If field definition of the same non-repeatable type is being
     *                                                                 added to the ContentType that already contains one
     *                                                                 or field definition that can't be added to a ContentType that
     *                                                                 has Content instances is being added to such ContentType
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeDraft $contentTypeDraft
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinitionCreateStruct $fieldDefinitionCreateStruct
     */
    public function addFieldDefinition(ContentTypeDraft $contentTypeDraft, FieldDefinitionCreateStruct $fieldDefinitionCreateStruct)
    {
        throw new \RuntimeException('@todo: Implement.');
    }

    /**
     * Remove a field definition from an existing Type.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If the given field definition does not belong to the given type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to edit a content type
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeDraft $contentTypeDraft
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition $fieldDefinition
     */
    public function removeFieldDefinition(ContentTypeDraft $contentTypeDraft, FieldDefinition $fieldDefinition)
    {
        throw new \RuntimeException('@todo: Implement.');
    }

    /**
     * Update a field definition.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If the field id in the update struct is not found or does not belong to the content type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to edit a content type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException  If the given identifier is used in an existing field of the given content type
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeDraft $contentTypeDraft the content type draft
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition $fieldDefinition the field definition which should be updated
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinitionUpdateStruct $fieldDefinitionUpdateStruct
     */
    public function updateFieldDefinition(ContentTypeDraft $contentTypeDraft, FieldDefinition $fieldDefinition, FieldDefinitionUpdateStruct $fieldDefinitionUpdateStruct)
    {
        throw new \RuntimeException('@todo: Implement.');
    }

    /**
     * Publish the content type and update content objects.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException If the content type has no draft
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to publish a content type
     *
     * This method updates content objects, depending on the changed field definitions.
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeDraft $contentTypeDraft
     */
    public function publishContentTypeDraft(ContentTypeDraft $contentTypeDraft)
    {
        throw new \RuntimeException('@todo: Implement.');
    }

    /**
     * {@inheritdoc}
     */
    public function loadContentType($contentTypeId, array $prioritizedLanguages = [])
    {
        $response = $this->client->request(
            'GET',
            $contentTypeId,
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentType')]]
        );

        return $this->completeContentType($this->inputDispatcher->parse($response));
    }

    /**
     * Loads a single field definition by $fieldDefinitionId.
     *
     * ATTENTION: This is not an API method and only meant for internal use in
     * the REST Client implementation.
     *
     * @param string $fieldDefinitionId
     *
     * @return FieldDefinition
     */
    public function loadFieldDefinition($fieldDefinitionId)
    {
        $response = $this->client->request(
            'GET',
            $fieldDefinitionId,
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('FieldDefinition')]]
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * Loads the FieldDefinitionList stored at $fieldDefinitionListReference.
     *
     * ATTENTION: This is not an API method and only meant for internal use in
     * the REST Client implementation.
     *
     * @param mixed $fieldDefinitionListReference
     *
     * @return \App\eZ\Platform\Core\Repository\Values\FieldDefinitionList
     */
    public function loadFieldDefinitionList($fieldDefinitionListReference)
    {
        $response = $this->client->request(
            'GET',
            $fieldDefinitionListReference,
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('FieldDefinitionList')]]
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * Loads the ContentTypeGroupList stored at $contentTypeGroupListReference.
     *
     * ATTENTION: This is not an API method and only meant for internal use in
     * the REST Client implementation.
     *
     * @param mixed $contentTypeGroupListReference
     *
     * @return \App\eZ\Platform\Core\Repository\Values\ContentTypeGroupRefList
     */
    public function loadContentTypeGroupList($contentTypeGroupListReference)
    {
        $response = $this->client->request(
            'GET',
            $contentTypeGroupListReference,
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentTypeGroupRefList')]]
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * {@inheritdoc}
     */
    public function loadContentTypeByIdentifier($identifier, array $prioritizedLanguages = [])
    {
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('ezpublish_rest_listContentTypes'),
            [
                'headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentTypeList')],
                'query' => ['type' => $identifier],
            ]
        );
        $contentTypes = $this->inputDispatcher->parse($response);

        return $this->completeContentType(reset($contentTypes));
    }

    /**
     * {@inheritdoc}
     */
    public function loadContentTypeByRemoteId($remoteId, array $prioritizedLanguages = [])
    {
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('ezpublish_rest_listContentTypes'),
            [
                'headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentTypeList')],
                'query' => ['remoteId' => $remoteId],
            ]
        );
        $contentTypes = $this->inputDispatcher->parse($response);

        return reset($contentTypes);
    }

    /**
     * {@inheritdoc}
     */
    public function loadContentTypeList(array $contentTypeIds, array $prioritizedLanguages = []): iterable
    {
        throw new \RuntimeException('@todo: Implement.');
    }

    /**
     * {@inheritdoc}
     */
    public function loadContentTypes(ContentTypeGroup $contentTypeGroup, array $prioritizedLanguages = [])
    {
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate(
                'ezpublish_rest_listContentTypesForGroup',
                $this->requestParser->parse($contentTypeGroup->id)
            ),
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentTypeList')]]
        );
        $completedContentTypes = array();
        $contentTypes = $this->inputDispatcher->parse($response);
        foreach ($contentTypes as $contentType) {
            $completedContentTypes[] = $this->completeContentType($contentType);
        }

        return $completedContentTypes;
    }

    /**
     * Creates a draft from an existing content type.
     *
     * This is a complete copy of the content
     * type which has the state STATUS_DRAFT.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to edit a content type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException If there is already a draft assigned to another user
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentType $contentType
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeDraft
     */
    public function createContentTypeDraft(ContentType $contentType)
    {
        throw new \RuntimeException('@todo: Implement.');
    }

    /**
     * Delete a Content Type object.
     *
     * Deletes a content type if it has no instances
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException If there exist content objects of this type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to delete a content type
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentType $contentType
     */
    public function deleteContentType(ContentType $contentType)
    {
        throw new \RuntimeException('@todo: Implement.');
    }

    /**
     * Copy Type incl fields and groupIds to a new Type object.
     *
     * New Type will have $userId as creator / modifier, created / modified should be updated with current time,
     * updated remoteId and identifier should be appended with '_' + unique string.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to copy a content type
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentType $contentType
     * @param \App\eZ\Platform\API\Repository\Values\User\User $user if null the current user is used
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentType
     */
    public function copyContentType(ContentType $contentType, User $user = null)
    {
        throw new \RuntimeException('@todo: Implement.');
    }

    /**
     * Assigns a content type to a content type group.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to unlink a content type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If the content type is already assigned the given group
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentType $contentType
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroup $contentTypeGroup
     */
    public function assignContentTypeGroup(ContentType $contentType, ContentTypeGroup $contentTypeGroup)
    {
        if ($contentType instanceof ContentTypeDraft) {
            // @todo fix route
            $urlValues = $this->requestParser->parse('typeDraft', $contentType->id);
        } else {
            // @todo fix route
            $urlValues = $this->requestParser->parse('type', $contentType->id);
        }
        $urlValues['group'] = $contentTypeGroup->id;

        $response = $this->client->request(
            'POST',
            // @todo fix route
            $this->requestParser->generate('typeGroupAssign', $urlValues),
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentTypeGroupRefList')]]
        );

        if ($this->isErrorResponse($response)) {
            try {
                $this->inputDispatcher->parse($response);
            } catch (ForbiddenException $e) {
                throw new InvalidArgumentException($e->getMessage(), $e->getCode());
            }
        }
    }

    /**
     * Unassign a content type from a group.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to link a content type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If the content type is not assigned this the given group.
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException If $contentTypeGroup is the last group assigned to the content type
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentType $contentType
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroup $contentTypeGroup
     */
    public function unassignContentTypeGroup(ContentType $contentType, ContentTypeGroup $contentTypeGroup)
    {
        $urlValues = $this->requestParser->parse($contentType->id);
        $groupUrlValues = $this->requestParser->parse($contentTypeGroup->id);
        $urlValues['group'] = $groupUrlValues['typegroup'];

        $response = $this->client->request(
            'DELETE',
            // @todo fix route
            $this->requestParser->generate('groupOfType', $urlValues),
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentTypeGroupRefList')]]
        );

        if ($this->isErrorResponse($response)) {
            try {
                $this->inputDispatcher->parse($response);
            } catch (ForbiddenException $e) {
                throw new InvalidArgumentException($e->getMessage(), $e->getCode());
            } catch (NotFoundException $e) {
                throw new BadStateException($e->getMessage(), $e->getCode());
            }
        }
    }

    /**
     * Instantiates a new content type group create class.
     *
     * @param string $identifier
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroupCreateStruct
     */
    public function newContentTypeGroupCreateStruct($identifier)
    {
        if (!is_string($identifier)) {
            throw new InvalidArgumentValue('$identifier', $identifier);
        }

        return new ContentTypeGroupCreateStruct(
            array(
                'identifier' => $identifier,
            )
        );
    }

    /**
     * Instantiates a new content type create class.
     *
     * @param string $identifier
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeCreateStruct
     */
    public function newContentTypeCreateStruct($identifier)
    {
        if (!is_string($identifier)) {
            throw new InvalidArgumentValue('$identifier', $identifier);
        }

        return new ContentTypeCreateStruct(
            array(
                'identifier' => $identifier,
            )
        );
    }

    /**
     * Instantiates a new content type update struct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeUpdateStruct
     */
    public function newContentTypeUpdateStruct()
    {
        return new ContentTypeUpdateStruct();
    }

    /**
     * Instantiates a new content type update struct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroupUpdateStruct
     */
    public function newContentTypeGroupUpdateStruct()
    {
        return new ContentTypeGroupUpdateStruct();
    }

    /**
     * Instantiates a field definition create struct.
     *
     * @param string $fieldTypeIdentifier the required field type identifier
     * @param string $identifier the required identifier for the field definition
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinitionCreateStruct
     */
    public function newFieldDefinitionCreateStruct($identifier, $fieldTypeIdentifier)
    {
        if (!is_string($identifier)) {
            throw new InvalidArgumentValue('$identifier', $identifier);
        }

        if (!is_string($fieldTypeIdentifier)) {
            throw new InvalidArgumentValue('$fieldTypeIdentifier', $fieldTypeIdentifier);
        }

        return new FieldDefinitionCreateStruct(
            array(
                'identifier' => $identifier,
                'fieldTypeIdentifier' => $fieldTypeIdentifier,
            )
        );
    }

    /**
     * Instantiates a field definition update class.
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinitionUpdateStruct
     */
    public function newFieldDefinitionUpdateStruct()
    {
        return new FieldDefinitionUpdateStruct();
    }

    /**
     * Returns true if the given content type $contentType has content instances.
     *
     * @since 6.0.1
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentType $contentType
     *
     * @return bool
     */
    public function isContentTypeUsed(ContentType $contentType)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeDraft $contentTypeDraft
     * @param string $languageCodes
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeDraft
     */
    public function removeContentTypeTranslation(
        ContentTypeDraft $contentTypeDraft,
        string $languageCode
    ): ContentTypeDraft {
        throw new \Exception('@todo: Implement.');
    }
}
