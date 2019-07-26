<?php

/**
 * File containing the Policy parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use eZ\Publish\Core\REST\Client;

/**
 * Parser for Policy.
 */
class Policy extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\Policy
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $limitations = array();

        if (array_key_exists('limitations', $data)) {
            foreach ($data['limitations']['limitation'] as $limitation) {
                $limitations[] = $parsingDispatcher->parse(
                    $limitation,
                    $limitation['_media-type']
                );
            }
        }

        return new Client\Values\User\Policy(
            array(
                'id' => $data['id'],
                'module' => $data['module'],
                'function' => $data['function'],
                'limitations' => $limitations,
            )
        );
    }
}
