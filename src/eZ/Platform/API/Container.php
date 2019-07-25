<?php

/**
 * File containing the eZ\Platform\API\Container class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API;

/**
 * Container interface.
 *
 * Starting point for getting all Public API's
 */
interface Container
{
    /**
     * Get Repository object.
     *
     * Public API for
     *
     * @return \App\eZ\Platform\API\Repository\Repository
     */
    public function getRepository();
}
