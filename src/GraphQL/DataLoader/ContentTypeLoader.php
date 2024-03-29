<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\DataLoader;

use App\eZ\Platform\API\Repository\Values\ContentType\ContentType;

/**
 * @internal
 */
interface ContentTypeLoader
{
    public function load($contentTypeId): ContentType;

    public function loadByIdentifier($identifier): ContentType;
}
