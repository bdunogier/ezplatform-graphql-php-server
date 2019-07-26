<?php

/**
 * File containing the Repository class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\API\Repository\Repository as APIRepository;
use App\eZ\Platform\API\Repository\Values\ValueObject;
use App\eZ\Platform\API\Repository\Values\User\UserReference;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * REST Client Repository.
 *
 * @see \App\eZ\Platform\API\Repository\Repository
 */
class Repository implements APIRepository
{
    /** @var \App\eZ\Platform\Core\Repository\SectionService */
    private $sectionService;

    /** @var \App\eZ\Platform\Core\Repository\LanguageService */
    private $languageService;

    /** @var \App\eZ\Platform\Core\Repository\UserService */
    private $userService;

    /** @var \App\eZ\Platform\Core\Repository\RoleService */
    private $roleService;

    /** @var \App\eZ\Platform\Core\Repository\URLAliasService */
    private $urlAliasService;

    /** @var \App\eZ\Platform\Core\Repository\ContentService */
    private $contentService;

    /** @var \App\eZ\Platform\Core\Repository\ContentTypeService */
    private $contentTypeService;

    /** @var \App\eZ\Platform\Core\Repository\TrashService */
    private $trashService;

    /** @var \App\eZ\Platform\Core\Repository\LocationService */
    private $locationService;

    /** @var \App\eZ\Platform\Core\Repository\ObjectStateService */
    private $objectStateService;

    /** @var \App\eZ\Platform\Core\Repository\IOService */
    private $ioService;

    /** @var \App\eZ\Platform\Core\Repository\FieldTypeService */
    private $fieldTypeService;

    /**
     * Client.
     *
     * @var \App\eZ\Platform\Core\Repository\HttpClient
     */
    private $client;

    /**
     * Input parsing dispatcher.
     *
     * @var \App\eZ\Platform\Core\Repository\Input\Dispatcher
     */
    private $inputDispatcher;

    /** @var \App\eZ\Platform\Core\Repository\Output\Visitor */
    private $outputVisitor;

    /** @var \App\eZ\Platform\Core\Repository\RequestParser */
    private $requestParser;

    /** @var \App\eZ\Platform\SPI\FieldType\FieldType[] */
    private $fieldTypes;

    /**
     * Instantiates the REST Client repository.
     *
     * @param \App\eZ\Platform\Core\Repository\\Symfony\Contracts\HttpClient\HttpClientInterface $ezpRestClient
     * @param \App\eZ\Platform\Core\Repository\Input\Dispatcher $inputDispatcher
     * @param \App\eZ\Platform\Core\Repository\Output\Visitor $outputVisitor
     * @param \App\eZ\Platform\Core\Repository\RequestParser $requestParser
     * @param \eZ\Publish\SPI\FieldType\FieldType[] $fieldTypes
     */
    public function __construct(HttpClientInterface $ezpRestClient, Input\Dispatcher $inputDispatcher, Output\Visitor $outputVisitor, RequestParser $requestParser, array $fieldTypes)
    {
        $this->client = $ezpRestClient;
        $this->inputDispatcher = $inputDispatcher;
        $this->outputVisitor = $outputVisitor;
        $this->requestParser = $requestParser;
        $this->fieldTypes = $fieldTypes;
    }

    /**
     * @deprecated since 6.6, to be removed. Use PermissionResolver::getCurrentUserReference() instead.
     *
     * Get current user.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User
     */
    public function getCurrentUser()
    {
        return null;
    }

    /**
     * @deprecated since 6.6, to be removed. Use PermissionResolver::getCurrentUserReference() instead.
     *
     * Get current user.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\UserReference
     */
    public function getCurrentUserReference()
    {
        return null;
    }

    /**
     * @deprecated since 6.6, to be removed. Use PermissionResolver::setCurrentUserReference() instead.
     *
     * Sets the current user to the given $user.
     *
     * @param \App\eZ\Platform\API\Repository\Values\User\UserReference $user
     *
     * @return void
     */
    public function setCurrentUser(UserReference $user)
    {
        throw new Exceptions\MethodNotAllowedException(
            'It is not allowed to set a current user in this implementation. Please use a corresponding authenticating HttpClient instead.'
        );
    }

