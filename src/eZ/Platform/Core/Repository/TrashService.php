<?php

/**
 * File containing the TrashService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\API\Repository\TrashService as APITrashService;
use App\eZ\Platform\API\Repository\Values\Content\Query;
use App\eZ\Platform\API\Repository\Values\Content\Location;
use App\eZ\Platform\API\Repository\Values\Content\Trash\SearchResult;
use App\eZ\Platform\API\Repository\Values\Content\TrashItem as APITrashItem;
use App\eZ\Platform\Core\Repository\Values\Content\TrashItem;
use App\eZ\Platform\Core\Repository\RequestParser;
use App\eZ\Platform\Core\Repository\Input\Dispatcher;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use App\eZ\Platform\Core\Repository\Message;

/**
 * Trash service used for content/location trash handling.
 */
class TrashService implements APITrashService, Sessionable
{
    /** @var \App\eZ\Platform\Core\Repository\LocationService */
    private $locationService;

    /** @var \App\eZ\Platform\Core\Repository\HttpClient */
    private $client;

    /** @var \App\eZ\Platform\Core\Repository\Input\Dispatcher */
    private $inputDispatcher;

    /** @var \App\eZ\Platform\Core\Repository\Output\Visitor */
    private $outputVisitor;

    /** @var \App\eZ\Platform\Core\Repository\RequestParser */
    private $requestParser;

    /**
     * @param \App\eZ\Platform\Core\Repository\LocationService $locationService
     * @param \App\eZ\Platform\Core\Repository\\Symfony\Contracts\HttpClient\HttpClientInterface $ezpRestClient
     * @param \App\eZ\Platform\Core\Repository\Input\Dispatcher $inputDispatcher
     * @param \App\eZ\Platform\Core\Repository\Output\Visitor $outputVisitor
     * @param \App\eZ\Platform\Core\Repository\RequestParser $requestParser
     */
    public function __construct(LocationService $locationService, \Symfony\Contracts\HttpClient\HttpClientInterface $ezpRestClient, Dispatcher $inputDispatcher, Visitor $outputVisitor, RequestParser $requestParser)
    {
        $this->locationService = $locationService;
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
     */
    public function setSession($id)
    {
        if ($this->outputVisitor instanceof Sessionable) {
            $this->outputVisitor->setSession($id);
        }
    }

    /**
     * Loads a trashed location object from its $id.
     *
     * Note that $id is identical to original location, which has been previously trashed
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to read the trashed location
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException - if the location with the given id does not exist
     *
     * @param mixed $trashItemId
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\TrashItem
     */
    public function loadTrashItem($trashItemId)
    {
        $response = $this->client->request(
            'GET',
            $trashItemId,
            new Message(
                array('Accept' => $this->outputVisitor->getMediaType('Location'))
            )
        );

        $location = $this->inputDispatcher->parse($response);

        return $this->buildTrashItem($location);
    }

    /**
     * Sends $location and all its children to trash and returns the corresponding trash item.
     *
     * The current user may not have access to the returned trash item, check before using it.
     * Content is left untouched.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to trash the given location
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $location
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\TrashItem
     */
    public function trash(Location $location)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Recovers the $trashedLocation at its original place if possible.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to recover the trash item at the parent location location
     *
     * If $newParentLocation is provided, $trashedLocation will be restored under it.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\TrashItem $trashItem
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $newParentLocation
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location the newly created or recovered location
     */
    public function recover(APITrashItem $trashItem, Location $newParentLocation = null)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Empties trash.
     *
     * All locations contained in the trash will be removed. Content objects will be removed
     * if all locations of the content are gone.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to empty the trash
     */
    public function emptyTrash()
    {
        $response = $this->client->request(
            'DELETE',
            $this->requestParser->generate('trashItems'),
            new Message(
                // @todo: What media-type should we set here? Actually, it should be
                // all expected exceptions + none? Or is "Location" correct,
                // since this is what is to be expected by the resource
                // identified by the URL?
                array('Accept' => $this->outputVisitor->getMediaType('Location'))
            )
        );

        if (!empty($response->body)) {
            $this->inputDispatcher->parse($response);
        }
    }

    /**
     * Deletes a trash item.
     *
     * The corresponding content object will be removed
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to delete this trash item
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\TrashItem $trashItem
     */
    public function deleteTrashItem(APITrashItem $trashItem)
    {
        $response = $this->client->request(
            'DELETE',
            $trashItem->id,
            new Message(
                // @todo: What media-type should we set here? Actually, it should be
                // all expected exceptions + none? Or is "Location" correct,
                // since this is what is to be expected by the resource
                // identified by the URL?
                array('Accept' => $this->outputVisitor->getMediaType('Location'))
            )
        );

        if (!empty($response->body)) {
            $this->inputDispatcher->parse($response);
        }
    }

    /**
     * Returns a collection of Trashed locations contained in the trash, which are readable by the current user.
     *
     * $query allows to filter/sort the elements to be contained in the collection.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Query $query
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Trash\SearchResult
     */
    public function findTrashItems(Query $query)
    {
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('trashItems'),
            new Message(
                array('Accept' => $this->outputVisitor->getMediaType('LocationList'))
            )
        );

        $locations = $this->inputDispatcher->parse($response);

        $trashItems = array();
        foreach ($locations as $location) {
            $trashItems[] = $this->buildTrashItem($location);
        }


        return new SearchResult([
            'items' => $trashItems,
            'totalCount' => count($trashItems),
        ]);
    }

    /**
     * Converts the Location value object to TrashItem value object.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Location $location
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\TrashItem
     */
    protected function buildTrashItem(Location $location)
    {
        return new TrashItem(
            array(
                'contentInfo' => $location->contentInfo,
                'id' => $location->id,
                'priority' => $location->priority,
                'hidden' => $location->hidden,
                'invisible' => $location->invisible,
                'remoteId' => $location->remoteId,
                'parentLocationId' => $location->parentLocationId,
                'pathString' => $location->pathString,
                'depth' => (int)$location->depth,
                'sortField' => $location->sortField,
                'sortOrder' => $location->sortOrder,
            )
        );
    }
}
