<?php

/**
 * File containing the UserGroupRoleAssignment class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\User\UserGroupRoleAssignment as APIUserGroupRoleAssignment;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\Values\User\UserGroupRoleAssignment}
 * class.
 *
 * @see \App\eZ\Platform\API\Repository\Values\User\UserGroupRoleAssignment
 */
class UserGroupRoleAssignment extends APIUserGroupRoleAssignment
{
    /** @var \App\eZ\Platform\API\Repository\Values\User\Role */
    protected $role;

    /** @var \App\eZ\Platform\API\Repository\Values\User\UserGroup */
    protected $userGroup;

    /** @var \App\eZ\Platform\API\Repository\Values\User\Limitation\RoleLimitation */
    protected $limitation;

    /**
     * Returns the limitation of the role assignment.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\Limitation\RoleLimitation
     */
    public function getRoleLimitation()
    {
        return $this->limitation;
    }

    /**
     * Returns the role to which the user or user group is assigned to.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\Role
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * Returns the user group to which the role is assigned to.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\UserGroup
     */
    public function getUserGroup()
    {
        return $this->userGroup;
    }
}
