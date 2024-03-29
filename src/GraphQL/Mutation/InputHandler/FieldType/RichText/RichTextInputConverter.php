<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Mutation\InputHandler\FieldType\RichText;

interface RichTextInputConverter
{
    public function convertToXml($text): \DOMDocument;
}
