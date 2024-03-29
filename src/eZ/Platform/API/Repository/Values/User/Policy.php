<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\User\Policy class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * This class represents a policy value.
 *
 * @property-read mixed $id internal id of the policy
 * @property-read mixed $roleId the role id this policy belongs to
 * @property-read string $module Name of module, associated with the Policy
 * @property-read string $function  Name of the module function Or all functions with '*'
 * @property-read array $limitations an array of \eZ\Platform\API\Repository\Values\User\Limitation
 */
abstract class Policy extends ValueObject
{
    /**
     * ID of the policy.
     *
     * @var mixed
     */
    protected $id;

    /**
     * the ID of the role this policy belongs to.
     *
     * @var mixed
     */
    protected $roleId;

    /**
     * Name of module, associated with the Policy.
     *
     * Eg: content
     *
     * @var string
     */
    protected $module;

    /**
     * Name of the module function Or all functions with '*'.
     *
     * Eg: read
     *
     * @var string
     */
    protected $function;

    /**
     * @return \App\eZ\Platform\API\Repository\Values\User\Limitation[]
     */
    abstract public function getLimitations();
}
