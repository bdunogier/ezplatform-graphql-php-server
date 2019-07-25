<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Values;

use App\eZ\Platform\API\Repository\Values\ValueObject;

class ViewInput extends ValueObject
{
    public $identifier;

    /** @var \App\eZ\Platform\API\Repository\Values\Content\Query */
    public $contentQuery;

    /** @var \App\eZ\Platform\API\Repository\Values\Content\LocationQuery */
    public $locationQuery;
}
