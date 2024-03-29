<?php

/**
 * File containing the ContentTypeCreateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values\ContentType;

use App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinitionCreateStruct;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeCreateStruct as APIContentTypeCreateStruct;

class ContentTypeCreateStruct extends APIContentTypeCreateStruct
{
    protected $fieldDefinitions = array();

    public function __construct(array $data = array())
    {
        foreach ($data as $propertyName => $propertyValue) {
            $this->$propertyName = $propertyValue;
        }
    }

    /**
     * Adds a new field definition.
     *
     * @param FieldDefinitionCreate $fieldDef
     */
    public function addFieldDefinition(FieldDefinitionCreateStruct $fieldDef)
    {
        $this->fieldDefinitions[] = $fieldDef;
    }
}
