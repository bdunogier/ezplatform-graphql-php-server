<?php

/**
 * File containing the HttpClient interface.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\Core\Repository\Message;

/**
 * Interface for Http Client implementations.
 */
interface HttpClient
{
    /**
     * Execute a HTTP request to the remote server.
     *
     * Returns the result from the remote server.
     *
     * @param string $method
     * @param string $path
     * @param Message $message
     *
     * @return Message
     */
    public function request($method, $path, Message $message = null);
}
