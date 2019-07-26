<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\FieldType\RichText\Converter;

use App\eZ\Platform\Core\FieldType\RichText\Converter;
use DOMDocument;

class MarkdownConverter implements Converter
{
    /**
     * Converts given $xmlDoc into another \DOMDocument object.
     *
     * @param \DOMDocument $xmlDoc
     *
     * @return \DOMDocument
     */
    public function convert(DOMDocument $xmlDoc)
    {
        // TODO: Implement convert() method.
    }
}