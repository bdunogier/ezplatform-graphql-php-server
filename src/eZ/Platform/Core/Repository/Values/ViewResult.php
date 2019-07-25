<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values;

use App\eZ\Platform\API\Repository\Values\Content\Search\SearchHit;
use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * @property-read SearchHit[] $searchHits
 */
class ViewResult extends ValueObject
{
    /** @var SearchHit[] */
    protected $searchHits;
}
