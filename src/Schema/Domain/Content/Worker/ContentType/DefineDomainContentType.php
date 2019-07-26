<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Schema\Domain\Content\Worker\ContentType;

use App\Schema\Domain\Content\Worker\BaseWorker;
use App\Schema\Worker;
use App\Schema\Builder\Input;
use App\Schema\Builder;
use App\eZ\Platform\API\Repository\Values\ContentType\ContentType;

class DefineDomainContentType extends BaseWorker implements Worker
{
    public function work(Builder $schema, array $args)
    {
        $schema->addType(new Input\Type(
            $this->typeName($args), 'object',
            [
                'inherits' => ['BaseDomainContentType'],
                'interfaces' => ['DomainContentType'],
            ]
        ));
    }

    public function canWork(Builder $schema, array $args)
    {
        return
            isset($args['ContentType'])
            && $args['ContentType'] instanceof ContentType
            && !$schema->hasType($this->typeName($args));
    }

    protected function typeName(array $args): string
    {
        return $this->getNameHelper()->domainContentTypeName($args['ContentType']);
    }
}
