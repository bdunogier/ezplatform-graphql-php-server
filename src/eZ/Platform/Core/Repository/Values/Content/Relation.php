<?php

/**
 * File containing the Relation class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values\Content;

use App\eZ\Platform\API\Repository\Values\Content\Relation as APIRelation;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\Values\Content\Relation}
 * class.
 *
 * @see \App\eZ\Platform\API\Repository\Values\Content\Relation
 */
class Relation extends APIRelation
{
    /** @var \App\eZ\Platform\API\Repository\Values\Content\ContentInfo */
    protected $sourceContentInfo;

    /** @var \App\eZ\Platform\API\Repository\Values\Content\ContentInfo */
    protected $destinationContentInfo;

    /** @var int */
    protected $type;

    /**
     * the content of the source content of the relation.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentInfo
     */
    public function getSourceContentInfo()
    {
        return $this->sourceContentInfo;
    }

    /**
     * the content of the destination content of the relation.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentInfo
     */
    public function getDestinationContentInfo()
    {
        return $this->destinationContentInfo;
    }
}
