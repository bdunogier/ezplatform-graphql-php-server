<?php

/**
 * File containing the Location class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values\Content;

use App\eZ\Platform\API\Repository\Values\Content\Location as APILocation;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\Values\Content\Location}
 * class.
 *
 * @see \App\eZ\Platform\API\Repository\Values\Content\Location
 */
class Location extends APILocation
{
    /**
     * ContentInfo.
     *
     * @var \App\eZ\Platform\API\Repository\Values\Content\ContentInfo
     */
    protected $contentInfo;

    /**
     * Returns the content info of the content object of this location.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentInfo
     */
    public function getContentInfo()
    {
        return $this->contentInfo;
    }

    /**
     * References to other resources from the Location
     */
    protected $references = [];
}
