<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Value;

use App\eZ\Platform\API\Repository\Values\Content\Content;
use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * A FieldValue Proxy that holds the content and field definition identifier.
 *
 * Required to be able to identify a value's FieldType to map with a GraphQL type.
 *
 * @property int $contentTypeId
 * @property string $fieldDefIdentifier
 * @property object $value
 * @
 */
class ContentFieldValue extends ValueObject
{
    /**
     * Identifier of the field definition this value is from.
     */
    protected $fieldDefIdentifier;

    /**
     * Id of the Content Type this value is from.
     */
    protected $contentTypeId;

    /**
     * @var \App\eZ\Platform\API\Repository\Values\Content\Content
     */
    protected $content;

    /**
     * @var \App\eZ\Platform\Core\FieldType\Value
     */
    protected $value;

    public function __get($property)
    {
        if (property_exists($this->value, $property)) {
            return $this->value->$property;
        }

        return parent::__get($property);
    }

    public function __toString()
    {
        return (string)$this->value;
    }
}
