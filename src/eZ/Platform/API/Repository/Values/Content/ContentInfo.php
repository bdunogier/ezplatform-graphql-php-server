<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\ContentInfo class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * This class provides all version independent information of the Content object.
 *
 * @property-read mixed $id The unique id of the Content object
 * @property-read mixed $contentTypeId The unique id of the Content Type object the Content object is an instance of
 * @property-read string $name the computed name (via name schema) in the main language of the Content object
 * @property-read mixed $sectionId the section to which the Content object is assigned
 * @property-read int $currentVersionNo Current Version number is the version number of the published version or the version number of a newly created draft (which is 1).
 * @property-read bool $published true if there exists a published version false otherwise
 * @property-read mixed $ownerId the user id of the owner of the Content object
 * @property-read \DateTime $modificationDate Content object modification date
 * @property-read \DateTime $publishedDate date of the first publish
 * @property-read bool $alwaysAvailable Indicates if the Content object is shown in the mainlanguage if its not present in an other requested language
 * @property-read string $remoteId a global unique id of the Content object
 * @property-read string $mainLanguageCode The main language code of the Content object. If the available flag is set to true the Content is shown in this language if the requested language does not exist.
 * @property-read mixed $mainLocationId Identifier of the main location.
 * @property-read int $status status of the Content object
 * @property-read bool $isHidden status of the Content object
 */
class ContentInfo extends ValueObject
{
    const STATUS_DRAFT = 0;
    const STATUS_PUBLISHED = 1;
    const STATUS_TRASHED = 2;

    /**
     * The unique id of the Content object.
     *
     * @var mixed
     */
    protected $id;

    /**
     * The Content Type id of the Content object.
     *
     * @var mixed
     */
    protected $contentTypeId;

    /**
     * The computed name (via name schema) in the main language of the Content object.
     *
     * For names in other languages then main see {@see \eZ\Platform\API\Repository\Values\Content\VersionInfo}
     *
     * @var string
     */
    protected $name;

    /**
     * The section to which the Content object is assigned.
     *
     * @var mixed
     */
    protected $sectionId;

    /**
     * Current Version number is the version number of the published version or the version number of
     * a newly created draft (which is 1).
     *
     * @var int
     */
    protected $currentVersionNo;

    /**
     * True if there exists a published version, false otherwise.
     *
     * @var bool Constant.
     */
    protected $published;

    /**
     * The owner of the Content object.
     *
     * @var mixed
     */
    protected $ownerId;

    /**
     * Content modification date.
     *
     * @var \DateTime
     */
    protected $modificationDate;

    /**
     * Content publication date.
     *
     * @var \DateTime
     */
    protected $publishedDate;

    /**
     * Indicates if the Content object is shown in the mainlanguage if its not present in an other requested language.
     *
     * @var bool
     */
    protected $alwaysAvailable;

    /**
     * Remote identifier used as a custom identifier for the object.
     *
     * @var string
     */
    protected $remoteId;

    /**
     * The main language code of the Content object.
     *
     * @var string
     */
    protected $mainLanguageCode;

    /**
     * Identifier of the main location.
     *
     * If the Content object has multiple locations,
     * $mainLocationId will point to the main one.
     *
     * @var mixed
     */
    protected $mainLocationId;

    /**
     * Status of the content.
     *
     * Replaces deprecated API\ContentInfo::$published.
     *
     * @var int
     */
    protected $status;

    /** @var bool */
    protected $isHidden;

    /**
     * @return bool
     */
    public function isDraft()
    {
        return $this->status === self::STATUS_DRAFT;
    }

    /**
     * @return bool
     */
    public function isPublished()
    {
        return $this->status === self::STATUS_PUBLISHED;
    }

    /**
     * @return bool
     */
    public function isTrashed()
    {
        return $this->status === self::STATUS_TRASHED;
    }
}
