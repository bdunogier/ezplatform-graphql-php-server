<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\User\Role class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * This class represents a role.
 *
 * @property-read mixed $id the internal id of the role
 * @property-read string $identifier the identifier of the role
 *
 * @property-read array $policies an array of the policies {@link \eZ\Platform\API\Repository\Values\User\Policy} of the role.
 */
abstract class Role extends ValueObject
{
    /** @var int Status constant for defined (aka "published") role */
    const STATUS_DEFINED = 0;

    /** @var int Status constant for draft (aka "temporary") role */
    const STATUS_DRAFT = 1;

    /**
     * ID of the user role.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Readable string identifier of a role
     * in 4.x. this is mapped to the role name.
     *
     * @var string
     */
    protected $identifier;

    /**
     * The status of the role.
     *
     * @var int One of Role::STATUS_DEFINED|Role::STATUS_DRAFT
     */
    protected $status;

    /**
     * Returns the list of policies of this role.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\Policy[]
     */
    abstract public function getPolicies();
}
