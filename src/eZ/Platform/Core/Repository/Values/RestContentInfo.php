<?php

/**
 * File containing the RestContentInfo class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * Subset of ContentInfo submitted by REST + some info submitted in addition.
 */
class RestContentInfo extends ValueObject
{
    protected $id;
    protected $name;
    protected $contentTypeId;
    protected $ownerId;
    protected $modificationDate;
    protected $publishedDate;
    protected $published;
    protected $alwaysAvailable;
    protected $remoteId;
    protected $mainLanguageCode;
    protected $mainLocationId;
    protected $sectionId;

    protected $versionListReference;
    protected $currentVersionReference;
    protected $locationListReference;
}
