<?php

/**
 * File containing the Policy class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\User\Policy as APIPolicy;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\Values\User\Policy}
 * class.
 *
 * @see \App\eZ\Platform\API\Repository\Values\User\Policy
 */
class Policy extends APIPolicy
{
    /** @var \App\eZ\Platform\API\Repository\Values\User\Limitation[] */
    protected $limitations = array();

    /**
     * @return \App\eZ\Platform\API\Repository\Values\User\Limitation[]
     */
    public function getLimitations()
    {
        return $this->limitations;
    }
}
