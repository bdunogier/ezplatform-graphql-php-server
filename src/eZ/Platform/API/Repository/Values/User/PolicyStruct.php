<?php
/**
 * This file is part of the eZ Publish Kernel package.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\ValueObject;

abstract class PolicyStruct extends ValueObject
{
    /**
     * Returns list of limitations added to policy.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\Limitation[]
     */
    abstract public function getLimitations();

    /**
     * Adds a limitation with the given identifier and list of values.
     *
     * @param Limitation $limitation
     */
    abstract public function addLimitation(Limitation $limitation);
}
