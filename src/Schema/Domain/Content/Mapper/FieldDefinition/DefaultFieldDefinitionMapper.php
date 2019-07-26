<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Schema\Domain\Content\Mapper\FieldDefinition;

use App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition;

class DefaultFieldDefinitionMapper implements FieldDefinitionMapper
{
    public function mapToFieldValueType(FieldDefinition $fieldDefinition): ?string
    {
        return 'String';
    }

    public function mapToFieldDefinitionType(FieldDefinition $fieldDefinition): ?string
    {
        return 'FieldDefinition';
    }

    public function mapToFieldValueResolver(FieldDefinition $fieldDefinition): ?string
    {
        return sprintf("@=value.getFieldValue('%s')", $fieldDefinition->identifier);
    }
}
