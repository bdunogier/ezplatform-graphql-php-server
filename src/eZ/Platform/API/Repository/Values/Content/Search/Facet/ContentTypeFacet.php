<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\Search\Facet\ContentTypeFacet class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content\Search\Facet;

use App\eZ\Platform\API\Repository\Values\Content\Search\Facet;

/**
 * This class holds counts of content with content type.
 */
class ContentTypeFacet extends Facet
{
    /**
     * An array with contentTypeId as key and count of matching content objects as value.
     *
     * @var array
     */
    public $entries;
}
