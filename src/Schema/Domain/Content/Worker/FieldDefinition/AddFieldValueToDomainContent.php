<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Schema\Domain\Content\Worker\FieldDefinition;

use App\Schema\Domain\Content\Mapper\FieldDefinition\FieldDefinitionMapper;
use App\Schema\Domain\Content\Worker\BaseWorker;
use App\Schema\Worker;
use App\Schema\Builder;
use App\Schema\Builder\Input;
use eZ\Publish\API\Repository\Values\ContentType\ContentType;
use eZ\Publish\API\Repository\Values\ContentType\FieldDefinition;

class AddFieldValueToDomainContent extends BaseWorker implements Worker
{
    /**
     * @var \App\Schema\Domain\Content\Mapper\FieldDefinition\FieldDefinitionMapper
     */
    private $fieldDefinitionMapper;

    public function __construct(FieldDefinitionMapper $fieldDefinitionMapper)
    {
        $this->fieldDefinitionMapper = $fieldDefinitionMapper;
    }

    public function work(Builder $schema, array $args)
    {
        $definition = $this->getDefinition($args['FieldDefinition']);
        $schema->addFieldToType(
            $this->typeName($args),
            new Input\Field($this->fieldName($args), $definition['type'], $definition)
        );
    }

    private function getDefinition(FieldDefinition $fieldDefinition)
    {
        return [
            'type' => $this->fieldDefinitionMapper->mapToFieldValueType($fieldDefinition),
            'resolve' => $this->fieldDefinitionMapper->mapToFieldValueResolver($fieldDefinition),
        ];
    }

    public function canWork(Builder $schema, array $args)
    {
        return
            isset($args['FieldDefinition'])
            && $args['FieldDefinition'] instanceof FieldDefinition
            & isset($args['ContentType'])
            && $args['ContentType'] instanceof ContentType
            && !$schema->hasTypeWithField($this->typeName($args), $this->fieldName($args));
    }

    protected function typeName(array $args): string
    {
        return $this->getNameHelper()->domainContentName($args['ContentType']);
    }

    protected function fieldName($args): string
    {
        return $this->getNameHelper()->fieldDefinitionField($args['FieldDefinition']);
    }
}