    /**
     * @deprecated since 6.6, to be removed. Use PermissionResolver::hasAccess() instead.
     *
     * @param string $module
     * @param string $function
     * @param \App\eZ\Platform\API\Repository\Values\User\UserReference $user
     *
     * @return bool|\App\eZ\Platform\API\Repository\Values\User\Limitation[] if limitations are on this function an array of limitations is returned
     */
    public function hasAccess($module, $function, UserReference $user = null)
    {
        // @todo: Implement
    }

    /**
     * @deprecated since 6.6, to be removed. Use PermissionResolver::canUser() instead.
     *
     * Indicates if the current user is allowed to perform an action given by the function on the given
     * objects.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If any of the arguments are invalid
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException If value of the LimitationValue is unsupported
     *
     * @param string $module The module, aka controller identifier to check permissions on
     * @param string $function The function, aka the controller action to check permissions on
     * @param \App\eZ\Platform\API\Repository\Values\ValueObject $object The object to check if the user has access to
     * @param mixed $targets The location, parent or "assignment" value object, or an array of the same
     *
     * @return bool
     */
    public function canUser($module, $function, ValueObject $object, $targets = null)
    {
        // @todo: Implement
    }

    /**
     * Get Content Service.
     *
     * Get service object to perform operations on Content objects and it's aggregate members.
     *
     * @return \App\eZ\Platform\API\Repository\ContentService
     */
    public function getContentService()
    {
        if (null === $this->contentService) {
            $this->contentService = new ContentService(
                $this->client,
                $this->inputDispatcher,
                $this->outputVisitor,
                $this->requestParser,
                $this->getContentTypeService()
            );
        }

        return $this->contentService;
    }

    /**
     * Get Content Language Service.
     *
     * Get service object to perform operations on Content language objects
     *
     * @return \App\eZ\Platform\API\Repository\LanguageService
     */
    public function getContentLanguageService()
    {
        if (null === $this->languageService) {
            $this->languageService = new LanguageService(
                $this->getContentService(),
                'eng-US',
                $this->client,
                $this->inputDispatcher,
                $this->outputVisitor,
                $this->requestParser
            );
        }

        return $this->languageService;
    }

    /**
     * Get Content Type Service.
     *
     * Get service object to perform operations on Content Type objects and it's aggregate members.
     * ( Group, Field & FieldCategory )
     *
     * @return \App\eZ\Platform\API\Repository\ContentTypeService
     */
    public function getContentTypeService()
    {
        if (null === $this->contentTypeService) {
            $this->contentTypeService = new ContentTypeService(
                $this->client,
                $this->inputDispatcher,
                $this->outputVisitor,
                $this->requestParser
            );
        }

        return $this->contentTypeService;
    }

    /**
     * Get Content Location Service.
     *
     * Get service object to perform operations on Location objects and subtrees
     *
     * @return \App\eZ\Platform\API\Repository\LocationService
     */
    public function getLocationService()
    {
        if (null === $this->locationService) {
            $this->locationService = new LocationService(
                $this->client,
                $this->inputDispatcher,
                $this->outputVisitor,
                $this->requestParser
            );
        }

        return $this->locationService;
    }

    /**
     * Get Content Trash service.
     *
     * Trash service allows to perform operations related to location trash
     * (trash/untrash, load/list from trash...)
     *
     * @return \App\eZ\Platform\API\Repository\TrashService
     */
    public function getTrashService()
    {
        if (null === $this->trashService) {
            $this->trashService = new TrashService(
                $this->getLocationService(),
                $this->client,
                $this->inputDispatcher,
                $this->outputVisitor,
                $this->requestParser
            );
        }

        return $this->trashService;
    }

    /**
     * Get Content Section Service.
     *
     * Get Section service that lets you manipulate section objects
     *
     * @return \App\eZ\Platform\API\Repository\SectionService
     */
    public function getSectionService()
    {
        if (null === $this->sectionService) {
            $this->sectionService = new SectionService(
                $this->client,
                $this->inputDispatcher,
                $this->outputVisitor,
                $this->requestParser
            );
        }

        return $this->sectionService;
    }

    /**
     * Get Search Service.
     *
     * Get search service that lets you find content objects
     *
     * @return \App\eZ\Platform\API\Repository\SearchService
     */
    public function getSearchService()
    {
        throw new \RuntimeException('@todo: Implement.');
    }

    /**
     * Get User Service.
     *
     * Get service object to perform operations on Users and UserGroup
     *
     * @return \App\eZ\Platform\API\Repository\UserService
     */
    public function getUserService()
    {
        if (null === $this->userService) {
            $this->userService = new UserService(
                $this->client,
                $this->inputDispatcher,
                $this->outputVisitor,
                $this->requestParser
            );
        }

        return $this->userService;
    }

