<?php

/**
 * File containing the ViewInput ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;

use App\eZ\Platform\Core\Repository\Output\Generator;
use App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use eZ\Publish\Core\Base\Exceptions;
use eZ\Publish\Core\REST\Server\Values\RestViewInput;

/**
 * ViewInput value object visitor.
 */
class ViewInput extends ValueObjectVisitor
{
    /**
     * @param Visitor $visitor
     * @param Generator $generator
     * @param \App\eZ\Platform\Core\Repository\Values\ViewInput $data
     * @throws Exceptions\InvalidArgumentException
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('ViewInput');

        $generator->startValueElement('identifier', $data->identifier);
        $generator->endValueElement('identifier');

        if ($data->locationQuery !== null ) {
            $queryElementName = 'locationQuery';
        } elseif ($data->contentQuery !== null ) {
            $queryElementName = 'contentQuery';
        } else {
            throw new Exceptions\InvalidArgumentException("ViewInput Query", "No content nor location query found in ViewInput");
        }

        $visitor->visitValueObject($data->$queryElementName);

        $generator->endObjectElement('ViewInput');
    }
}
