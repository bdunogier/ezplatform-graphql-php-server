<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Schema\Domain\Content\Worker\FieldDefinition;

use App\eZ\Platform\API\Repository\Values\ContentType\ContentType;
use App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition;
use App\Schema\Builder;
use App\Schema\Domain\Content\Worker\BaseWorker;
use App\Schema\Worker;

class AddFieldDefinitionToDomainContentMutation extends BaseWorker implements Worker
{
    const OPERATION_CREATE = 'create';
    const OPERATION_UPDATE = 'update';

    /**
     * Mapping of fieldtypes identifiers to their GraphQL input type.
     *
     * @var string[]
     */
    private $typesInputMap;

    public function __construct($typesInputMap = [])
    {
        $this->typesInputMap = $typesInputMap;
    }

    public function work(Builder $schema, array $args)
    {
        $fieldDefinition = $args['FieldDefinition'];
        $contentType = $args['ContentType'];

        $fieldDefinitionField = $this->getFieldDefinitionField($fieldDefinition);

        $schema->addFieldToType(
            $this->getCreateInputName($contentType),
            new Builder\Input\Field(
                $fieldDefinitionField,
                $this->getFieldDefinitionToGraphQLType($args['FieldDefinition'], self::OPERATION_CREATE),
                ['description' => $fieldDefinition->getDescriptions()['eng-GB'] ?? '']
            )
        );

        $schema->addFieldToType(
            $this->getUpdateInputName($contentType),
            new Builder\Input\Field(
                $fieldDefinitionField,
                $this->getFieldDefinitionToGraphQLType($args['FieldDefinition'], self::OPERATION_UPDATE),
                ['description' => $fieldDefinition->getDescriptions()['eng-GB'] ?? '']
            )
        );
    }

    public function canWork(Builder $schema, array $args): bool
    {
        return
            isset($args['ContentType'])
            && $args['ContentType'] instanceof ContentType
            && isset($args['FieldDefinition'])
            && $args['FieldDefinition'] instanceof FieldDefinition
            && !$schema->hasTypeWithField($this->getCreateInputName($args['ContentType']), $this->getFieldDefinitionField($args['FieldDefinition']));
    }

    /**
     * @param ContentType $contentType
     *
     * @return string
     */
    protected function getCreateInputName(ContentType $contentType): string
    {
        return $this->getNameHelper()->domainContentCreateInputName($contentType);
    }

    /**
     * @param ContentType $contentType
     *
     * @return string
     */
    private function getUpdateInputName($contentType): string
    {
        return $this->getNameHelper()->domainContentUpdateInputName($contentType);
    }

    /**
     * @param FieldDefinition $fieldDefinition
     *
     * @return string
     */
    protected function getFieldDefinitionField(FieldDefinition $fieldDefinition): string
    {
        return $this->getNameHelper()->fieldDefinitionField($fieldDefinition);
    }

    private function getFieldDefinitionToGraphQLType(FieldDefinition $fieldDefinition, $operation): string
    {
        $requiredFlag = $operation == self::OPERATION_CREATE && $fieldDefinition->isRequired ? '!' : '';

        return isset($this->typesInputMap[$fieldDefinition->fieldTypeIdentifier])
            ? ($this->typesInputMap[$fieldDefinition->fieldTypeIdentifier] . $requiredFlag)
            : ('String' . $requiredFlag);
    }
}
