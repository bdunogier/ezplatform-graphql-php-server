<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Resolver;

use App\eZ\Platform\API\Repository\Values\Content\Content;
use App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition;
use App\GraphQL\Value\Field;
use App\GraphQL\DataLoader\ContentTypeLoader;

/**
 * @internal
 */
class SelectionFieldResolver
{
    /**
     * @var DomainContentResolver
     */
    private $domainContentResolver;

    /**
     * @var ContentTypeLoader
     */
    private $contentTypeLoader;

    public function __construct(
        ContentTypeLoader $contentTypeLoader,
        DomainContentResolver $domainContentResolver
    ) {
        $this->contentTypeLoader = $contentTypeLoader;
        $this->domainContentResolver = $domainContentResolver;
    }

    public function resolveSelectionFieldValue(Field $field, Content $content)
    {
        $fieldDefinition = $this
            ->contentTypeLoader->load($content->contentInfo->contentTypeId)
            ->getFieldDefinition($field->fieldDefIdentifier);

        $options = $this->getOptions($content, $field, $fieldDefinition);

        if ($fieldDefinition->getFieldSettings()['isMultiple']) {
            $return = [];
            foreach ($field->value->selection as $selectedItemId) {
                $return[] = $options[$selectedItemId];
            }
        } else {
            reset($field->value->selection);
            $return = $options[current($field->value->selection)];
        }

        return $return;
    }

    /**
     * Returns the options set based on the language.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Content $content
     * @param \App\GraphQL\Value\Field $field
     * @param \App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition $fieldDefinition
     *
     * @return array
     */
    private function getOptions(Content $content, Field $field, FieldDefinition $fieldDefinition)
    {
        $fieldSettings = $fieldDefinition->getFieldSettings();

        if (isset($fieldSettings['multilingualOptions'])) {
            $multilingualOptions = $fieldSettings['multilingualOptions'];
            $mainLanguageCode = $content->contentInfo->mainLanguageCode;

            if (isset($multilingualOptions[$field->languageCode])) {
                return $multilingualOptions[$field->languageCode];
            } elseif (isset($multilingualOptions[$mainLanguageCode])) {
                return $multilingualOptions[$mainLanguageCode];
            }
        }

        return $fieldSettings['options'];
    }
}
