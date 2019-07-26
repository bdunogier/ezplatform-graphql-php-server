<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\URLAlias class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * @property-read int|string $id
 * @property-read URLAliasRef[] $refList
 */
class URLAliasRefList extends ValueObject
{
    protected $id;

    /**
     * @var URLAliasRef[]
     */
    protected $refList = [];
}
