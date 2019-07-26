<?php

/**
 * File containing the ContentService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\API\Repository\ContentService as APIContentService;
use App\eZ\Platform\API\Repository\ContentTypeService as APIContentTypeService;
use App\eZ\Platform\API\Repository\Values\Content\ContentInfo;
use App\eZ\Platform\API\Repository\Values\Content\ContentCreateStruct;
use App\eZ\Platform\API\Repository\Values\Content\ContentUpdateStruct;
use App\eZ\Platform\API\Repository\Values\Content\ContentMetadataUpdateStruct;
use App\eZ\Platform\API\Repository\Values\Content\Language;
use App\eZ\Platform\API\Repository\Values\Content\LocationCreateStruct;
use App\eZ\Platform\API\Repository\Values\Content\TranslationInfo;
use App\eZ\Platform\API\Repository\Values\Content\VersionInfo;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentType;
use App\eZ\Platform\API\Repository\Values\Content\Query;
use App\eZ\Platform\API\Repository\Values\User\User;
use App\eZ\Platform\Core\Repository\Input\Dispatcher;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * @example Examples/contenttype.php
 */
class ContentService implements APIContentService, Sessionable
{
    /** @var \Symfony\Contracts\HttpClient\HttpClientInterface */
    private $client;

    /** @var \App\eZ\Platform\Core\Repository\Input\Dispatcher */
    private $inputDispatcher;

    /** @var \App\eZ\Platform\Core\Repository\Output\Visitor */
    private $outputVisitor;

    /** @var \App\eZ\Platform\Core\Repository\RequestParser */
    private $requestParser;

    /** @var \App\eZ\Platform\Core\Repository\ContentTypeService */
    private $contentTypeService;

    /**
     * @param \App\eZ\Platform\Core\Repository\\Symfony\Contracts\HttpClient\HttpClientInterface $ezpRestClient
     * @param \App\eZ\Platform\Core\Repository\Input\Dispatcher $inputDispatcher
     * @param \App\eZ\Platform\Core\Repository\Output\Visitor $outputVisitor
     * @param \App\eZ\Platform\Core\Repository\RequestParser $requestParser
     * @param \App\eZ\Platform\Core\Repository\ContentTypeService $contentTypeService
     */
    public function __construct(HttpClientInterface $ezpRestClient, Dispatcher $inputDispatcher, Visitor $outputVisitor, RequestParser $requestParser, ApiContentTypeService $contentTypeService)
    {
        $this->client = $ezpRestClient;
        $this->inputDispatcher = $inputDispatcher;
        $this->outputVisitor = $outputVisitor;
        $this->requestParser = $requestParser;
        $this->contentTypeService = $contentTypeService;
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
     * Loads a content info object.
     *
     * To load fields use loadContent
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to read the content
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException - if the content with the given id does not exist
     *
     * @param int $contentId
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentInfo
     */
    public function loadContentInfo($contentId)
    {
        if (is_numeric($contentId)) {
            $contentId = "/api/ezp/v2/content/objects/$contentId";
        }
        $response = $this->client->request(
            'GET',
            $contentId,
            [
                'headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentInfo')]
            ]
        );

        $restContentInfo = $this->inputDispatcher->parse($response);

