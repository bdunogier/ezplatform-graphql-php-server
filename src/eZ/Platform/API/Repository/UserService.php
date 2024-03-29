<?php

/**
 * File containing the eZ\Platform\API\Repository\UserService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository;

use App\eZ\Platform\API\Repository\Values\Content\Content;
use App\eZ\Platform\API\Repository\Values\User\PasswordValidationContext;
use App\eZ\Platform\API\Repository\Values\User\UserTokenUpdateStruct;
use App\eZ\Platform\API\Repository\Values\User\UserCreateStruct;
use App\eZ\Platform\API\Repository\Values\User\UserUpdateStruct;
use App\eZ\Platform\API\Repository\Values\User\User;
use App\eZ\Platform\API\Repository\Values\User\UserGroup;
use App\eZ\Platform\API\Repository\Values\User\UserGroupCreateStruct;
use App\eZ\Platform\API\Repository\Values\User\UserGroupUpdateStruct;

/**
 * This service provides methods for managing users and user groups.
 *
 * @example Examples/user.php
 */
interface UserService
{
    /**
     * Creates a new user group using the data provided in the ContentCreateStruct parameter.
     *
     * In 4.x in the content type parameter in the profile is ignored
     * - the content type is determined via configuration and can be set to null.
     * The returned version is published.
     *
     * @param \eZ\Platform\API\Repository\Values\User\UserGroupCreateStruct $userGroupCreateStruct a structure for setting all necessary data to create this user group
     * @param \eZ\Platform\API\Repository\Values\User\UserGroup $parentGroup
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\UserGroup
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to create a user group
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the input structure has invalid data
     * @throws \eZ\Platform\API\Repository\Exceptions\ContentFieldValidationException if a field in the $userGroupCreateStruct is not valid
     * @throws \eZ\Platform\API\Repository\Exceptions\ContentValidationException if a required field is missing or set to an empty value
     */
    public function createUserGroup(UserGroupCreateStruct $userGroupCreateStruct, UserGroup $parentGroup);

    /**
     * Loads a user group for the given id.
     *
     * @param mixed $id
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\UserGroup
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to create a user group
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if the user group with the given id was not found
     */
    public function loadUserGroup($id, array $prioritizedLanguages = []);

    /**
     * Loads the sub groups of a user group.
     *
     * @param \eZ\Platform\API\Repository\Values\User\UserGroup $userGroup
     * @param int $offset the start offset for paging
     * @param int $limit the number of user groups returned
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\UserGroup[]
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to read the user group
     */
    public function loadSubUserGroups(UserGroup $userGroup, $offset = 0, $limit = 25, array $prioritizedLanguages = []);

    /**
     * Removes a user group.
     *
     * the users which are not assigned to other groups will be deleted.
     *
     * @param \eZ\Platform\API\Repository\Values\User\UserGroup $userGroup
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to create a user group
     *
     * @return mixed[] Affected Location Id's (List of Locations of the Content that was deleted)
     */
    public function deleteUserGroup(UserGroup $userGroup);

    /**
     * Moves the user group to another parent.
     *
     * @param \eZ\Platform\API\Repository\Values\User\UserGroup $userGroup
     * @param \eZ\Platform\API\Repository\Values\User\UserGroup $newParent
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to move the user group
     */
    public function moveUserGroup(UserGroup $userGroup, UserGroup $newParent);

    /**
     * Updates the group profile with fields and meta data.
     *
     * 4.x: If the versionUpdateStruct is set in $userGroupUpdateStruct, this method internally creates a content draft, updates ts with the provided data
     * and publishes the draft. If a draft is explicitly required, the user group can be updated via the content service methods.
     *
     * @param \eZ\Platform\API\Repository\Values\User\UserGroup $userGroup
     * @param \eZ\Platform\API\Repository\Values\User\UserGroupUpdateStruct $userGroupUpdateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\UserGroup
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to move the user group
     * @throws \eZ\Platform\API\Repository\Exceptions\ContentFieldValidationException if a field in the $userGroupUpdateStruct is not valid
     * @throws \eZ\Platform\API\Repository\Exceptions\ContentValidationException if a required field is set empty
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if a field value is not accepted by the field type
     */
    public function updateUserGroup(UserGroup $userGroup, UserGroupUpdateStruct $userGroupUpdateStruct);

