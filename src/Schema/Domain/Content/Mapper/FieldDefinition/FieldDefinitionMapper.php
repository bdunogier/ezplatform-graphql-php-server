<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Schema\Domain\Content\Mapper\FieldDefinition;

use App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition;

/**
 * Maps a Field Definition to its GraphQL components.
 */
interface FieldDefinitionMapper
{
    public function mapToFieldDefinitionType(FieldDefinition $fieldDefinition): ?string;

    public function mapToFieldValueType(FieldDefinition $fieldDefinition): ?string;

    public function mapToFieldValueResolver(FieldDefinition $fieldDefinition): ?string;
}
