<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\DataLoader;

use App\eZ\Platform\API\Repository\Values\Content\Content;
use App\eZ\Platform\API\Repository\Values\Content\Query;
use App\eZ\Platform\API\Repository\Values\Content\Query\Criterion;

/**
 * @internal
 */
interface ContentLoader
{
    /**
     * Loads a list of content items given a Query Criterion.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Query $query
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content[]
     */
    public function find(Query $query): array;

    /**
     * Loads a single content item given a Query Criterion.
     *
     * @param Criterion $criterion A Query Criterion.
     *        Use Criterion\ContentId, Criterion\RemoteId or Criterion\LocationId for basic loading.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content
     */
    public function findSingle(Criterion $criterion): Content;

    /**
     * Counts the results of a query.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Query $query
     *
     * @return int
     */
    public function count(Query $query);
}
