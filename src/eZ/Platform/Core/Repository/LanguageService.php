<?php

/**
 * File containing the LanguageService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\API\Repository\LanguageService as APILanguageService;
use App\eZ\Platform\API\Repository\ContentService as APIContentService;
use App\eZ\Platform\API\Repository\Values\Content\Language;
use App\eZ\Platform\API\Repository\Values\Content\LanguageCreateStruct;
use App\eZ\Platform\Core\Repository\Input\Dispatcher;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\LanguageService}
 * interface.
 *
 * @see \App\eZ\Platform\API\Repository\LanguageService
 */
class LanguageService implements APILanguageService, Sessionable
{
    /** @var \App\eZ\Platform\Core\Repository\ContentService */
    private $contentService;

    /** @var string */
    private $defaultLanguageCode;

    /** @var \App\eZ\Platform\Core\Repository\HttpClient */
    private $client;

    /** @var \App\eZ\Platform\Core\Repository\Input\Dispatcher */
    private $inputDispatcher;

    /** @var \App\eZ\Platform\Core\Repository\Output\Visitor */
    private $outputVisitor;

    /** @var \App\eZ\Platform\Core\Repository\RequestParser */
    private $requestParser;

    /**
     * @param \App\eZ\Platform\Core\Repository\\Symfony\Contracts\HttpClient\HttpClientInterface $ezpRestClient
     * @param \App\eZ\Platform\Core\Repository\Input\Dispatcher $inputDispatcher
     * @param \App\eZ\Platform\Core\Repository\Output\Visitor $outputVisitor
     * @param \App\eZ\Platform\Core\Repository\RequestParser $requestParser
     */
    public function __construct(APIContentService $contentService, $defaultLanguageCode, HttpClientInterface $ezpRestClient, Dispatcher $inputDispatcher, Visitor $outputVisitor, RequestParser $requestParser)
    {
        $this->contentService = $contentService;
        $this->defaultLanguageCode = $defaultLanguageCode;
        $this->client = $ezpRestClient;
        $this->inputDispatcher = $inputDispatcher;
        $this->outputVisitor = $outputVisitor;
        $this->requestParser = $requestParser;
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
        if ($this->outputVisitor instanceof Sessionable) {
            $this->outputVisitor->setSession($id);
        }
    }

    /**
     * Creates the a new Language in the content repository.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException if the languageCode already exists
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\LanguageCreateStruct $languageCreateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language
     */
    public function createLanguage(LanguageCreateStruct $languageCreateStruct)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Changes the name of the language in the content repository.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Language $language
     * @param string $newName
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language
     */
    public function updateLanguageName(Language $language, $newName)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Enables a language.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Language $language
     */
    public function enableLanguage(Language $language)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Disables a language.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Language $language
     */
    public function disableLanguage(Language $language)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Loads a Language from its language code ($languageCode).
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException if language could not be found
     *
     * @param string $languageCode
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language
     */
    public function loadLanguage($languageCode)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Loads all Languages.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language[]
     */
    public function loadLanguages()
    {
        // @todo implement me
        return [
            new Language(['languageCode' => 'eng-GB'])
        ];
    }

    /**
     * Loads a Language by its id ($languageId).
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException if language could not be found
     *
     * @param mixed $languageId
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Language
     */
    public function loadLanguageById($languageId)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * {@inheritdoc}
     */
    public function loadLanguageListByCode(array $languageCodes): iterable
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * {@inheritdoc}
     */
    public function loadLanguageListById(array $languageIds): iterable
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Deletes  a language from content repository.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException
     *         if language can not be deleted
     *         because it is still assigned to some content / type / (...).
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user does not have access to content translations
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Language $language
     */
    public function deleteLanguage(Language $language)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Returns a configured default language code.
     *
     * @return string
     */
    public function getDefaultLanguageCode()
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Instantiates an object to be used for creating languages.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\LanguageCreateStruct
     */
    public function newLanguageCreateStruct()
    {
        throw new \Exception('@todo: Implement.');
    }
}
