<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Schema\Domain\Content\Worker\ContentTypeGroup;

use App\Schema\Domain\Content\Worker\BaseWorker;
use App\Schema\Worker;
use App\Schema\Builder\Input;
use App\Schema\Builder;
use App\eZ\Platform\API\Repository\ContentTypeService;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroup;

final class AddDomainGroupToDomain extends BaseWorker implements Worker
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
        $contentTypeGroup = $args['ContentTypeGroup'];
        $schema->addFieldToType('Domain', new Input\Field(
            $this->fieldName($args),
            $this->typeGroupName($args),
            [
                'description' => $contentTypeGroup->getDescription('eng-GB'),
                'resolve' => sprintf(
                    '@=resolver("ContentTypeGroupByIdentifier", ["%s"])',
                    $contentTypeGroup->identifier
                ),
            ]
        ));
    }

    public function canWork(Builder $schema, array $args)
    {
        return
            isset($args['ContentTypeGroup'])
            && $args['ContentTypeGroup'] instanceof ContentTypeGroup
            && !$schema->hasTypeWithField('Domain', $this->fieldName($args))
            && !empty($this->contentTypeService->loadContentTypes($args['ContentTypeGroup']));
    }

    private function fieldName($args): string
    {
        return $this->getNameHelper()->domainGroupField($args['ContentTypeGroup']);
    }

    private function typeGroupName($args): string
    {
        return $this->getNameHelper()->domainGroupName($args['ContentTypeGroup']);
    }
}
