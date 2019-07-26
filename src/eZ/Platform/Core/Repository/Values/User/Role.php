<?php

/**
 * File containing the Role class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\User\Role as APIRole;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\Values\User\Role}
 * class.
 *
 * @see \App\eZ\Platform\API\Repository\Values\User\Role
 */
class Role extends APIRole
{
    /** @var \App\eZ\Platform\API\Repository\Values\User\Policy[] */
    protected $policies;

    /**
     * Instantiates a role stub instance.
     *
     * @param array $properties
     * @param \App\eZ\Platform\API\Repository\Values\User\Policy[] $policies
     */
    public function __construct(array $properties = array(), array $policies = array())
    {
        parent::__construct($properties);

        $this->policies = $policies;
    }

    /**
     * Returns the list of policies of this role.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\Policy[]
     */
    public function getPolicies()
    {
        return $this->policies;
    }
}
