<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values;

use App\eZ\Platform\API\Repository\Values\Content\Query;
use App\eZ\Platform\API\Repository\Values\ValueObject;
use App\eZ\Platform\Core\Repository\Values\ViewResult;

/**
 * @property-read string $identifier
 * @property-read \eZ\Publish\API\Repository\Values\Content\Query $query
 * @property-read ViewResult $result
 */
class View extends ValueObject
{
    /** @var string */
    protected $identifier;

    /** @var Query */
    protected $query;

    /** @var ViewResult */
    protected $result;
}
