<?php

/**
 * File containing the IntegrationTestRepository class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\API\Repository\Values\User\UserReference;
use App\eZ\Platform\Core\Repository\RequestParser;
use App\eZ\Platform\Core\Repository\Input\Dispatcher;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use App\eZ\Platform\Core\Repository\HttpClient\Authentication\IntegrationTestAuthenticator;

/**
 * REST Client Repository to be used in integration tests.
 *
 * Note: NEVER USE THIS IN PRODUCTION!
 *
 * @see \App\eZ\Platform\API\Repository\Repository
 */
class IntegrationTestRepository extends Repository implements Sessionable
{
    /**
     * Integration test authenticator.
     *
     * @var \App\eZ\Platform\Core\Repository\HttpClient\Authentication\IntegrationTestAuthentication
     */
    private $authenticator;

    /**
     * Client.
     *
     * @var \App\eZ\Platform\Core\Repository\HttpClient
     */
    private $client;

    /**
     * Current user.
     *
     * @var \App\eZ\Platform\API\Repository\Values\User\User
     */
    private $currentUser;

    /**
     * Instantiates the REST Client repository.
     *
     * @param \App\eZ\Platform\Core\Repository\\Symfony\Contracts\HttpClient\HttpClientInterface $ezpRestClient
     * @param \App\eZ\Platform\Core\Repository\Input\Dispatcher $inputDispatcher
     * @param \App\eZ\Platform\Core\Repository\Output\Visitor $outputVisitor
     * @param \eZ\Publish\SPI\FieldType\FieldType[] $fieldTypes
     * @param \App\eZ\Platform\Core\Repository\HttpClient\Authentication\IntegrationTestAuthentication $authenticator
     */
    public function __construct(\Symfony\Contracts\HttpClient\HttpClientInterface $ezpRestClient, Dispatcher $inputDispatcher, Visitor $outputVisitor, RequestParser $requestParser, array $fieldTypes, IntegrationTestAuthenticator $authenticator)
    {
        parent::__construct($client, $inputDispatcher, $outputVisitor, $requestParser, $fieldTypes);
        $this->client = $ezpRestClient;
        $this->authenticator = $authenticator;
    }

    /**
     * Set session ID.
     *
     * Only for testing
     *
     * @param mixed tringid
     *
     * @private
     */
    public function setSession($id)
    {
        if ($this->client instanceof Sessionable) {
            $this->client->setSession($id);
        }
    }

    /**
     * Get current user.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User
     */
    public function getCurrentUser()
    {
        return $this->currentUser;
    }

    /**
     * Sets the current user to the given $user.
     *
     * @param \App\eZ\Platform\API\Repository\Values\User\UserReference $user
     *
     * @return void
     */
    public function setCurrentUser(UserReference $user)
    {
        $this->currentUser = $user;
        $this->authenticator->setUserId($user->getUserId());
    }
}
