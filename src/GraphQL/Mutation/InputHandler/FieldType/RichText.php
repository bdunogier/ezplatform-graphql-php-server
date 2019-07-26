<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Mutation\InputHandler\FieldType;

use App\eZ\Platform\Core\FieldType\RichText as RichTextFieldType;
use App\eZ\Platform\Core\FieldType\Value as FieldValue;
use App\Exception\UnsupportedFieldInputFormatException;
use App\GraphQL\Mutation\InputHandler\FieldType\RichText\RichTextInputConverter;
use App\GraphQL\Mutation\InputHandler\FieldTypeInputHandler;

class RichText implements FieldTypeInputHandler
{
    /**
     * @var RichTextInputConverter[]
     */
    private $inputConverters;

    public function __construct(array $inputConverters)
    {
        $this->inputConverters = $inputConverters;
    }

    /**
     * @param array $input
     * @param null $inputFormat
     *
     * @return RichTextFieldType\Value
     */
    public function toFieldValue($input, $inputFormat = null): FieldValue
    {
        if (isset($this->inputConverters[$inputFormat])) {
            $fieldValue = new RichTextFieldType\Value(
                $this->inputConverters[$inputFormat]->convertToXml($input)
            );
        } else {
            throw new UnsupportedFieldInputFormatException('ezrichtext', $inputFormat);
        }

        return $fieldValue;
    }
}