        return $this->completeContentInfo($restContentInfo);
    }

    /**
     * {@inheritdoc}
     */
    public function loadContentInfoList(array $contentIds): iterable
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Loads a content info object for the given remoteId.
     *
     * To load fields use loadContent
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create the content in the given location
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException - if the content with the given remote id does not exist
     *
     * @param string $remoteId
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentInfo
     */
    public function loadContentInfoByRemoteId($remoteId)
    {
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('ezpublish_rest_redirectContent'),
            [
                'query' => ['remoteId' => $remoteId],
                'headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentInfo')]
            ]
        );

        if ($response->statusCode == 307) {
            $locationHeader = $response->getHeaders()['location'][0];
            $response = $this->client->request(
                'GET',
                $locationHeader,
                ['headers' => ['Accept' => $this->outputVisitor->getMediaType('ContentInfo')]]
            );
        }

        $restContentInfo = $this->inputDispatcher->parse($response);

        return $this->completeContentInfo($restContentInfo);
    }

    /**
     * Returns a complete ContentInfo based on $restContentInfo.
     *
     * @param \App\eZ\Platform\Core\Repository\Values\RestContentInfo $restContentInfo
     *
     * @return \App\eZ\Platform\Core\Repository\Values\Content\ContentInfo
     */
    protected function completeContentInfo(Values\RestContentInfo $restContentInfo)
    {
        $versionUrlValues = $this->requestParser->parse(
            $this->fetchCurrentVersionUrl($restContentInfo->currentVersionReference)
        );

        return new Values\Content\ContentInfo(
            $this->contentTypeService,
            array(
                'id' => $restContentInfo->id,
                'name' => $restContentInfo->name,
                'contentTypeId' => $restContentInfo->contentTypeId,
                'ownerId' => $restContentInfo->ownerId,
                'modificationDate' => $restContentInfo->modificationDate,
                'publishedDate' => $restContentInfo->publishedDate,
                'published' => $restContentInfo->published,
                'alwaysAvailable' => $restContentInfo->alwaysAvailable,
                'remoteId' => $restContentInfo->remoteId,
                'mainLanguageCode' => $restContentInfo->mainLanguageCode,
                'mainLocationId' => $restContentInfo->mainLocationId,
                'sectionId' => $restContentInfo->sectionId,

                'currentVersionNo' => $versionUrlValues['versionNumber'],
            )
        );
    }

    /**
     * Returns the URL of the current version referenced by
     * $currentVersionReference.
     *
     * @param string $currentVersionReference
     *
     * @return string
     */
    protected function fetchCurrentVersionUrl($currentVersionReference)
    {
        $versionResponse = $this->client->request(
            'GET',
            $currentVersionReference
        );

        if ($this->isErrorResponse($versionResponse)) {
            return $this->inputDispatcher->parse($versionResponse);
        }

        $headers = $versionResponse->getHeaders(false);
        return $headers['location'][0];
    }

    /**
     * Checks if the given response is an error.
     *
     * @param Message $response
     *
     * @return bool
     */
    protected function isErrorResponse(ResponseInterface $response)
    {
        $headers = $response->getHeaders(false);
        return strpos($headers['content-type'][0], 'application/vnd.ez.api.ErrorMessage') === 0;
    }

    /**
     * Loads a version info of the given content object.
     *
     * If no version number is given, the method returns the current version
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException - if the version with the given number does not exist
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to load this version
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param int $versionNo the version number. If not given the current version is returned.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\VersionInfo
     */
    public function loadVersionInfo(ContentInfo $contentInfo, $versionNo = null)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Loads a version info of the given content object id.
     *
     * If no version number is given, the method returns the current version
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException - if the version with the given number does not exist
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to load this version
     *
     * @param mixed $contentId
     * @param int $versionNo the version number. If not given the current version is returned.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\VersionInfo
     */
    public function loadVersionInfoById($contentId, $versionNo = null)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Loads content in a version for the given content info object.
     *
     * If no version number is given, the method returns the current version
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException - if version with the given number does not exist
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to load this version
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param array $languages A language filter for fields. If not given all languages are returned
     * @param int $versionNo the version number. If not given the current version is returned
     * @param bool $useAlwaysAvailable Add Main language to \$languages if true (default) and if alwaysAvailable is true
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content
     */
    public function loadContentByContentInfo(ContentInfo $contentInfo, array $languages = null, $versionNo = null, $useAlwaysAvailable = true)
    {
        return $this->loadContent(
            $contentInfo->id,
            $languages,
            $versionNo
        );
    }

    /**
     * Loads content in the version given by version info.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to load this version
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\VersionInfo $versionInfo
     * @param array $languages A language filter for fields. If not given all languages are returned
     * @param bool $useAlwaysAvailable Add Main language to \$languages if true (default) and if alwaysAvailable is true
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content
     */
    public function loadContentByVersionInfo(VersionInfo $versionInfo, array $languages = null, $useAlwaysAvailable = true)
    {
        $contentInfo = $versionInfo->getContentInfo();

        return $this->loadContent($contentInfo->id, $languages, $versionInfo->versionNo);
    }

    /**
     * Loads content in a version of the given content object.
     *
     * If no version number is given, the method returns the current version
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException - if the content or version with the given id does not exist
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to load this version
     *
     * @param int $contentId
     * @param array $languages A language filter for fields. If not given all languages are returned
     * @param int $versionNo the version number. If not given the current version is returned
     * @param bool $useAlwaysAvailable Add Main language to \$languages if true (default) and if alwaysAvailable is true
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content
     *
     * @todo Handle $versionNo = null
     * @todo Handle language filters
     */
    public function loadContent($contentId, array $languages = null, $versionNo = null, $useAlwaysAvailable = true)
    {
        // $contentId should already be a URL!
        $contentIdValues = $this->requestParser->parse($contentId);

        $url = '';
        if ($versionNo === null) {
            $url = $this->fetchCurrentVersionUrl(
                $this->requestParser->generate(
                    'ezpublish_rest_redirectCurrentVersion',
                    ['contentId' => $contentIdValues['object']]
                )
            );
        } else {
            $url = $this->requestParser->generate(
                'ezpublish_rest_loadContentInVersion',
                ['contentId' => $contentIdValues['object'], 'versionNumber' => $versionNo]
            );
        }

        $response = $this->client->request(
            'GET',
            $url,
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('Version')]]
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * Loads content in a version for the content object reference by the given remote id.
     *
     * If no version is given, the method returns the current version
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException - if the content or version with the given remote id does not exist
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to load this version
     *
     * @param string $remoteId
     * @param array $languages A language filter for fields. If not given all languages are returned
     * @param int $versionNo the version number. If not given the current version is returned
     * @param bool $useAlwaysAvailable Add Main language to \$languages if true (default) and if alwaysAvailable is true
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content
     */
    public function loadContentByRemoteId($remoteId, array $languages = null, $versionNo = null, $useAlwaysAvailable = true)
    {
        $contentInfo = $this->loadContentInfoByRemoteId($remoteId);

        return $this->loadContentByContentInfo($contentInfo, $languages, $versionNo);
    }

    /**
     * Creates a new content draft assigned to the authenticated user.
     *
     * If a different userId is given in $contentCreateStruct it is assigned to the given user
     * but this required special rights for the authenticated user
     * (this is useful for content staging where the transfer process does not
     * have to authenticate with the user which created the content object in the source server).
     * The user has to publish the draft if it should be visible.
     * In 4.x at least one location has to be provided in the location creation array.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create the content in the given location
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if there is a provided remoteId which exists in the system
     *                                                                        or there is no location provided (4.x) or multiple locations
     *                                                                        are under the same parent or if the a field value is not accepted by the field type
     * @throws \App\eZ\Platform\API\Repository\Exceptions\ContentFieldValidationException if a field in the $contentCreateStruct is not valid
     * @throws \App\eZ\Platform\API\Repository\Exceptions\ContentValidationException if a required field is missing
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentCreateStruct $contentCreateStruct
     * @param \App\eZ\Platform\API\Repository\Values\Content\LocationCreateStruct[] $locationCreateStructs For each location parent under which a location should be created for the content
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content - the newly created content draft
     */
    public function createContent(ContentCreateStruct $contentCreateStruct, array $locationCreateStructs = array())
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Updates the metadata.
     *
     * (see {@link ContentMetadataUpdateStruct}) of a content object - to update fields use updateContent
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to update the content meta data
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the remoteId in $contentMetadataUpdateStruct is set but already exists
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentMetadataUpdateStruct $contentMetadataUpdateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content the content with the updated attributes
     */
    public function updateContentMetadata(ContentInfo $contentInfo, ContentMetadataUpdateStruct $contentMetadataUpdateStruct)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Deletes a content object including all its versions and locations including their subtrees.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to delete the content (in one of the locations of the given content object)
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     */
    public function deleteContent(ContentInfo $contentInfo)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Creates a draft from a published or archived version.
     *
     * If no version is given, the current published version is used.
     * 4.x: The draft is created with the initialLanguage code of the source version or if not present with the main language.
     * It can be changed on updating the version.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create the draft
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \App\eZ\Platform\API\Repository\Values\Content\VersionInfo $versionInfo
     * @param \App\eZ\Platform\API\Repository\Values\User\User $user if set given user is used to create the draft - otherwise the current user is used
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content - the newly created content draft
     */
    public function createContentDraft(ContentInfo $contentInfo, VersionInfo $versionInfo = null, User $user = null)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Loads drafts for a user.
     *
     * If no user is given the drafts for the authenticated user a returned
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to load the draft list
     *
     * @param \App\eZ\Platform\API\Repository\Values\User\User $user
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\VersionInfo the drafts ({@link VersionInfo}) owned by the given user
     */
    public function loadContentDrafts(User $user = null)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Updates the fields of a draft.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to update this version
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException if the version is not a draft
     * @throws \App\eZ\Platform\API\Repository\Exceptions\ContentFieldValidationException if a field in the $contentUpdateStruct is not valid
     * @throws \App\eZ\Platform\API\Repository\Exceptions\ContentValidationException if a required field is set to an empty value
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\VersionInfo $versionInfo
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentUpdateStruct $contentUpdateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content the content draft with the updated fields
     */
    public function updateContent(VersionInfo $versionInfo, ContentUpdateStruct $contentUpdateStruct)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Publishes a content version.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to publish this version
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException if the version is not a draft
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\VersionInfo $versionInfo
     * @param string[] $translations
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to publish this version
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException if the version is not a draft
     */
    public function publishVersion(VersionInfo $versionInfo, array $translations = Language::ALL)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Removes the given version.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException if the version is in
     *         published state or is a last version of the Content
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to remove this version
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\VersionInfo $versionInfo
     */
    public function deleteVersion(VersionInfo $versionInfo)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Loads all versions for the given content.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to list versions
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the given status is invalid
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param int|null $status
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\VersionInfo[] Sorted by creation date
     */
    public function loadVersions(ContentInfo $contentInfo, ?int $status = null)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Copies the content to a new location. If no version is given,
     * all versions are copied, otherwise only the given version.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to copy the content to the given location
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \App\eZ\Platform\API\Repository\Values\Content\LocationCreateStruct $destinationLocationCreateStruct the target location where the content is copied to
     * @param \App\eZ\Platform\API\Repository\Values\Content\VersionInfo $versionInfo
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content
     */
    public function copyContent(ContentInfo $contentInfo, LocationCreateStruct $destinationLocationCreateStruct, VersionInfo $versionInfo = null)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Finds content objects for the given query.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Query $query
     * @param array $languageFilter Configuration for specifying prioritized languages query will be performed on.
     *        Currently supported: <code>array("languages" => array(<language1>,..))</code>.
     * @param bool $filterOnUserPermissions if true only the objects which is the user allowed to read are returned.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\SearchResult
     */
    public function findContent(Query $query, array $languageFilter, $filterOnUserPermissions = true)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Performs a query for a single content object.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException if the object was not found by the query or due to permissions
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the query would return more than one result
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Query $query
     * @param array $languageFilter Configuration for specifying prioritized languages query will be performed on.
     *        Currently supported: <code>array("languages" => array(<language1>,..))</code>.
     * @param bool $filterOnUserPermissions if true only the objects which is the user allowed to read are returned.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content
     */
    public function findSingle(Query $query, array $languageFilter, $filterOnUserPermissions = true)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Loads all outgoing relations for the given version.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to read this version
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\VersionInfo $versionInfo
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Relation[]
     */
    public function loadRelations(VersionInfo $versionInfo)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Loads all incoming relations for a content object.
     *
     * The relations come only
     * from published versions of the source content objects
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to read this version
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Relation[]
     */
    public function loadReverseRelations(ContentInfo $contentInfo)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Adds a relation of type common.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to edit this version
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException if the version is not a draft
     *
     * The source of the relation is the content and version
     * referenced by $versionInfo.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\VersionInfo $sourceVersion
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $destinationContent the destination of the relation
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Relation the newly created relation
     */
    public function addRelation(VersionInfo $sourceVersion, ContentInfo $destinationContent)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Removes a relation of type COMMON from a draft.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed edit this version
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException if the version is not a draft
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if there is no relation of type COMMON for the given destination
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\VersionInfo $sourceVersion
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $destinationContent
     */
    public function deleteRelation(VersionInfo $sourceVersion, ContentInfo $destinationContent)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Instantiates a new content create struct object.
     *
     * alwaysAvailable is set to the ContentType's defaultAlwaysAvailable
     *
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentType $contentType
     * @param string $mainLanguageCode
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentCreateStruct
     */
    public function newContentCreateStruct(ContentType $contentType, $mainLanguageCode)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Instantiates a new content meta data update struct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentMetadataUpdateStruct
     */
    public function newContentMetadataUpdateStruct()
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Instantiates a new content update struct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentUpdateStruct
     */
    public function newContentUpdateStruct()
    {
        throw new \Exception('@todo: Implement.');
    }

    // Ignore this eZ Publish 5 feature by now.

    // @codeCoverageIgnoreStart

    /**
     * {@inheritdoc}
     */
    public function removeTranslation(ContentInfo $contentInfo, $languageCode)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * {@inheritdoc}
     */
    public function deleteTranslation(ContentInfo $contentInfo, $languageCode)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * {@inheritdoc}
     */
    public function deleteTranslationFromDraft(VersionInfo $versionInfo, $languageCode)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Bulk-load Content items by the list of ContentInfo Value Objects.
     *
     * Note: it does not throw exceptions on load, just ignores erroneous Content item.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo[] $contentInfoList
     * @param string[] $languages A language priority, filters returned fields and is used as prioritized language code on
     *                            returned value object. If not given all languages are returned.
     * @param bool $useAlwaysAvailable Add Main language to \$languages if true (default) and if alwaysAvailable is true,
     *                                 unless all languages have been asked for.
     *
     * @throws \Exception Not implemented
     */
    public function loadContentListByContentInfo(array $contentInfoList, array $languages = [], $useAlwaysAvailable = true)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Hides Content by making all the Locations appear hidden.
     * It does not persist hidden state on Location object itself.
     *
     * Content hidden by this API can be revealed by revealContent API.
     *
     * @see revealContent
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     */
    public function hideContent(ContentInfo $contentInfo): void
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Reveals Content hidden by hideContent API.
     * Locations which were hidden before hiding Content will remain hidden.
     *
     * @see hideContent
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     */
    public function revealContent(ContentInfo $contentInfo): void
    {
        throw new \Exception('@todo: Implement.');
    }
}
