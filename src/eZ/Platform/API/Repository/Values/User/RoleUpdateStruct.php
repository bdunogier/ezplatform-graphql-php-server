<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\User\RoleUpdateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * This class is used to update a role.
 */
class RoleUpdateStruct extends ValueObject
{
    /**
     * Readable string identifier of a role.
     *
     * @var string
     */
    public $identifier;
}
