<?php

/**
 * File containing the RoleCreateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values;
use App\eZ\Platform\API\Repository\Values\User\RoleCreateStruct as APIRoleCreateStruct;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\Values\User\RoleCreateStruct}
 * class.
 *
 * @see \App\eZ\Platform\API\Repository\Values\User\RoleCreateStruct
 */
class RoleCreateStruct extends APIRoleCreateStruct
{
    /** @var \App\eZ\Platform\API\Repository\Values\User\PolicyCreateStruct[] */
    private $policies = array();

    /**
     * Instantiates a role create class.
     *
     * @param string $name
     */
    public function __construct($name)
    {
        parent::__construct(array('identifier' => $name));
    }

    /**
     * Returns policies associated with the role.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\PolicyCreateStruct[]
     */
    public function getPolicies()
    {
        return $this->policies;
    }

    /**
     * Adds a policy to this role.
     *
     * @param \App\eZ\Platform\API\Repository\Values\User\PolicyCreateStruct $policyCreateStruct
     */
    public function addPolicy(Values\User\PolicyCreateStruct $policyCreateStruct)
    {
        $this->policies[] = $policyCreateStruct;
    }
}
