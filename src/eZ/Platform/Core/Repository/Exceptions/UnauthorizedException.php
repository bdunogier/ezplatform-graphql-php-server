<?php

/**
 * File containing the UnauthorizedException class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Exceptions;

use App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException as APIUnauthorizedException;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException}
 * interface.
 *
 * @see \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException
 */
class UnauthorizedException extends APIUnauthorizedException
{
}
