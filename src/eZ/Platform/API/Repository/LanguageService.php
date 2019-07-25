<?php

/**
 * File containing the eZ\Platform\API\Repository\LanguageService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository;

use App\eZ\Platform\API\Repository\Values\Content\LanguageCreateStruct;
use App\eZ\Platform\API\Repository\Values\Content\Language;

/**
 * Language service, used for language operations.
 */
interface LanguageService
{
    /**
     * Creates the a new Language in the content repository.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the languageCode already exists
     *
     * @param \eZ\Platform\API\Repository\Values\Content\LanguageCreateStruct $languageCreateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language
     */
    public function createLanguage(LanguageCreateStruct $languageCreateStruct);

    /**
     * Changes the name of the language in the content repository.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Language $language
     * @param string $newName
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language
     */
    public function updateLanguageName(Language $language, $newName);

    /**
     * Enables a language.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Language $language
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language
     */
    public function enableLanguage(Language $language);

    /**
     * Disables a language.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Language $language
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language
     */
    public function disableLanguage(Language $language);

    /**
     * Loads a Language from its language code ($languageCode).
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if language could not be found
     *
     * @param string $languageCode
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language
     */
    public function loadLanguage($languageCode);

    /**
     * Loads all Languages.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language[]
     */
    public function loadLanguages();

    /**
     * Loads a Language by its id ($languageId).
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if language could not be found
     *
     * @param mixed $languageId
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language
     */
    public function loadLanguageById($languageId);

    /**
     * Bulk-load Languages by language codes.
     *
     * Note: it does not throw exceptions on load, just ignores erroneous Languages.
     *
     * @param string[] $languageCodes
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language[] list of Languages with language-code as keys
     */
    public function loadLanguageListByCode(array $languageCodes): iterable;

    /**
     * Bulk-load Languages by ids.
     *
     * Note: it does not throw exceptions on load, just ignores erroneous Languages.
     *
     * @param int[] $languageIds
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language[] list of Languages with id as keys
     */
    public function loadLanguageListById(array $languageIds): iterable;

    /**
     * Deletes  a language from content repository.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException
     *         if language can not be deleted
     *         because it is still assigned to some content / type / (...).
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user is not allowed to delete a language
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Language $language
     */
    public function deleteLanguage(Language $language);

    /**
     * Returns a configured default language code.
     *
     * @return string
     */
    public function getDefaultLanguageCode();

    /**
     * Instantiates an object to be used for creating languages.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\LanguageCreateStruct
     */
    public function newLanguageCreateStruct();
}
