<?php

/**
 * File containing the FieldTypeService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\API\Repository\FieldTypeService as APIFieldTypeService;
use App\eZ\Platform\API\Repository\FieldType;
use App\eZ\Platform\Core\FieldType\Generic\Type as GenericType;
use App\eZ\Platform\Core\Repository\Exceptions;

class FieldTypeService implements APIFieldTypeService
{
    /**
     * FieldTypes by identifier.
     *
     * @var \App\eZ\Platform\Core\Repository\FieldType[]
     */
    protected $fieldTypes = array();

    /**
     * @param \App\eZ\Platform\Core\Repository\FieldType[] $fieldTypes
     */
    public function __construct(array $fieldTypes = array())
    {
        foreach ($fieldTypes as $fieldType) {
            $this->addFieldType($fieldType);
        }
    }

    /**
     * Adds the given $fieldType.
     *
     * Note, this is not an API method and not meant to be used directly!
     *
     * @param FieldType $fieldType
     */
    public function addFieldType(FieldType $fieldType)
    {
        $this->fieldTypes[$fieldType->getFieldTypeIdentifier()] = $fieldType;
    }

    /**
     * Returns a list of all field types.
     *
     * @return \App\eZ\Platform\API\Repository\FieldType[]
     */
    public function getFieldTypes()
    {
        return array_values($this->fieldTypes);
    }

    /**
     * Returns the FieldType registered with the given identifier.
     *
     * @param string $identifier
     *
     * @return \App\eZ\Platform\API\Repository\FieldType
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException
     *         if there is no FieldType registered with $identifier
     */
    public function getFieldType($identifier)
    {
        if (!$this->hasFieldType($identifier)) {
            $this->fieldTypes[$identifier] = new GenericType($identifier);
        }

        return $this->fieldTypes[$identifier];
    }

    /**
     * Returns if there is a FieldType registered under $identifier.
     *
     * @param string $identifier
     *
     * @return bool
     */
    public function hasFieldType($identifier)
    {
        return isset($this->fieldTypes[$identifier]);
    }
}