    /**
     * Create a new user. The created user is published by this method.
     *
     * @param \eZ\Platform\API\Repository\Values\User\UserCreateStruct $userCreateStruct the data used for creating the user
     * @param array $parentGroups the groups of type {@link \eZ\Platform\API\Repository\Values\User\UserGroup} which are assigned to the user after creation
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to move the user group
     * @throws \eZ\Platform\API\Repository\Exceptions\ContentFieldValidationException if a field in the $userCreateStruct is not valid
     * @throws \eZ\Platform\API\Repository\Exceptions\ContentValidationException if a required field is missing or set  to an empty value
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if a field value is not accepted by the field type
     *                                                                        if a user with provided login already exists
     */
    public function createUser(UserCreateStruct $userCreateStruct, array $parentGroups);

    /**
     * Loads a user.
     *
     * @param mixed $userId
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if a user with the given id was not found
     */
    public function loadUser($userId, array $prioritizedLanguages = []);

    /**
     * Loads anonymous user.
     *
     * @deprecated since 5.3, use loadUser( $anonymousUserId ) instead
     *
     * @uses ::loadUser()
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User
     */
    public function loadAnonymousUser();

    /**
     * Loads a user for the given login and password.
     *
     * Since 6.1 login is case-insensitive across all storage engines and database backends, however if login
     * is part of the password hash this method will essentially be case sensitive.
     *
     * @param string $login
     * @param string $password the plain password
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if credentials are invalid
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if a user with the given credentials was not found
     */
    public function loadUserByCredentials($login, $password, array $prioritizedLanguages = []);

    /**
     * Loads a user for the given login.
     *
     * Since 6.1 login is case-insensitive across all storage engines and database backends, like was the case
     * with mysql before in eZ Publish 3.x/4.x/5.x.
     *
     * @param string $login
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if a user with the given credentials was not found
     */
    public function loadUserByLogin($login, array $prioritizedLanguages = []);

    /**
     * Loads a user for the given email.
     *
     * Note: This method loads user by $email where $email might be case-insensitive on certain storage engines!
     *
     * Returns an array of Users since eZ Publish has under certain circumstances allowed
     * several users having same email in the past (by means of a configuration option).
     *
     * @param string $email
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User[]
     */
    public function loadUsersByEmail($email, array $prioritizedLanguages = []);

    /**
     * Loads a user with user hash key.
     *
     * @param string $hash
     * @param array $prioritizedLanguages
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User
     */
    public function loadUserByToken($hash, array $prioritizedLanguages = []);

    /**
     * This method deletes a user.
     *
     * @param \eZ\Platform\API\Repository\Values\User\User $user
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to delete the user
     *
     * @return mixed[] Affected Location Id's (List of Locations of the Content that was deleted)
     */
    public function deleteUser(User $user);

    /**
     * Updates a user.
     *
     * 4.x: If the versionUpdateStruct is set in the user update structure, this method internally creates a content draft, updates ts with the provided data
     * and publishes the draft. If a draft is explicitly required, the user group can be updated via the content service methods.
     *
     * @param \eZ\Platform\API\Repository\Values\User\User $user
     * @param \eZ\Platform\API\Repository\Values\User\UserUpdateStruct $userUpdateStruct
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to update the user
     * @throws \eZ\Platform\API\Repository\Exceptions\ContentFieldValidationException if a field in the $userUpdateStruct is not valid
     * @throws \eZ\Platform\API\Repository\Exceptions\ContentValidationException if a required field is set empty
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if a field value is not accepted by the field type
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User
     */
    public function updateUser(User $user, UserUpdateStruct $userUpdateStruct);

