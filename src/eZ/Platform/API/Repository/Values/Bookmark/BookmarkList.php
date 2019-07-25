<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\eZ\Platform\API\Repository\Values\Bookmark;

use ArrayIterator;
use IteratorAggregate;
use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * List of bookmarked locations.
 */
class BookmarkList extends ValueObject implements IteratorAggregate
{
    /**
     * The total number of bookmarks.
     *
     * @var int
     */
    public $totalCount = 0;

    /**
     * List of bookmarked locations.
     *
     * @var \eZ\Platform\API\Repository\Values\Content\Location[]
     */
    public $items = [];

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new ArrayIterator($this->items);
    }
}
