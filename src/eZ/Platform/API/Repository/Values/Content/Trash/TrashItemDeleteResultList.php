<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content\Trash;

use App\eZ\Platform\API\Repository\Values\ValueObject;

class TrashItemDeleteResultList extends ValueObject implements \IteratorAggregate
{
    /** @var \eZ\Platform\API\Repository\Values\Content\Trash\TrashItemDeleteResult[] */
    public $items = [];

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}
