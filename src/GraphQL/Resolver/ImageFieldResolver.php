<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Resolver;

use App\eZ\Platform\API\Repository\ContentService;
use App\eZ\Platform\API\Repository\FieldTypeService;
use App\eZ\Platform\API\Repository\Values\Content\Query\Criterion;
use App\eZ\Platform\Core\FieldType;
use App\eZ\Platform\Core\FieldType\Image\VariationHandler;
use App\GraphQL\DataLoader\ContentLoader;
use Overblog\GraphQLBundle\Error\UserError;
use App\eZ\Platform\Core\FieldType\Generic\Value as ImageFieldValue;

/**
 * @internal
 */
class ImageFieldResolver
{
    /**
     * @var \App\eZ\Platform\Core\FieldType\Image\VariationHandler
     */
    private $variationHandler;

    /**
     * @var \App\eZ\Platform\API\Repository\ContentService
     */
    private $contentService;

    /**
     * @var array
     */
    private $variations;

    /**
     * @var FieldType\Image\Type
     */
    private $fieldType;

    /**
     * @var ContentLoader
     */
    private $contentLoader;

    public function __construct(
        FieldTypeService $fieldTypeService,
        VariationHandler $variationHandler,
        ContentLoader $contentLoader,
        ContentService $contentService,
        array $variations
    ) {
        $this->variationHandler = $variationHandler;
        $this->contentService = $contentService;
        $this->variations = $variations;
        $this->fieldType = $fieldTypeService->getFieldType('ezimage');
        $this->contentLoader = $contentLoader;
    }

    public function resolveImageVariations(ImageFieldValue $fieldValue, $args)
    {
        if ($this->fieldType->isEmptyValue($fieldValue)) {
            return null;
        }
        list($content, $field) = $this->getImageField($fieldValue);

        $variations = [];
        foreach ($args['identifier'] as $identifier) {
            $variations[] = $this->variationHandler->getVariation($field, $content->versionInfo, $identifier);
        }

        return $variations;
    }

    public function resolveImageVariation(ImageFieldValue $fieldValue, $args)
    {
        if ($this->fieldType->isEmptyValue($fieldValue)) {
            return null;
        }

        list($content, $field) = $this->getImageField($fieldValue);
        $versionInfo = $this->contentService->loadVersionInfo($content->contentInfo);

        return $this->variationHandler->getVariation($field, $versionInfo, $args['identifier']);
    }

    /**
     * @param ImageFieldValue $fieldValue
     *
     * @return [Content, Field]
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException
     */
    protected function getImageField(ImageFieldValue $fieldValue): array
    {
        list($contentId, $fieldId) = $this->decomposeImageId($fieldValue);

        $content = $this->contentLoader->findSingle(new Criterion\ContentId($contentId));

        $fieldFound = false;
        /** @var $field \App\eZ\Platform\API\Repository\Values\Content\Field */
        foreach ($content->getFields() as $field) {
            if ($field->id == $fieldId) {
                $fieldFound = true;
                break;
            }
        }

        if (!$fieldFound) {
            throw new UserError("No image field with ID $fieldId could be found");
        }

        // check the field's value
        if ($field->value->uri === null) {
            throw new UserError("Image file {$field->value->id} doesn't exist");
        }

        return [$content, $field];
    }

    /**
     * @param ImageFieldValue $fieldValue
     *
     * @return array
     */
    protected function decomposeImageId(ImageFieldValue $fieldValue): array
    {
        $idArray = explode('-', $fieldValue->imageId);
        if (count($idArray) != 3) {
            throw new UserError("Invalid image ID {$fieldValue->imageId}");
        }

        return $idArray;
    }
}
