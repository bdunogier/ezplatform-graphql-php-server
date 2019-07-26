<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\FieldType\Generic;

use App\eZ\Platform\API\Repository\FieldType;
use App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition;
use App\eZ\Platform\Core\FieldType\Value as CoreValue;

class Type implements FieldType
{
    private $fieldTypeIdentifier;

    public function __construct($fieldTypeIdentifier)
    {
        $this->fieldTypeIdentifier = $fieldTypeIdentifier;
    }

    public function getFieldTypeIdentifier()
    {
        return $this->fieldTypeIdentifier;
    }

    public function getName($value)
    {
        return (string)$value;
    }

    public function getSettingsSchema()
    {
        return [];
    }

    public function getValidatorConfigurationSchema()
    {
        return [];
    }

    public function isSearchable()
    {
        // TODO: Implement isSearchable() method.
    }

    public function isSingular()
    {
        return false;
    }

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
        // TODO: Implement getEmptyValue() method.
    }

    public function isEmptyValue($value)
    {
        // TODO: Implement isEmptyValue() method.
    }

    public function fromHash($hash)
    {
        return new Value($hash);
    }

    public function toHash($value)
    {
        if ($value instanceof Value) {
            return $value->toHash();
        }
    }

    public function fieldSettingsToHash($fieldSettings)
    {
        // TODO: Implement fieldSettingsToHash() method.
    }

    public function fieldSettingsFromHash($fieldSettingsHash)
    {
        return [];
    }

    public function validatorConfigurationToHash($validatorConfiguration)
    {
        return [];
    }

    public function validatorConfigurationFromHash($validatorConfigurationHash)
    {
        return [];
    }

    public function validateValidatorConfiguration($validatorConfiguration)
    {
    }

    public function validateFieldSettings($fieldSettings)
    {
    }

    public function validateValue(FieldDefinition $fieldDef, CoreValue $value)
    {
        // TODO: Implement validateValue() method.
    }
}