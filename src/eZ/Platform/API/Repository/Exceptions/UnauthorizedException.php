<?php

/**
 * File containing the eZ\Platform\API\Repository\Exceptions\UnauthorizedException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Exceptions;

use Exception;

/**
 * This Exception is thrown if the user has is not allowed to perform a service operation.
 */
abstract class UnauthorizedException extends Exception
{
}
