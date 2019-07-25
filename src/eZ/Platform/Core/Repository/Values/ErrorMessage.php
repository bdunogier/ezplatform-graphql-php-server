<?php
/**
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Values;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * @property-read int $code
 * @property-read string $message
 * @property-read string $description
 * @property-read mixed $trace
 * @property-read string $file
 * @property-read int $line
 */
class ErrorMessage extends ValueObject
{
    protected $code;

    protected $message;

    protected $description;

    protected $details;

    protected $trace;

    protected $file;

    protected $line;
}
