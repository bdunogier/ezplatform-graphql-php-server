<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\FieldType\TextLine;

use App\eZ\Platform\API\Repository\Values\ValueObject;

class Value extends ValueObject implements \App\eZ\Platform\Core\FieldType\Value
{
    protected $text;

    public function __construct($text = '')
    {
        $this->text = $text;
    }

    public function __toString()
    {
        return (string)$this->text;
    }
}