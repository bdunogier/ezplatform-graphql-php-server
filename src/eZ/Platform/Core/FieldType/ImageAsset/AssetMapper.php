<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\FieldType\ImageAsset;

use App\eZ\Platform\API\Repository\Values\Content\Content;
use App\eZ\Platform\API\Repository\Values\Content\Field;
use App\eZ\Platform\Core\Repository\Exceptions\InvalidArgumentException;

class AssetMapper
{
    private $mappings = [
        'content_field_identifier' => 'image',
    ];

    public function getAssetField(Content $content): Field
    {
        if (!$this->isAsset($content)) {
            throw new InvalidArgumentException('contentId', "Content {$content->id} is not a image asset!");
        }

        return $content->getField($this->mappings['content_field_identifier']);
    }

}