<?php

/**
 * File containing the RoleAssignment ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;

use App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;
use App\eZ\Platform\Core\Repository\Output\Generator;
use App\eZ\Platform\Core\Repository\Output\Visitor;

/**
 * RoleAssignment value object visitor.
 */
class RoleAssignment extends ValueObjectVisitor
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
        $generator->startObjectElement('RoleAssignInput');
        $visitor->setHeader('Content-Type', $generator->getMediaType('RoleAssignInput'));

        $generator->startObjectElement('Role');

        $generator->startAttribute(
            'href',
            $this->requestParser->generate('role', array('role' => $data->getRole()->id))
        );
        $generator->endAttribute('href');

        $generator->endObjectElement('Role');

        $roleLimitation = $data->getRoleLimitation();
        if ($roleLimitation !== null) {
            $visitor->visitValueObject($roleLimitation);
        }

        $generator->endObjectElement('RoleAssignInput');
    }
}
