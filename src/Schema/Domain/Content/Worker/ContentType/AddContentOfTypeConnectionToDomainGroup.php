<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Schema\Domain\Content\Worker\ContentType;

use App\Schema\Domain\Content\Worker\BaseWorker;
use App\Schema\Worker;
use App\Schema\Builder;
use App\Schema\Builder\Input;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentType;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentTypeGroup;

class AddContentOfTypeConnectionToDomainGroup extends BaseWorker implements Worker
{
    public function work(Builder $schema, array $args)
    {
        $contentType = $args['ContentType'];
        $descriptions = $contentType->getDescriptions();

        $schema->addFieldToType($this->groupName($args), new Input\Field(
            $this->connectionField($args),
            $this->connectionType($args),
            [
                'description' => isset($descriptions['eng-GB']) ? $descriptions['eng-GB'] : 'No description available',
                'resolve' => sprintf(
                    '@=resolver("SearchContentOfTypeAsConnection", ["%s", args])',
                    $contentType->identifier
                ),
                'argsBuilder' => 'Relay::Connection',
            ]
        ));

        $schema->addArgToField($this->groupName($args), $this->connectionField($args), new Input\Arg(
            'query', 'ContentSearchQuery',
            ['description' => 'A Content query used to filter results']
        ));

        $schema->addArgToField($this->groupName($args), $this->connectionField($args), new Input\Arg(
            'sortBy', '[SortByOptions]',
            ['description' => 'A sort clause, or array of clauses. Add _desc after a clause to reverse it']
        ));
    }

    public function canWork(Builder $schema, array $args)
    {
        return
            isset($args['ContentType'])
            && $args['ContentType'] instanceof ContentType
            && isset($args['ContentTypeGroup'])
            && $args['ContentTypeGroup'] instanceof ContentTypeGroup
            && !$schema->hasTypeWithField($this->groupName($args), $this->connectionField($args));
    }

    protected function groupName(array $args): string
    {
        return $this->getNameHelper()->domainGroupName($args['ContentTypeGroup']);
    }

    protected function connectionField(array $args): string
    {
        return $this->getNameHelper()->domainContentCollectionField($args['ContentType']);
    }

    protected function connectionType(array $args): string
    {
        return $this->getNameHelper()->domainContentConnection($args['ContentType']);
    }

    protected function typeName($args): string
    {
        return $this->getNameHelper()->domainContentName($args['ContentType']);
    }
}
