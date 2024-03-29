<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\Search\Facet\FieldFacet class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content\Search\Facet;

use App\eZ\Platform\API\Repository\Values\Content\Search\Facet;

/**
 * Facet containing counts for content below child locations.
 */
class LocationFacet extends Facet
{
    /**
     * An array with location id as key and count of matching content objects which are below this location as value.
     *
     * @var array
     */
    public $entries;
}
