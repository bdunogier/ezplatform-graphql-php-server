<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\User\UserCreateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\Content\ContentCreateStruct;

/**
 * This class is used to create a new user in the repository.
 */
abstract class UserCreateStruct extends ContentCreateStruct
{
    /**
     * User login.
     *
     * Required.
     *
     * @var string
     */
    public $login;

    /**
     * User E-Mail address.
     *
     * Required.
     *
     * @var string
     */
    public $email;

    /**
     * The plain password.
     *
     * Required.
     *
     * @var string
     */
    public $password;

    /**
     * Indicates if the user is enabled after creation.
     *
     * @var bool
     */
    public $enabled = true;
}
