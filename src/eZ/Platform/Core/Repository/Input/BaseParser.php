<?php

/**
 * File containing the Base parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Input;

use App\eZ\Platform\Core\Repository\RequestParser;

abstract class BaseParser extends Parser
{
    /**
     * URL handler.
     *
     * @var \App\eZ\Platform\Core\Repository\RequestParser
     */
    protected $requestParser;

    public function setRequestParser(RequestParser $requestParser)
    {
        $this->requestParser = $requestParser;
    }
}
