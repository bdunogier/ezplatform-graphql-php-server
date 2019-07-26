<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\DependencyInjection\Factory;

use App\eZ\Platform\API\Repository\FieldTypeService;
use App\eZ\Platform\Core\FieldType\Generic\Type as GenericType;

class InputHandlerFactory
{
    /**
     * @var \App\eZ\Platform\API\Repository\FieldTypeService
     */
    private $fieldTypeService;

    public function __construct(FieldTypeService $fieldTypeService)
    {
        $this->fieldTypeService = $fieldTypeService;
    }

    public function createFieldTypeService($typeIdentifier, $typeClass = GenericType::class)
    {
        return new $typeClass($this->fieldTypeService->getFieldType($typeIdentifier));

    }
}