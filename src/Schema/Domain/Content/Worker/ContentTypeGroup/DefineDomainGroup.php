<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Schema\Domain\Content\Worker\ContentTypeGroup;

use App\eZ\Platform\API\Repository\ContentTypeService;
use App\Schema\Domain\Content\Worker\BaseWorker;
use App\Schema\Worker;
use App\Schema\Builder\Input;
use App\Schema\Builder;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroup;

class DefineDomainGroup extends BaseWorker implements Worker
{
    /**
     * @var ContentTypeService
     */
    private $contentTypeService;

    public function __construct(ContentTypeService $contentTypeService)
    {
        $this->contentTypeService = $contentTypeService;
    }

    public function work(Builder $schema, array $args)
    {
        $schema->addType(new Input\Type(
            $this->typeName($args),
            'object',
            ['inherits' => 'DomainContentTypeGroup']
        ));

        $schema->addFieldToType(
            $this->typeName($args),
            new Input\Field(
                '_types',
                $this->groupTypesName($args),
                ['resolve' => []]
            )
        );
    }

    public function canWork(Builder $schema, array $args)
    {
        return
            isset($args['ContentTypeGroup'])
            && $args['ContentTypeGroup'] instanceof ContentTypeGroup
            && !$schema->hasType($this->typeName($args))
            && !empty($this->contentTypeService->loadContentTypes($args['ContentTypeGroup']));
    }

    protected function typeName($args): string
    {
        return $this->getNameHelper()->domainGroupName($args['ContentTypeGroup']);
    }

    private function groupTypesName(array $args): string
    {
        return $this->getNameHelper()->domainGroupTypesName($args['ContentTypeGroup']);
    }
}
