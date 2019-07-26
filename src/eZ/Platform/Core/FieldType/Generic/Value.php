<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\FieldType\Generic;

use App\eZ\Platform\Core\FieldType\Value as FieldTypeValue;

class Value implements FieldTypeValue
{
    /**
     * @var array
     */
    private $data;

    public function __construct($data = [])
    {
        $this->data = [];
    }

    public function __get($property)
    {
        if (array_key_exists($this->data, $property)) {
            return $this->data[$property];
        }
    }

    public function __isset($property)
    {
        return array_key_exists($this->data, $property);
    }

    public function __set($property, $value)
    {
        $this->data[$property] = $value;
    }

    public function toArray(): array
    {
        return $this->data;
    }
}