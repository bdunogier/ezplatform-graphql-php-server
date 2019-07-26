<?php
/**
 * File containing the FloatProcessor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\FieldTypeProcessor;

use App\eZ\Platform\Core\Repository\FieldTypeProcessor;

class FloatProcessor extends FieldTypeProcessor
{
    /**
     * {@inheritdoc}
     */
    public function preProcessValueHash($incomingValueHash)
    {
        if (is_numeric($incomingValueHash)) {
            $incomingValueHash = (float)$incomingValueHash;
        }

        return $incomingValueHash;
    }
}
