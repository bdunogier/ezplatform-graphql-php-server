<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\Query\FacetBuilder\TermFacetBuilder class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content\Query\FacetBuilder;

use App\eZ\Platform\API\Repository\Values\Content\Query\FacetBuilder;

/**
 * Build a term facet.
 *
 * If provided the search service returns a TermFacet which contains the counts for
 * content containing terms in arbitrary fields
 */
class TermFacetBuilder extends FacetBuilder
{
}