    /**
     * Get IO Service.
     *
     * Get service object to perform operations on binary files
     *
     * @return \App\eZ\Platform\API\Repository\IOService
     */
    public function getIOService()
    {
        if (null === $this->ioService) {
            $this->ioService = new IOService(
                $this->client,
                $this->inputDispatcher,
                $this->outputVisitor,
                $this->requestParser
            );
        }

        return $this->ioService;
    }

    /**
     * Get RoleService.
     *
     * @return \App\eZ\Platform\API\Repository\RoleService
     */
    public function getRoleService()
    {
        if (null === $this->roleService) {
            $this->roleService = new RoleService(
                $this->getUserService(),
                $this->client,
                $this->inputDispatcher,
                $this->outputVisitor,
                $this->requestParser
            );
        }

        return $this->roleService;
    }

    /**
     * Get URLAliasService.
     *
     * @return \App\eZ\Platform\API\Repository\URLAliasService
     */
    public function getURLAliasService()
    {
        if (null === $this->urlAliasService) {
            $this->urlAliasService = new URLAliasService(
                $this->client,
                $this->inputDispatcher,
                $this->outputVisitor,
                $this->requestParser
            );
        }

        return $this->urlAliasService;
    }

    /**
     * Get URLWildcardService.
     *
     * @return \App\eZ\Platform\API\Repository\URLWildcardService
     */
    public function getURLWildcardService()
    {
        throw new \RuntimeException('@todo: Implement');
    }

    /**
     * Get ObjectStateService.
     *
     * @return \App\eZ\Platform\API\Repository\ObjectStateService
     */
    public function getObjectStateService()
    {
        if (null === $this->objectStateService) {
            $this->objectStateService = new ObjectStateService(
                $this->client,
                $this->inputDispatcher,
                $this->outputVisitor,
                $this->requestParser
            );
        }

        return $this->objectStateService;
    }

    /**
     * Get FieldTypeService.
     *
     * @return \App\eZ\Platform\API\Repository\FieldTypeService
     */
    public function getFieldTypeService()
    {
        if (null === $this->fieldTypeService) {
            $this->fieldTypeService = new FieldTypeService($this->fieldTypes);
        }

        return $this->fieldTypeService;
    }

    /**
     * Get PermissionResolver.
     *
     * @return \App\eZ\Platform\API\Repository\PermissionResolver
     */
    public function getPermissionResolver()
    {
        throw new \RuntimeException('@todo: Implement');
    }

    /**
     * Get URLService.
     *
     * @return \App\eZ\Platform\API\Repository\URLService
     */
    public function getURLService()
    {
        throw new \RuntimeException('@todo: Implement');
    }

    /**
     * Get BookmarkService.
     *
     * @return \App\eZ\Platform\API\Repository\BookmarkService
     */
    public function getBookmarkService()
    {
        throw new \RuntimeException('@todo: Implement');
    }

    /**
     * Get UserPreferenceService.
     *
     * @return \App\eZ\Platform\API\Repository\UserPreferenceService
     */
    public function getUserPreferenceService()
    {
        throw new \RuntimeException('@todo: Implement');
    }

    /**
     * Get NotificationService
     *
     * @return \App\eZ\Platform\API\Repository\NotificationService
     */
    public function getNotificationService()
    {
        throw new \RuntimeException('@todo: Implement');
    }

    /**
     * Begin transaction.
     *
     * Begins an transaction, make sure you'll call commit or rollback when done,
     * otherwise work will be lost.
     */
    public function beginTransaction()
    {
        // @todo: Implement / discuss
    }

    /**
     * Commit transaction.
     *
     * Commit transaction, or throw exceptions if no transactions has been started.
     *
     * @throws \RuntimeException If no transaction has been started
     */
    public function commit()
    {
        // @todo: Implement / discuss
    }

    /**
     * Rollback transaction.
     *
     * Rollback transaction, or throw exceptions if no transactions has been started.
     *
     * @throws \RuntimeException If no transaction has been started
     */
    public function rollback()
    {
        // @todo: Implement / discuss
    }

    /**
     * {@inheritdoc}
     */
    public function sudo(callable $callback, APIRepository $outerRepository = null)
    {
        // TODO: Implement sudo() method.
    }
}
