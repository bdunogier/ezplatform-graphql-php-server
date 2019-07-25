<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\User\UserGroupUpdateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * This class is used to update a user group in the repository.
 */
class UserGroupUpdateStruct extends ValueObject
{
    /**
     * The update structure for the profile content.
     *
     * @var \eZ\Platform\API\Repository\Values\Content\ContentUpdateStruct
     */
    public $contentUpdateStruct = null;

    /**
     * The update structure for the profile meta data.
     *
     * @var \eZ\Platform\API\Repository\Values\Content\ContentMetadataUpdateStruct
     */
    public $contentMetadataUpdateStruct = null;
}
