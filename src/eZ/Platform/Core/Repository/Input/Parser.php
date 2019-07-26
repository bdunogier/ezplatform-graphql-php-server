<?php

/**
 * File containing the Parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Input;

/**
 * Base class for input parser.
 */
abstract class Parser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @return \App\eZ\Platform\API\Repository\Values\ValueObject
     */
    abstract public function parse(array $data, ParsingDispatcher $parsingDispatcher);
}
