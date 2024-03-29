<?php

/**
 * File containing the InvalidArgumentException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Exceptions;

use App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException as APIInvalidArgumentException;

/**
 * This exception is thrown if a service method is called with an illegal or non appropriate value.
 */
class InvalidArgumentException extends APIInvalidArgumentException
{
}
