<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */


namespace App\eZ\Platform\Core\Repository;


use App\eZ\Platform\API\Repository\ContentTypeService as APIContentTypeService;
use App\eZ\Platform\API\Repository\SearchService as ServiceServiceApi;
use App\eZ\Platform\API\Repository\Values\Content\LocationQuery;
use App\eZ\Platform\API\Repository\Values\Content\Query;
use App\eZ\Platform\API\Repository\Values\Content\Query\Criterion;
use App\eZ\Platform\Core\Repository\Input\Dispatcher;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use App\eZ\Platform\Core\Repository\Values\ViewInput;
use App\eZ\Platform\Core\Repository\Values\View;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SearchService implements ServiceServiceApi
{
    /** @var \Symfony\Contracts\HttpClient\HttpClientInterface */
    private $client;

    /** @var \App\eZ\Platform\Core\Repository\Input\Dispatcher */
    private $inputDispatcher;

    /** @var \App\eZ\Platform\Core\Repository\Output\Visitor */
    private $outputVisitor;

    /** @var \App\eZ\Platform\Core\Repository\RequestParser */
    private $requestParser;

    public function __construct(HttpClientInterface $ezpRestClient, Dispatcher $inputDispatcher, Visitor $outputVisitor, RequestParser $requestParser, ApiContentTypeService $contentTypeService)
    {
        $this->client = $ezpRestClient;
        $this->inputDispatcher = $inputDispatcher;
        $this->outputVisitor = $outputVisitor;
        $this->requestParser = $requestParser;
    }

    /**
     * Finds content objects for the given query.
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Query $query
     * @param array $languageFilter Configuration for specifying prioritized languages query will be performed on.
     *        Also used to define which field languages are loaded for the returned content.
     *        Currently supports: <code>array("languages" => array(<language1>,..), "useAlwaysAvailable" => bool)</code>
     *                            useAlwaysAvailable defaults to true to avoid exceptions on missing translations
     * @param bool $filterOnUserPermissions if true only the objects which the user is allowed to read are returned.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Search\SearchResult
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if query is not valid
     *
     */
    public function findContent(Query $query, array $languageFilter = [], $filterOnUserPermissions = true)
    {
        // TODO: Implement findContent() method.
    }

    /**
     * Finds contentInfo objects for the given query.
     *
     * This method works just like findContent, however does not load the full Content Objects. This means
     * it can be more efficient for use cases where you don't need the full Content. Also including use cases
     * where content will be loaded by separate code, like an ESI based sub requests that takes content ID as input.
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Query $query
     * @param array $languageFilter Configuration for specifying prioritized languages query will be performed on.
     *        Currently supports: <code>array("languages" => array(<language1>,..), "useAlwaysAvailable" => bool)</code>
     *                            useAlwaysAvailable defaults to true to avoid exceptions on missing translations
     * @param bool $filterOnUserPermissions if true (default) only the objects which is the user allowed to read are returned.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Search\SearchResult
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if query is not valid
     *
     * @since 5.4.5
     */
    public function findContentInfo(Query $query, array $languageFilter = [], $filterOnUserPermissions = true)
    {
        // TODO: Implement findContentInfo() method.
    }

    /**
     * Performs a query for a single content object.
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Query\Criterion $filter
     * @param array $languageFilter Configuration for specifying prioritized languages query will be performed on.
     *        Currently supports: <code>array("languages" => array(<language1>,..), "useAlwaysAvailable" => bool)</code>
     *                            useAlwaysAvailable defaults to true to avoid exceptions on missing translations
     * @param bool $filterOnUserPermissions if true only the objects which is the user allowed to read are returned.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Content
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if criterion is not valid
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if there is more than than one result matching the criterions
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if the object was not found by the query or due to permissions
     */
    public function findSingle(Criterion $filter, array $languageFilter = [], $filterOnUserPermissions = true)
    {
        $view = new ViewInput([
            'identifier' => 'findSingleContent',
            'contentQuery' => new Query(['filter' => $filter]),
        ]);
        $body = $this->outputVisitor->visit($view)->getContent();
        $response = $this->client->request(
            'POST',
            $this->requestParser->generate('ezpublish_rest_views_create'),
            [
                'headers' => [
                    'Accept' => $this->outputVisitor->getMediaType('View'),
                    'Content-Type' => $this->outputVisitor->getMediaType('ViewInput') . '; version=1.1',
                ],
                'body' => $body
            ]
        );

        $headers = $response->getHeaders();
        $view = $this->inputDispatcher->parse($response);
        if ($view instanceof View) {
            return $view->result->searchHits[0]->valueObject;
        } else {
            throw new \Exception("TODO: Fix me");
        }
    }

    /**
     * Suggests a list of values for the given prefix.
     *
     * @param string $prefix
     * @param string[] $fieldPaths
     * @param int $limit
     * @param \eZ\Platform\API\Repository\Values\Content\Query\Criterion $filter
     */
    public function suggest($prefix, $fieldPaths = [], $limit = 10, Criterion $filter = null)
    {
        // TODO: Implement suggest() method.
    }

    /**
     * Finds Locations for the given query.
     *
     * @param \eZ\Platform\API\Repository\Values\Content\LocationQuery $query
     * @param array $languageFilter Configuration for specifying prioritized languages query will be performed on.
     *        Currently supports: <code>array("languages" => array(<language1>,..), "useAlwaysAvailable" => bool)</code>
     *                            useAlwaysAvailable defaults to true to avoid exceptions on missing translations
     * @param bool $filterOnUserPermissions if true only the objects which is the user allowed to read are returned.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Search\SearchResult
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if query is not valid
     *
     */
    public function findLocations(LocationQuery $query, array $languageFilter = [], $filterOnUserPermissions = true)
    {
        // TODO: Implement findLocations() method.
    }

    /**
     * Query for supported capability of currently configured search engine.
     *
     * Will return false if search engine does not implement {@see eZ\Publish\SPI\Search\Capable}.
     *
     * @param int $capabilityFlag One of CAPABILITY_* constants.
     *
     * @return bool
     * @since 6.12
     *
     */
    public function supports($capabilityFlag)
    {
        // TODO: Implement supports() method.
    }
}