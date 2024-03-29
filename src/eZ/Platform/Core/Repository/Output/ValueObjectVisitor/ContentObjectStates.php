<?php

/**
 * File containing the ContentObjectStates visitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;

use App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;
use App\eZ\Platform\Core\Repository\Output\Generator;
use App\eZ\Platform\Core\Repository\Output\Visitor;

/**
 * ContentObjectStates value object visitor.
 */
class ContentObjectStates extends ValueObjectVisitor
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
        $generator->startObjectElement('ContentObjectStates');
        $visitor->setHeader('Content-Type', $generator->getMediaType('ContentObjectStates'));

        $generator->startList('ObjectState');

        foreach ($data->states as $state) {
            $generator->startObjectElement('ObjectState');
            $generator->startAttribute(
                'href',
                $this->requestParser->generate(
                    'objectstate',
                    array(
                        'objectstategroup' => $state->groupId,
                        'objectstate' => $state->objectState->id,
                    )
                )
            );
            $generator->endAttribute('href');
            $generator->endObjectElement('ObjectState');
        }

        $generator->endList('ObjectState');

        $generator->endObjectElement('ContentObjectStates');
    }
}
