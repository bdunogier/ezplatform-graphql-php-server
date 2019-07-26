<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Mutation\InputHandler\FieldType;

use App\eZ\Platform\Core\FieldType\Value as FieldValue;
use App\GraphQL\Mutation\InputHandler\FieldTypeInputHandler;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class Media extends FromHash implements FieldTypeInputHandler
{
    public function toFieldValue($input, $inputFormat = null): FieldValue
    {
        if (!$input['file'] instanceof UploadedFile) {
            return null;
        }

        $file = $input['file'];

        $value = parent::toFieldValue([
            'fileName' => $input['fileName'] ?? $file->getClientOriginalName(),
            'inputUri' => $file->getPathname(),
            'fileSize' => $file->getSize(),
        ]);

        foreach (['hasController', 'loop', 'autoplay', 'width', 'height'] as $property) {
            if (isset($input[$property])) {
                $value->$property = $input[$property];
            }
        }

        return $value;
    }
}
