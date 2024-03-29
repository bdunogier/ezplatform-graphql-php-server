<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\Query\FacetBuilder\SubtreeFacetBuilder class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content\Query\FacetBuilder;

use App\eZ\Platform\API\Repository\Values\Content\Query\FacetBuilder;

/**
 * Build a Subtree facet.
 *
 * If provided the search service returns a LocationFacet
 * which contains the counts for all content objects below the child locations.
 *
 * @todo: check hierarchical facets
 */
class LocationFacetBuilder extends FacetBuilder
{
    /**
     * The parent location.
     *
     * @var \eZ\Platform\API\Repository\Values\Content\Location
     */
    public $location;
}
