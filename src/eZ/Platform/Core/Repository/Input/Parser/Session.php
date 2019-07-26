<?php

/**
 * File containing the Session parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParserTools;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use eZ\Publish\Core\REST\Server\Values;
use App\eZ\Platform\API\Repository\UserService;

/**
 * Value for Session.
 */
class Session extends BaseParser
{
    /** @var \App\eZ\Platform\Core\Repository\Input\ParserTools */
    protected $parserTools;

    /**
     * User Service.
     *
     * @var \App\eZ\Platform\Core\Repository\userService
     */
    protected $userService;

    /**
     * @param \App\eZ\Platform\Core\Repository\Input\ParserTools $parserTools
     * @param \App\eZ\Platform\API\Repository\UserService $userService
     */
    public function __construct(ParserTools $parserTools, UserService $userService)
    {
        $this->parserTools = $parserTools;
        $this->userService = $userService;
    }

    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\Role
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        if (!array_key_exists('_href', $data['User'])) {
            throw new Exceptions\Parser("Missing '_href' attribute for User element in Session.");
        }

        $userId = $this->requestParser->parseHref($data['User']['_href'], 'userId');

        $user = $this->userService->loadUser($userId);

        return new Values\UserSession(
            $user,
            $data['name'],
            $data['identifier'],
            $data['csrfToken'],
            null
        );
    }
}
