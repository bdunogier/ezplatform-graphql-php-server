<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Mutation\InputHandler\FieldType;

use App\eZ\Platform\Core\FieldType\Value as FieldValue;
use App\eZ\Platform\Core\FieldType\Generic\Value as GenericValue;
use App\GraphQL\Mutation\InputHandler\FieldTypeInputHandler;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class BinaryFile implements FieldTypeInputHandler
{
    public function toFieldValue($input, $inputFormat = null): FieldValue
    {
        if (!$input['file'] instanceof UploadedFile) {
            return null;
        }

        $file = $input['file'];

        return new GenericValue([
            'fileName' => $file->getClientOriginalName(),
            'inputUri' => $file->getPathname(),
            'fileSize' => $file->getSize(),
        ]);
    }
}
