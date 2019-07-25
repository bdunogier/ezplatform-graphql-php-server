<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Values\Content;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * A REST VersionUpdate.
 *
 * Aggregate of a ContentUpdateStruct and the updated ContentType.
 *
 * @property-read \eZ\Publish\API\Repository\Values\Content\ContentUpdateStruct $contentUpdateStruct
 * @property-read \eZ\Publish\API\Repository\Values\ContentType\ContentType $contentType
 * @property-read string $url
 */
class VersionUpdate extends ValueObject
{
    /** @var \App\eZ\Platform\API\Repository\Values\Content\ContentUpdateStruct */
    protected $contentUpdateStruct;

    /** @var \App\eZ\Platform\API\Repository\Values\ContentType\ContentType */
    protected $contentType;
}
