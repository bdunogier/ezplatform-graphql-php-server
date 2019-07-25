<?php

/**
 * File containing the eZ\Platform\API\Repository\Exceptions\NotFoundException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Exceptions;

use Exception;

/**
 * This Exception is thrown if an object referenced by an id or identifier
 * could not be found in the repository.
 */
abstract class NotFoundException extends Exception
{
}
