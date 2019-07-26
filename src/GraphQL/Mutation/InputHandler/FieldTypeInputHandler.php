<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Mutation\InputHandler;

use App\eZ\Platform\Core\FieldType\Value as FieldValue;

interface FieldTypeInputHandler
{
    public function toFieldValue($input, $inputFormat = null): FieldValue;
}
