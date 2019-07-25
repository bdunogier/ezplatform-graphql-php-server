<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\eZ\Platform\API\Repository\Values\UserPreference;

use ArrayIterator;
use IteratorAggregate;
use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * List of user preferences.
 */
class UserPreferenceList extends ValueObject implements IteratorAggregate
{
    /**
     * The total number of user preferences.
     *
     * @var int
     */
    public $totalCount = 0;

    /**
     * List of user preferences.
     *
     * @var \eZ\Platform\API\Repository\Values\UserPreference\UserPreference[]
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
