<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\User\Limitation\SubtreeLimitation class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\User\Limitation;

use App\eZ\Platform\API\Repository\Values\User\Limitation;

class SubtreeLimitation extends RoleLimitation
{
    /**
     * @see \eZ\Platform\API\Repository\Values\User\Limitation::getIdentifier()
     *
     * @return string
     */
    public function getIdentifier()
    {
        return Limitation::SUBTREE;
    }
}
