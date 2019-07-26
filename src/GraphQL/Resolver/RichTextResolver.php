<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Resolver;

use DOMDocument;
use App\eZ\Platform\Core\FieldType\RichText\Converter as RichTextConverterInterface;

/**
 * @internal
 */
class RichTextResolver
{
    /**
     * @var RichTextConverterInterface
     */
    private $richTextConverter;
    /**
     * @var RichTextConverterInterface
     */
    private $richTextEditConverter;

    public function __construct(RichTextConverterInterface\Xhtml5Converter $richTextConverter, RichTextConverterInterface\Xhtml5EditConverter $richTextEditConverter)
    {
        $this->richTextConverter = $richTextConverter;
        $this->richTextEditConverter = $richTextEditConverter;
    }

    public function xmlToHtml5(DOMDocument $document)
    {
        return $this->richTextConverter->convert($document)->saveHTML();
    }

    public function xmlToHtml5Edit(DOMDocument $document)
    {
        return $this->richTextEditConverter->convert($document)->saveHTML();
    }

    public function xmlToPlainText(DOMDocument $document)
    {
        $html = $this->richTextConverter->convert($document)->saveHTML();

        return strip_tags($html);
    }
}
