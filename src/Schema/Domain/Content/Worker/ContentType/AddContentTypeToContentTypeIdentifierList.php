<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Schema\Domain\Content\Worker\ContentType;

use App\Schema\Domain\Content\Worker\BaseWorker;
use App\Schema\Initializer;
use App\Schema\Worker;
use App\Schema\Builder\Input;
use App\Schema\Builder;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentType;

/**
 * Adds a content type to the content type identifiers list (ContentTypeIdentifier).
 */
class AddContentTypeToContentTypeIdentifierList extends BaseWorker implements Worker, Initializer
{
    const TYPE = 'ContentTypeIdentifier';

    public function work(Builder $schema, array $args)
    {
        $contentType = $args['ContentType'];

        $descriptions = $contentType->getDescriptions();
        $description = isset($descriptions['eng-GB']) ? $descriptions['eng-GB'] : 'No description available';

        $schema->addValueToEnum(
            self::TYPE,
            new Input\EnumValue(
                $contentType->identifier,
                ['description' => $description]
            )
        );
    }

    public function init(Builder $schema)
    {
        $schema->addType(new Input\Type(self::TYPE, 'enum'));
    }

    public function canWork(Builder $schema, array $args)
    {
        $canWork =
            isset($args['ContentType'])
            && $args['ContentType'] instanceof ContentType
            && $schema->hasType(self::TYPE);

        return $canWork;
    }
}
