<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\Search\Facet\SectionFacet class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content\Search\Facet;

use App\eZ\Platform\API\Repository\Values\Content\Search\Facet;

/**
 * this class hold counts for content in sections.
 */
class SectionFacet extends Facet
{
    /**
     * An array with sectionId as key and count of matching content objects as value.
     *
     * @var array
     */
    public $entries;
}
