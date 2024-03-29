<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\LocationUpdateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * This class is used for updating location meta data.
 */
class LocationUpdateStruct extends ValueObject
{
    /**
     * If set the location priority is changed to the new value.
     *
     * @var int
     */
    public $priority;

    /**
     * If set the location gets a new remoteId.
     *
     * Needs to be a unique Location->remoteId string value.
     *
     * @var mixed
     */
    public $remoteId;

    /**
     * If set the sortField is changed.
     * The sort field specifies which property the child locations should be sorted on.
     * Valid values are found at {@link Location::SORT_FIELD_*}.
     *
     * @var mixed
     */
    public $sortField;

    /**
     * If set the sortOrder is changed.
     * The sort order specifies whether the sort order should be ascending or descending.
     * Valid values are {@link Location::SORT_ORDER_*}.
     *
     * @var mixed
     */
    public $sortOrder;
}
