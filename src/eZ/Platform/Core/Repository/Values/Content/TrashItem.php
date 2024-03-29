<?php

/**
 * File containing the TrashItem class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values\Content;

use App\eZ\Platform\API\Repository\Values\Content\TrashItem as APITrashItem;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\Values\Content\TrashItem}
 * class.
 *
 * @see \App\eZ\Platform\API\Repository\Values\Content\TrashItem
 */
class TrashItem extends APITrashItem
{
    /** @var \App\eZ\Platform\API\Repository\Values\Content\Location */
    protected $location;

    /**
     * Returns the content info of the content object of this location.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentInfo
     */
    public function getContentInfo()
    {
        return $this->location->getContentInfo();
    }

    public function __get($property)
    {
        switch ($property) {
            case 'id':
                return $this->getContentInfo()->id;
        }

        return parent::__get($property);
    }
}