    /**
     * Update the user token information specified by the user token struct.
     *
     * @param \eZ\Platform\API\Repository\Values\User\User $user
     * @param \eZ\Platform\API\Repository\Values\User\UserTokenUpdateStruct $userTokenUpdateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User
     */
    public function updateUserToken(User $user, UserTokenUpdateStruct $userTokenUpdateStruct);

    /**
     * Expires user token with user hash.
     *
     * @param string $hash
     */
    public function expireUserToken($hash);

    /**
     * Assigns a new user group to the user.
     *
     * If the user is already in the given user group this method does nothing.
     *
     * @param \eZ\Platform\API\Repository\Values\User\User $user
     * @param \eZ\Platform\API\Repository\Values\User\UserGroup $userGroup
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to assign the user group to the user
     */
    public function assignUserToUserGroup(User $user, UserGroup $userGroup);

    /**
     * Removes a user group from the user.
     *
     * @param \eZ\Platform\API\Repository\Values\User\User $user
     * @param \eZ\Platform\API\Repository\Values\User\UserGroup $userGroup
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to remove the user group from the user
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the user is not in the given user group
     * @throws \eZ\Platform\API\Repository\Exceptions\BadStateException If $userGroup is the last assigned user group
     */
    public function unAssignUserFromUserGroup(User $user, UserGroup $userGroup);

    /**
     * Loads the user groups the user belongs to.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed read the user or user group
     *
     * @param \eZ\Platform\API\Repository\Values\User\User $user
     * @param int $offset the start offset for paging
     * @param int $limit the number of user groups returned
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\UserGroup[]
     */
    public function loadUserGroupsOfUser(User $user, $offset = 0, $limit = 25, array $prioritizedLanguages = []);

    /**
     * Loads the users of a user group.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException if the authenticated user is not allowed to read the users or user group
     *
     * @param \eZ\Platform\API\Repository\Values\User\UserGroup $userGroup
     * @param int $offset the start offset for paging
     * @param int $limit the number of users returned
     * @param string[] $prioritizedLanguages Used as prioritized language code on translated properties of returned object.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\User[]
     */
    public function loadUsersOfUserGroup(UserGroup $userGroup, $offset = 0, $limit = 25, array $prioritizedLanguages = []);

    /**
     * Checks if Content is a user.
     *
     *  @since 7.4
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Content $content
     *
     * @return bool
     */
    public function isUser(Content $content): bool;

    /**
     * Checks if Content is a user group.
     *
     * @since 7.4
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Content $content
     *
     * @return bool
     */
    public function isUserGroup(Content $content): bool;

    /**
     * Instantiate a user create class.
     *
     * @param string $login the login of the new user
     * @param string $email the email of the new user
     * @param string $password the plain password of the new user
     * @param string $mainLanguageCode the main language for the underlying content object
     * @param \eZ\Platform\API\Repository\Values\ContentType\ContentType $contentType 5.x the content type for the underlying content object. In 4.x it is ignored and taken from the configuration
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\UserCreateStruct
     */
    public function newUserCreateStruct($login, $email, $password, $mainLanguageCode, $contentType = null);

    /**
     * Instantiate a user group create class.
     *
     * @param string $mainLanguageCode The main language for the underlying content object
     * @param null|\eZ\Platform\API\Repository\Values\ContentType\ContentType $contentType 5.x the content type for the underlying content object. In 4.x it is ignored and taken from the configuration
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\UserGroupCreateStruct
     */
    public function newUserGroupCreateStruct($mainLanguageCode, $contentType = null);

    /**
     * Instantiate a new user update struct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\UserUpdateStruct
     */
    public function newUserUpdateStruct();

    /**
     * Instantiate a new user group update struct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\UserGroupUpdateStruct
     */
    public function newUserGroupUpdateStruct();

    /**
     * Validates given password.
     *
     * @param string $password
     * @param \eZ\Platform\API\Repository\Values\User\PasswordValidationContext|null $context
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\ContentValidationException
     *
     * @return \eZ\Publish\SPI\FieldType\ValidationError[]
     */
    public function validatePassword(string $password, PasswordValidationContext $context = null): array;
}
