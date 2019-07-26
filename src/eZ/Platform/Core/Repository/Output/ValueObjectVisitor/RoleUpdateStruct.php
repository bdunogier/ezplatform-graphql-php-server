<?php

/**
 * File containing the RoleUpdateStruct ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;

use App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;
use App\eZ\Platform\Core\Repository\Output\Generator;
use App\eZ\Platform\Core\Repository\Output\Visitor;

/**
 * RoleUpdateStruct value object visitor.
 */
class RoleUpdateStruct extends ValueObjectVisitor
{
    /**
     * Visit struct returned by controllers.
     *
     * @param \App\eZ\Platform\Core\Repository\Output\Visitor $visitor
     * @param \App\eZ\Platform\Core\Repository\Output\Generator $generator
     * @param mixed $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('RoleInput');
        $visitor->setHeader('Content-Type', $generator->getMediaType('RoleInput'));

        $generator->startValueElement('identifier', $data->identifier);
        $generator->endValueElement('identifier');

        $generator->endObjectElement('RoleInput');
    }
}
