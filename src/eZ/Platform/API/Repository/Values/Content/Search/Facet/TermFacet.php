<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\Search\Facet\TermFacet class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content\Search\Facet;

use App\eZ\Platform\API\Repository\Values\Content\Search\Facet;

/**
 * this class hold counts for content in sections.
 */
class TermFacet extends Facet
{
    /**
     * An array with term as key and count of matching content objects as value.
     *
     * @var array
     */
    public $entries;
}
