<?php

/**
 * File containing the RestContentMetadataUpdateStruct.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Values;

use App\eZ\Platform\API\Repository\Values\Content\ContentMetadataUpdateStruct;

/**
 * Extended ContentMetadataUpdateStruct that includes section information.
 */
class RestContentMetadataUpdateStruct extends ContentMetadataUpdateStruct
{
    /**
     * ID of the section to assign.
     *
     * Leave null to not change section assignment.
     *
     * @var mixed
     */
    public $sectionId;
}
