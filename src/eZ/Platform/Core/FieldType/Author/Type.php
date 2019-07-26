<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\FieldType\Author;

use App\eZ\Platform\API\Repository\FieldType;
use App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition;
use App\eZ\Platform\Core\FieldType\Value as FieldTypeValue;

class Type implements FieldType
{
    const DEFAULT_VALUE_EMPTY = 0;

    protected $settingsSchema = [
        'defaultAuthor' => [
            'type' => 'choice',
            'default' => self::DEFAULT_VALUE_EMPTY,
        ],
    ];

    public function getFieldTypeIdentifier()
    {
        return 'ezauthor';
    }

    public function getName($value)
    {
        return isset($value->authors[0]) ? $value->authors[0]->name : '';
    }

    public function getSettingsSchema()
    {
    }


    public function getValidatorConfigurationSchema()
    {
        return $this->validatorConfigurationSchema;
    }

    /**
     * Indicates if the field type supports indexing and sort keys for searching.
     *
     * @return bool
     */
    public function isSearchable()
    {
        return false;
    }

    /**
     * Indicates if the field definition of this type can appear only once in the same ContentType.
     *
     * @return bool
     */
    public function isSingular()
    {
        return false;
    }

    /**
     * Indicates if the field definition of this type can be added to a ContentType with Content instances.
     *
     * @return bool
     */
    public function onlyEmptyInstance()
    {
        return false;
    }

    /**
     * Returns the empty value for this field type.
     *
     * @return mixed
     */
    public function getEmptyValue()
    {
        return new Value();
    }

    public function isEmptyValue($value)
    {
        return $value instanceof Value && $value->text === null || trim($value->text) === '';
    }

    public function fromHash($hash)
    {
        if ($hash === null) {
            return $this->getEmptyValue();
        }

        return new Value($hash);
    }

    public function toHash($value)
    {
        if ($this->isEmptyValue($value)) {
            return null;
        }

        return $value->text;
    }

    /**
     * Converts the given $fieldSettings to a simple hash format.
     *
     * @param mixed $fieldSettings
     *
     * @return array|hash|scalar|null
     */
    public function fieldSettingsToHash($fieldSettings)
    {
        // TODO: Implement fieldSettingsToHash() method.
    }

    /**
     * Converts the given $fieldSettingsHash to field settings of the type.
     *
     * This is the reverse operation of {@link fieldSettingsToHash()}.
     *
     * @param array|hash|scalar|null $fieldSettingsHash
     *
     * @return mixed
     */
    public function fieldSettingsFromHash($fieldSettingsHash)
    {
        // TODO: Implement fieldSettingsFromHash() method.
    }

    /**
     * Converts the given $validatorConfiguration to a simple hash format.
     *
     * @param mixed $validatorConfiguration
     *
     * @return array|hash|scalar|null
     */
    public function validatorConfigurationToHash($validatorConfiguration)
    {
        // TODO: Implement validatorConfigurationToHash() method.
    }

    /**
     * Converts the given $validatorConfigurationHash to a validator
     * configuration of the type.
     *
     * @param array|hash|scalar|null $validatorConfigurationHash
     *
     * @return mixed
     */
    public function validatorConfigurationFromHash($validatorConfigurationHash)
    {
        // TODO: Implement validatorConfigurationFromHash() method.
    }

    /**
     * Validates the validatorConfiguration of a FieldDefinitionCreateStruct or FieldDefinitionUpdateStruct.
     *
     * This methods determines if the given $validatorConfiguration is
     * structurally correct and complies to the validator configuration schema as defined in FieldType.
     *
     * @param mixed $validatorConfiguration
     *
     * @return \eZ\Publish\SPI\FieldType\ValidationError[]
     */
    public function validateValidatorConfiguration($validatorConfiguration)
    {
        // TODO: Implement validateValidatorConfiguration() method.
    }

    /**
     * Validates the fieldSettings of a FieldDefinitionCreateStruct or FieldDefinitionUpdateStruct.
     *
     * This methods determines if the given $fieldSettings are structurally
     * correct and comply to the settings schema as defined in FieldType.
     *
     * @param mixed $fieldSettings
     *
     * @return \eZ\Publish\SPI\FieldType\ValidationError[]
     */
    public function validateFieldSettings($fieldSettings)
    {
        // TODO: Implement validateFieldSettings() method.
    }

    public function validateValue(FieldDefinition $fieldDef, FieldTypeValue $value)
    {
        // TODO: Implement validateValue() method.
    }
}