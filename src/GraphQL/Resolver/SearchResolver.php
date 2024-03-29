<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Resolver;

use App\GraphQL\DataLoader\ContentLoader;
use App\GraphQL\InputMapper\SearchQueryMapper;
use App\eZ\Platform\API\Repository\SearchService;
use Overblog\GraphQLBundle\Relay\Connection\Paginator;

/**
 * @internal
 */
class SearchResolver
{
    /**
     * @var SearchService
     */
    private $searchService;

    /**
     * @var SearchQueryMapper
     */
    private $queryMapper;

    /**
     * @var ContentLoader
     */
    private $contentLoader;

    public function __construct(ContentLoader $contentLoader, SearchService $searchService, SearchQueryMapper $queryMapper)
    {
        $this->contentLoader = $contentLoader;
        $this->searchService = $searchService;
        $this->queryMapper = $queryMapper;
    }

    public function searchContent($args)
    {
        return $this->contentLoader->find(
            $this->queryMapper->mapInputToQuery($args['query'])
        );
    }

    public function searchContentOfTypeAsConnection($contentTypeIdentifier, $args)
    {
        $query = $args['query'] ?: [];
        $query['ContentTypeIdentifier'] = $contentTypeIdentifier;
        $query['sortBy'] = $args['sortBy'];
        $query = $this->queryMapper->mapInputToQuery($query);

        $paginator = new Paginator(function ($offset, $limit) use ($query) {
            $query->offset = $offset;
            $query->limit = $limit ?? 10;

            return $this->contentLoader->find($query);
        });

        return $paginator->auto(
            $args,
            function () use ($query) {
                return $this->contentLoader->count($query);
            }
        );
    }
}
