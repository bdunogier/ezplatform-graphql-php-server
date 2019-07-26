<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\DataLoader\Exception;

use App\eZ\Platform\API\Repository\Exceptions\NotFoundException as NotFoundApiException;

class NotFoundException extends NotFoundApiException
{
}
