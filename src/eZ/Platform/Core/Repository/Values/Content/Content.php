<?php

/**
 * File containing the Content class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values\Content;

use App\eZ\Platform\API\Repository\ContentService;
use App\eZ\Platform\API\Repository\Values\Content\Content as APIContent;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentType;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\Values\Content\Content}
 * class.
 *
 * @see \App\eZ\Platform\API\Repository\Values\Content\Content
 * @property-read \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo convenience getter for $versionInfo->contentInfo
 * @property-read \App\eZ\Platform\API\Repository\Values\ContentType\ContentType $contentType convenience getter for $versionInfo->contentInfo->contentType
 * @property-read mixed $id convenience getter for retrieving the contentId: $versionInfo->content->id
 * @property-read \App\eZ\Platform\API\Repository\Values\Content\VersionInfo $versionInfo calls getVersionInfo()
 * @property-read \App\eZ\Platform\API\Repository\Values\Content\Field[] $fields access fields, calls getFields()
 *
 * @todo Implement convenience property access!
 */
class Content extends APIContent
{
    /** @var \App\eZ\Platform\API\Repository\Values\Content\Field[][] Array of array of field values like[$fieldDefIdentifier][$languageCode] */
    protected $fields;

    /** @var \App\eZ\Platform\API\Repository\Values\Content\VersionInfo */
    protected $versionInfo;

    /** @var \App\eZ\Platform\API\Repository\Values\Content\Field[] */
    private $internalFields;

    /** @var \App\eZ\Platform\Core\Repository\ContentService */
    protected $contentService;

    /**
     * Creates a new struct from the given $data array.
     *
     * @param ContentService $contentService
     * @param array $data
     */
    public function __construct(ContentService $contentService, array $data = array())
    {
        $this->contentService = $contentService;
        foreach ($data as $propertyName => $propertyValue) {
            $this->$propertyName = $propertyValue;
        }
        foreach ($this->internalFields as $field) {
            $this->fields[$field->fieldDefIdentifier][$field->languageCode] = $field;
        }
    }

    public function __get($property)
    {
        if ($property === 'id') {
            return $this->getVersionInfo()->getContentInfo()->id;
        } else if ($property === 'contentInfo') {
            return $this->getVersionInfo()->getContentInfo();
        }
    }

    /**
     * Returns the VersionInfo for this version.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\VersionInfo
     */
    public function getVersionInfo()
    {
        return $this->versionInfo;
    }

    /**
     * Returns a field value for the given value
     * $version->fields[$fieldDefId][$languageCode] is an equivalent call
     * if no language is given on a translatable field this method returns
     * the value of the initial language of the version if present, otherwise null.
     * On non translatable fields this method ignores the languageCode parameter.
     *
     * @param string $fieldDefIdentifier
     * @param string $languageCode
     *
     * @return mixed a primitive type or a field type Value object depending on the field type.
     */
    public function getFieldValue($fieldDefIdentifier, $languageCode = null)
    {
        if (null === $languageCode) {
            $languageCode = $this->versionInfo->contentInfo->mainLanguageCode;
        }

        if (isset($this->fields[$fieldDefIdentifier][$languageCode])) {
            return $this->fields[$fieldDefIdentifier][$languageCode]->value;
        }

        return null;
    }

    /**
     * This method returns the complete fields collection.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Field[] An array of {@link Field}
     */
    public function getFields()
    {
        return $this->internalFields;
    }

    /**
     * This method returns the fields for a given language and non translatable fields.
     *
     * If not set the initialLanguage of the content version is used.
     *
     * @param string $languageCode
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Field[] An array of {@link Field} with field identifier as keys
     */
    public function getFieldsByLanguage($languageCode = null)
    {
        $fields = array();

        if (null === $languageCode) {
            $languageCode = $this->versionInfo->contentInfo->mainLanguageCode;
        }

        foreach ($this->getFields() as $field) {
            if ($field->languageCode === $languageCode) {
                $fields[$field->fieldDefIdentifier] = $field;
            }
        }

        return $fields;
    }

    /**
     * This method returns the field for a given field definition identifier and language.
     *
     * If not set the initialLanguage of the content version is used.
     *
     * @param string $fieldDefIdentifier
     * @param string|null $languageCode
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Field|null A {@link Field} or null if nothing is found
     */
    public function getField($fieldDefIdentifier, $languageCode = null)
    {
        if (null === $languageCode) {
            $languageCode = $this->versionInfo->contentInfo->mainLanguageCode;
        }

        if (isset($this->fields[$fieldDefIdentifier][$languageCode])) {
            return $this->fields[$fieldDefIdentifier][$languageCode];
        }

        return null;
    }

    /**
     * Returns the ContentType for this content.
     *
     * @return \App\eZ\Platform\API\Repository\Values\ContentType\ContentType
     */
    public function getContentType(): ContentType
    {
        // TODO: Implement getContentType() method.
    }
}
