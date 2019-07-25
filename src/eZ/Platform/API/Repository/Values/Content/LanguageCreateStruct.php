<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\LanguageCreateStruct class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * This class represents a value for creating a language.
 */
class LanguageCreateStruct extends ValueObject
{
    /**
     * The languageCode code.
     *
     * Needs to be a unique.
     *
     * @var string
     */
    public $languageCode;

    /**
     * Human readable name of the language.
     *
     * @var string
     */
    public $name;

    /**
     * Indicates if the language is enabled or not.
     *
     * @var bool
     */
    public $enabled = true;
}
