<?php

/**
 * File containing the VersionInfo class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Values\Content;

use App\eZ\Platform\API\Repository\ContentService;
use App\eZ\Platform\API\Repository\Values\Content\VersionInfo as APIVersionInfo;

/**
 * This class holds version information data. It also contains the corresponding {@link Content} to
 * which the version belongs to.
 *
 * @see \App\eZ\Platform\API\Repository\Values\Content\VersionInfo
 *
 * @property-read \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo calls getContentInfo()
 * @property-read mixed $id the internal id of the version
 * @property-read int $versionNo the version number of this version (which only increments in scope of a single Content object)
 * @property-read \DateTime $modificationDate the last modified date of this version
 * @property-read \DateTime $creationDate the creation date of this version
 * @property-read mixed $creatorId the user id of the user which created this version
 * @property-read int $status the status of this version. One of VersionInfo::STATUS_DRAFT, VersionInfo::STATUS_PUBLISHED, VersionInfo::STATUS_ARCHIVED
 * @property-read string $initialLanguageCode the language code of the version. This value is used to flag a version as a translation to specific language
 * @property-read array $languageCodes a collection of all languages which exist in this version.
 */
class VersionInfo extends APIVersionInfo
{
    /** @var \App\eZ\Platform\Core\Repository\ContentService */
    protected $contentService;

    /** @var string */
    protected $contentInfoId;

    /** @var string[] */
    protected $names;

    public function __construct(ContentService $contentService, array $data = array())
    {
        parent::__construct($data);

        $this->contentService = $contentService;
    }

    /**
     * Content of the content this version belongs to.
     *
     * @return ContentInfo
     */
    public function getContentInfo()
    {
        return $this->contentService->loadContentInfo($this->contentInfoId);
    }

    /**
     * Returns the names computed from the name schema in the available languages.
     *
     * @return string[]
     */
    public function getNames()
    {
        return $this->names;
    }

    /**
     * Returns the name computed from the name schema in the given language.
     * If no language is given the name in initial language of the version if present, otherwise null.
     *
     * @param string $languageCode
     *
     * @return string
     */
    public function getName($languageCode = null)
    {
        if ($languageCode === null) {
            $languageCode = $this->initialLanguageCode;
        }

        return $this->names[$languageCode];
    }

    public function __get($propertyName)
    {
        switch ($propertyName) {
            case 'contentInfo':
                return $this->getContentInfo();
        }

        return parent::__get($propertyName);
    }

    public function __isset($propertyName)
    {
        switch ($propertyName) {
            case 'contentInfo':
                return true;
        }

        return parent::__isset($propertyName);
    }
}
