<?php

/**
 * File containing the LocationUpdateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\API\Repository\LocationService as APILocationService;
use App\eZ\Platform\API\Repository\Values\Content\LocationUpdateStruct;
use App\eZ\Platform\API\Repository\Values\Content\LocationCreateStruct;
use App\eZ\Platform\API\Repository\Values\Content\ContentInfo;
use App\eZ\Platform\API\Repository\Values\Content\Location;
use App\eZ\Platform\API\Repository\Values\Content\VersionInfo;
use App\eZ\Platform\Core\Repository\RequestParser;
use App\eZ\Platform\Core\Repository\Input\Dispatcher;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use App\eZ\Platform\Core\Repository\Message;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentType;

/**
 * Location service, used for complex subtree operations.
 *
 * @example Examples/location.php
 */
class LocationService implements APILocationService, Sessionable
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
     * Instantiates a new location create class.
     *
     * @param mixed $parentLocationId the parent under which the new location should be created
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\ContentType|null $contentType
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\LocationCreateStruct
     */
    public function newLocationCreateStruct($parentLocationId, ContentType $contentType = null)
    {
        $properties = [
            'parentLocationId' => $parentLocationId,
        ];
        if ($contentType) {
            $properties['sortField'] = $contentType->defaultSortField;
            $properties['sortOrder'] = $contentType->defaultSortOrder;
        }

        return new LocationCreateStruct($properties);
    }

    /**
     * Creates the new $location in the content repository for the given content.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to create this location
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException  if the content is already below the specified parent
     *                                        or the parent is a sub location of the location the content
     *                                        or if set the remoteId exists already
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \App\eZ\Platform\API\Repository\Values\Content\LocationCreateStruct $locationCreateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location the newly created Location
     */
    public function createLocation(ContentInfo $contentInfo, LocationCreateStruct $locationCreateStruct)
    {
        $inputMessage = $this->outputVisitor->visit($locationCreateStruct);
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('Location');

        $values = $this->requestParser->parse('object', $contentInfo->id);
        $result = $this->client->request(
            'POST',
            $this->requestParser->generate('objectLocations', array('object' => $values['object'])),
            $inputMessage
        );

        return $this->inputDispatcher->parse($result);
    }

    /**
     * {@inheritdoc)
     */
    public function loadLocation($locationId, array $prioritizedLanguages = null, bool $useAlwaysAvailable = null)
    {
        $response = $this->client->request(
            'GET',
            $locationId,
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('Location')]]
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * {@inheritdoc)
     */
    public function loadLocationList(array $locationIds, array $prioritizedLanguages = null, bool $useAlwaysAvailable = null): iterable
    {
        // @todo Implement server part, ala: https://gist.github.com/andrerom/f2f328029ae7a9d78b363282b3ddf4a4

        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('locationsByIds', ['locations' => $locationIds]),
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('LocationList')]]
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * {@inheritdoc)
     */
    public function loadLocationByRemoteId($remoteId, array $prioritizedLanguages = null, bool $useAlwaysAvailable = null)
    {
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('locationByRemote', array('location' => $remoteId)),
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('LocationList')]]
        );

        return reset($this->inputDispatcher->parse($response));
    }

    /**
     * Instantiates a new location update class.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\LocationUpdateStruct
     */
    public function newLocationUpdateStruct()
    {
        return new LocationUpdateStruct();
    }

    /**
     * Updates $location in the content repository.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to update this location
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException   if if set the remoteId exists already
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $location
     * @param \App\eZ\Platform\API\Repository\Values\Content\LocationUpdateStruct $locationUpdateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location the updated Location
     */
    public function updateLocation(Location $location, LocationUpdateStruct $locationUpdateStruct)
    {
        $result = $this->client->request(
            'POST',
            $location->id,
            [
                'body' => $this->outputVisitor->visit($locationUpdateStruct)->getContent(),
                'headers' => [
                    'Accept' => $this->outputVisitor->getMediaType('Location'),
                    'X-HTTP-Method-Override' => 'PATCH',
                ]
            ]
        );

        return $this->inputDispatcher->parse($result);
    }

    /**
     * Loads the locations for the given content object.
     *
     * If a $rootLocation is given, only locations that belong to this location are returned.
     * The location list is also filtered by permissions on reading locations.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException if there is no published version yet
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $rootLocation
     * @param string[]|null $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location[]
     */
    public function loadLocations(ContentInfo $contentInfo, Location $rootLocation = null, array $prioritizedLanguages = null)
    {
        $values = $this->requestParser->parse($contentInfo->id);
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('objectLocations', array('object' => $values['object'])),
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('LocationList')]]
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * Loads children which are readable by the current user of a location object sorted by sortField and sortOrder.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $location
     * @param int $offset the start offset for paging
     * @param int $limit the number of locations returned
     * @param string[]|null $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\LocationList
     */
    public function loadLocationChildren(Location $location, $offset = 0, $limit = 25, array $prioritizedLanguages = null)
    {
        $values = $this->requestParser->parse($location->id);
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('locationChildren', array('location' => $values['location'])),
            ['headers' => ['Accept' => $this->outputVisitor->getMediaType('LocationList')]]
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * Load parent Locations for Content Draft.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\VersionInfo $versionInfo
     * @param string[]|null $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location[] List of parent Locations
     */
    public function loadParentLocationsForDraftContent(VersionInfo $versionInfo, array $prioritizedLanguages = null)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Returns the number of children which are readable by the current user of a location object.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $location
     *
     * @return int
     */
    public function getLocationChildCount(Location $location)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Swaps the contents hold by the $location1 and $location2.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to swap content
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $location1
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $location2
     */
    public function swapLocation(Location $location1, Location $location2)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Hides the $location and marks invisible all descendants of $location.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to hide this location
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $location
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location $location, with updated hidden value
     */
    public function hideLocation(Location $location)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Unhides the $location.
     *
     * This method and marks visible all descendants of $locations
     * until a hidden location is found.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to unhide this location
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $location
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location $location, with updated hidden value
     */
    public function unhideLocation(Location $location)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Deletes $location and all its descendants.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user is not allowed to delete this location or a descendant
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $location
     */
    public function deleteLocation(Location $location)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Copies the subtree starting from $subtree as a new subtree of $targetLocation.
     *
     * Only the items on which the user has read access are copied.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed copy the subtree to the given parent location
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException  if the target location is a sub location of the given location
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $subtree - the subtree denoted by the location to copy
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $targetParentLocation - the target parent location for the copy operation
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location The newly created location of the copied subtree
     *
     * @todo enhancement - this method should return a result structure containing the new location and a list
     *       of locations which are not copied due to permission denials.
     */
    public function copySubtree(Location $subtree, Location $targetParentLocation)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Moves the subtree to $newParentLocation.
     *
     * If a user has the permission to move the location to a target location
     * he can do it regardless of an existing descendant on which the user has no permission.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to move this location to the target
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $location
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $newParentLocation
     */
    public function moveSubtree(Location $location, Location $newParentLocation)
    {
        throw new \Exception('@todo: Implement.');
    }

    public function getAllLocationsCount(): int
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * @param int $limit
     * @param int $offset
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location[]
     *
     * @throws \Exception
     */
    public function loadAllLocations(int $offset = 0, int $limit = 25): array
    {
        throw new \Exception('@todo: Implement.');
    }
}
