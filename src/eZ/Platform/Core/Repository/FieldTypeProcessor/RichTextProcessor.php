<?php

/**
 * File containing the RichTextProcessor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\FieldTypeProcessor;

use App\eZ\Platform\Core\Repository\FieldTypeProcessor;
use eZ\Publish\Core\FieldType\RichText\Converter;
use DOMDocument;

class RichTextProcessor extends FieldTypeProcessor
{
    /** @var \eZ\Publish\Core\FieldType\RichText\Converter */
    protected $docbookToXhtml5EditConverter;

    public function __construct(/*Converter $docbookToXhtml5EditConverter*/)
    {
        $this->docbookToXhtml5EditConverter = null;
    }

    /**
     * {@inheritdoc}
     */
    public function postProcessValueHash($outgoingValueHash)
    {
        $document = new DOMDocument();
        $document->loadXML($outgoingValueHash['xml']);

        $outgoingValueHash['xhtml5edit'] = $this->docbookToXhtml5EditConverter
            ->convert($document)
            ->saveXML();

        return $outgoingValueHash;
    }
}
