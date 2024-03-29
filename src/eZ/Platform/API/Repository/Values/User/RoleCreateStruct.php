<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\User\RoleCreateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * This class is used to create a new role.
 */
abstract class RoleCreateStruct extends ValueObject
{
    /**
     * Readable string identifier of a role.
     *
     * @var string
     */
    public $identifier;

    /**
     * Returns policies associated with the role.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\PolicyCreateStruct[]
     */
    abstract public function getPolicies();

    /**
     * Adds a policy to this role.
     *
     * @param \eZ\Platform\API\Repository\Values\User\PolicyCreateStruct $policyCreateStruct
     */
    abstract public function addPolicy(PolicyCreateStruct $policyCreateStruct);
}
