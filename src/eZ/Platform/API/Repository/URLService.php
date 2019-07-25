<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository;

use App\eZ\Platform\API\Repository\Values\URL\URL;
use App\eZ\Platform\API\Repository\Values\URL\URLQuery;
use App\eZ\Platform\API\Repository\Values\URL\URLUpdateStruct;

/**
 * URL Service.
 */
interface URLService
{
    /**
     * Instantiates a new URL update struct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\URL\URLUpdateStruct
     */
    public function createUpdateStruct();

    /**
     * Find URLs.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException
     *
     * @param \eZ\Platform\API\Repository\Values\URL\URLQuery $query
     * @return \App\eZ\Platform\API\Repository\Values\URL\SearchResult
     */
    public function findUrls(URLQuery $query);

    /**
     * Find content objects using URL.
     *
     * Content is filter by user permissions.
     *
     * @param \eZ\Platform\API\Repository\Values\URL\URL $url
     * @param int $offset
     * @param int $limit
     * @return \App\eZ\Platform\API\Repository\Values\URL\UsageSearchResult
     */
    public function findUsages(URL $url, $offset = 0, $limit = -1);

    /**
     * Load single URL (by ID).
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException
     *
     * @param int $id ID of URL
     * @return \App\eZ\Platform\API\Repository\Values\URL\URL
     */
    public function loadById($id);

    /**
     * Load single URL (by URL).
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException
     *
     * @param string $url url
     * @return \App\eZ\Platform\API\Repository\Values\URL\URL
     */
    public function loadByUrl($url);

    /**
     * Updates URL.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the url already exists
     *
     * @param \eZ\Platform\API\Repository\Values\URL\URL $url
     * @param \eZ\Platform\API\Repository\Values\URL\URLUpdateStruct $struct
     * @return \App\eZ\Platform\API\Repository\Values\URL\URL
     */
    public function updateUrl(URL $url, URLUpdateStruct $struct);
}
