<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Mutation\InputHandler\FieldType;

use App\eZ\Platform\API\Repository\FieldType;
use App\eZ\Platform\Core\FieldType\Value as FieldValue;
use App\GraphQL\Mutation\InputHandler\FieldTypeInputHandler;

/**
 * Converts input to a Field Value using the type's fromHash method.
 */
class FromHash implements FieldTypeInputHandler
{
    /**
     * @var FieldType
     */
    private $fieldType;

    public function __construct(FieldType $fieldType)
    {
        $this->fieldType = $fieldType;
    }

    public function toFieldValue($input, $inputFormat = null): FieldValue
    {
        return $this->fieldType->fromHash($input);
    }
}
