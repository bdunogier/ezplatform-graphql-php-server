<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\DataLoader;

use App\GraphQL\DataLoader\Exception\NotFoundException;
use App\eZ\Platform\API\Repository\Exceptions as ApiException;
use App\eZ\Platform\API\Repository\SearchService;
use App\eZ\Platform\API\Repository\Values\Content\Content;
use App\eZ\Platform\API\Repository\Values\Content\Query;
use App\eZ\Platform\API\Repository\Values\Content\Search\SearchHit;
use App\eZ\Platform\API\Repository\Values\Content\Query\Criterion;

/**
 * @internal
 */
class SearchContentLoader implements ContentLoader
{
    /**
     * @var SearchService
     */
    private $searchService;

    public function __construct(SearchService $searchService)
    {
        $this->searchService = $searchService;
    }

    /**
     * Loads a list of content items given a Query Criterion.
     *
     * @param Query $query A Query Criterion. To use multiple criteria, group them with a LogicalAnd.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content[]
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException
     */
    public function find(Query $query): array
    {
        return array_map(
            function (SearchHit $searchHit) {
                return $searchHit->valueObject;
            },
            $this->searchService->findContent($query)->searchHits
        );
    }

    /**
     * Loads a single content item given a Query Criterion.
     *
     * @param Criterion $filter A Query Criterion. Use Criterion\ContentId, Criterion\RemoteId or Criterion\LocationId for basic loading.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content
     *
     * @throws NotFoundException
     */
    public function findSingle(Criterion $filter): Content
    {
        try {
            return $this->searchService->findSingle($filter);
        } catch (ApiException\InvalidArgumentException $e) {
        } catch (ApiException\NotFoundException $e) {
            throw new NotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }

    /**
     * Counts the results of a query.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Query $query
     *
     * @return int
     *
     * @throws NotFoundException
     */
    public function count(Query $query)
    {
        $countQuery = clone $query;
        $countQuery->limit = 0;
        $countQuery->offset = 0;

        try {
            return $this->searchService->findContent($countQuery)->totalCount;
        } catch (ApiException\InvalidArgumentException $e) {
            throw new NotFoundException($e->getMessage(), $e->getCode(), $e);
        }
    }
}
