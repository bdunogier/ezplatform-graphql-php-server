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
use App\eZ\Platform\API\Repository\Values\ContentType\ContentType;
use App\eZ\Platform\API\Repository\Values\ContentType\FieldDefinition;
use eZ\Publish\SPI\Repository\Values\MultiLanguageDescription;

class AddFieldDefinitionToDomainContentType extends BaseWorker implements Worker
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
        $schema->addFieldToType($this->typeName($args), new Input\Field(
            $this->fieldName($args),
            $this->fieldType($args),
            [
                'description' => $this->fieldDescription($args),
                'resolve' => sprintf(
                    '@=value.getFieldDefinition("%s")',
                    $args['FieldDefinition']->identifier
                ),
            ]
        ));
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

    /**
     * @param array $args
     *
     * @return string
     */
    protected function typeName(array $args): string
    {
        return $this->getNameHelper()->domainContentTypeName($args['ContentType']);
    }

    protected function fieldName($args): string
    {
        return $this->getNameHelper()->fieldDefinitionField($args['FieldDefinition']);
    }

    public function fieldDescription($args)
    {
        $description = '';
        if ($args['FieldDefinition'] instanceof MultiLanguageDescription) {
            $description = $args['FieldDefinition']->getDescription('eng-GB') ?? '';
        }

        return $description;
    }

    private function fieldType($args)
    {
        return $this->fieldDefinitionMapper->mapToFieldDefinitionType($args['FieldDefinition']);
    }
}
