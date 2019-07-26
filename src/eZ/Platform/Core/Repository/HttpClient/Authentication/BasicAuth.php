<?php

/**
 * File containing the BasicAuth authentication HttpClient.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\HttpClient\Authentication;

use App\eZ\Platform\Core\Repository\HttpClient;
use App\eZ\Platform\Core\Repository\Message;

/**
 * Interface for Http Client implementations.
 */
class BasicAuth implements HttpClient
{
    /**
     * Inner HTTP client, performing the actual requests.
     *
     * @var \App\eZ\Platform\Core\Repository\HttpClient
     */
    protected $innerClient;

    /**
     * User name for Basic Auth.
     *
     * @var string
     */
    protected $username;

    /**
     * Password for Basic Auth.
     *
     * @var string
     */
    protected $password;

    /**
     * Creates a new Basic Auth HTTP client.
     *
     * @param \App\eZ\Platform\Core\Repository\HttpClient $innerClient
     * @param string $username
     * @param string $password
     */
    public function __construct(HttpClient $innerClient, $username, $password)
    {
        $this->innerClient = $innerClient;
        $this->username = $username;
        $this->password = $password;
    }

    /**
     * Execute a HTTP request to the remote server.
     *
     * Returns the result from the remote server. The client sets the correct
     * headers for Basic Auth into the $message transmitted to the inner
     * client.
     *
     * @param string $method
     * @param string $path
     * @param \App\eZ\Platform\Core\Repository\Message $message
     *
     * @return \App\eZ\Platform\Core\Repository\Message
     */
    public function request($method, $path, Message $message = null)
    {
        if ($message === null) {
            $message = new Message();
        }
        $message->headers['Authorization'] = sprintf(
            'Basic %s',
            base64_encode(
                sprintf(
                    '%s:%s',
                    $this->username,
                    $this->password
                )
            )
        );

        return $this->innerClient->request($method, $path, $message);
    }
}
