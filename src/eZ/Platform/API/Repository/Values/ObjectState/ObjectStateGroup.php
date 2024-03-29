<?php

/**
 * File containing the ObjectStateGroup class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\ObjectState;

use App\eZ\Platform\API\Repository\Values\ValueObject;
use App\eZ\Platform\API\Repository\Values\MultiLanguageDescription;
use App\eZ\Platform\API\Repository\Values\MultiLanguageName;

/**
 * This class represents an object state group value.
 *
 * @property-read mixed $id the id of the content type group
 * @property-read string $identifier the identifier of the content type group
 * @property-read string $mainLanguageCode the default language of the object state group names and description used for fallback.
 * @property-read string[] $languageCodes the available languages
 */
abstract class ObjectStateGroup extends ValueObject implements MultiLanguageName, MultiLanguageDescription
{
    /**
     * Primary key.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Readable string identifier of a group.
     *
     * @var string
     */
    protected $identifier;

    /**
     * The default language code.
     *
     * @var string
     */
    protected $defaultLanguageCode;

    /**
     * The available language codes for names an descriptions.
     *
     * @var string[]
     */
    protected $languageCodes;
}
