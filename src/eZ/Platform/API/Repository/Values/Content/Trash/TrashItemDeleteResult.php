<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content\Trash;

use App\eZ\Platform\API\Repository\Values\ValueObject;

class TrashItemDeleteResult extends ValueObject
{
    /** @var int */
    public $trashItemId;

    /** @var int */
    public $contentId;

    /**
     * Flag indicating content was removed.
     *
     * @var bool
     */
    public $contentRemoved = false;
}
