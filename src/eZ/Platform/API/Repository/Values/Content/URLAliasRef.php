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
 * This class represents a url alias in the repository.
 *
 * @property-read string $href
 * @property-read string $mediaType
 */
class URLAliasRef extends ValueObject
{
    protected $href;

    protected $mediaType;
}
