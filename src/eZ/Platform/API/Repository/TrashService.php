<?php

/**
 * File containing the eZ\Platform\API\Repository\TrashService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository;

use App\eZ\Platform\API\Repository\Values\Content\Location;
use App\eZ\Platform\API\Repository\Values\Content\TrashItem;
use App\eZ\Platform\API\Repository\Values\Content\Query;

/**
 * Trash service, used for managing trashed content.
 */
interface TrashService
{
    /**
     * Loads a trashed location object from its $id.
     *
     * Note that $id is identical to original location, which has been previously trashed
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to read the trashed location
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException - if the location with the given id does not exist
     *
     * @param mixed $trashItemId
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\TrashItem
     */
    public function loadTrashItem($trashItemId);

    /**
     * Sends $location and all its children to trash and returns the corresponding trash item.
     *
     * The current user may not have access to the returned trash item, check before using it.
     * Content is left untouched.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to trash the given location
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Location $location
     *
     * @return null|\eZ\Platform\API\Repository\Values\Content\TrashItem null if location was deleted, otherwise TrashItem
     */
    public function trash(Location $location);

    /**
     * Recovers the $trashedLocation at its original place if possible.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to recover the trash item at the parent location location
     *
     * If $newParentLocation is provided, $trashedLocation will be restored under it.
     *
     * @param \eZ\Platform\API\Repository\Values\Content\TrashItem $trashItem
     * @param \eZ\Platform\API\Repository\Values\Content\Location $newParentLocation
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Location the newly created or recovered location
     */
    public function recover(TrashItem $trashItem, Location $newParentLocation = null);

    /**
     * Empties trash.
     *
     * All locations contained in the trash will be removed. Content objects will be removed
     * if all locations of the content are gone.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to empty the trash
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Trash\TrashItemDeleteResultList
     */
    public function emptyTrash();

    /**
     * Deletes a trash item.
     *
     * The corresponding content object will be removed
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to delete this trash item
     *
     * @param \eZ\Platform\API\Repository\Values\Content\TrashItem $trashItem
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Trash\TrashItemDeleteResult
     */
    public function deleteTrashItem(TrashItem $trashItem);

    /**
     * Returns a collection of Trashed locations contained in the trash, which are readable by the current user.
     *
     * $query allows to filter/sort the elements to be contained in the collection.
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Query $query
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Trash\SearchResult
     */
    public function findTrashItems(Query $query);
}
