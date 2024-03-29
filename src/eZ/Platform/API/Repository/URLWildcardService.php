<?php

/**
 * File containing the eZ\Platform\API\Repository\URLWildcardService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository;

use App\eZ\Platform\API\Repository\Values\Content\URLWildcard;

/**
 * URLAlias service.
 *
 * @example Examples/urlalias.php
 */
interface URLWildcardService
{
    /**
     * Creates a new url wildcard.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the $sourceUrl pattern already exists
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to create url wildcards
     * @throws \eZ\Platform\API\Repository\Exceptions\ContentValidationException if the number of "*" patterns in $sourceUrl and
     *          the number of {\d} placeholders in $destinationUrl doesn't match or
     *          if the placeholders aren't a valid number sequence({1}/{2}/{3}), starting with 1.
     *
     * @param string $sourceUrl
     * @param string $destinationUrl
     * @param bool $forward
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\UrlWildcard
     */
    public function create($sourceUrl, $destinationUrl, $forward = false);

    /**
     * Removes an url wildcard.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the user is not allowed to remove url wildcards
     *
     * @param \eZ\Platform\API\Repository\Values\Content\UrlWildcard $urlWildcard the url wildcard to remove
     */
    public function remove(URLWildcard $urlWildcard);

    /**
     * Loads a url wild card.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if the url wild card was not found
     *
     * @param mixed $id
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\UrlWildcard
     */
    public function load($id);

    /**
     * Loads all url wild card (paged).
     *
     * @param int $offset
     * @param int $limit
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\UrlWildcard[]
     */
    public function loadAll($offset = 0, $limit = -1);

    /**
     * Translates an url to an existing uri resource based on the
     * source/destination patterns of the url wildcard.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if the url could not be translated
     *
     * @param mixed $url
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\URLWildcardTranslationResult
     */
    public function translate($url);
}
