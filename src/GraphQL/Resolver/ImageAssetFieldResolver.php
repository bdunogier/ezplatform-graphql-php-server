<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Resolver;

use App\eZ\Platform\API\Repository\Values\Content\Query\Criterion;
use App\eZ\Platform\Core\FieldType\ImageAsset\AssetMapper;
use App\GraphQL\DataLoader\ContentLoader;
use App\GraphQL\Value\Field;

/**
 * @internal
 */
class ImageAssetFieldResolver
{
    /**
     * @var DomainContentResolver
     */
    private $domainContentResolver;
    /**
     * @var ContentLoader
     */
    private $contentLoader;
    /**
     * @var AssetMapper
     */
    private $assetMapper;

    public function __construct(ContentLoader $contentLoader, DomainContentResolver $domainContentResolver, AssetMapper $assetMapper)
    {
        $this->domainContentResolver = $domainContentResolver;
        $this->contentLoader = $contentLoader;
        $this->assetMapper = $assetMapper;
    }

    public function resolveDomainImageAssetFieldValue(Field $field)
    {
        $assetField = $this->assetMapper->getAssetField(
            $this->contentLoader->findSingle(new Criterion\ContentId($field->value->destinationContentId))
        );

        if (empty($assetField->value->alternativeText)) {
            $assetField->value->alternativeText = $field->value->alternativeText;
        }

        return Field::fromField($assetField);
    }
}
