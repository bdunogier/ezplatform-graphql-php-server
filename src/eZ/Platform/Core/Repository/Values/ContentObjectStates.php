<?php

/**
 * File containing the ContentObjectStates class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Values;

use App\eZ\Platform\Core\Repository\Value as RestValue;

/**
 * ContentObjectStates view model.
 */
class ContentObjectStates extends RestValue
{
    /**
     * Object states.
     *
     * @var array
     */
    public $states;

    /**
     * Construct.
     *
     * @param array $states
     */
    public function __construct(array $states)
    {
        $this->states = $states;
    }
}
