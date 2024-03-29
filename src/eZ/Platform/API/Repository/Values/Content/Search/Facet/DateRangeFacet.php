<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\Search\Facet\DateRangeFacet class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content\Search\Facet;

use App\eZ\Platform\API\Repository\Values\Content\Search\Facet;

/**
 * This class represents a date range facet holding counts for content in the built date ranges.
 */
class DateRangeFacet extends Facet
{
    /**
     * The date intervals with statistical data.
     *
     * @var \eZ\Platform\API\Repository\Values\Content\Search\Facet\RangeFacetEntry
     */
    public $entries;
}
