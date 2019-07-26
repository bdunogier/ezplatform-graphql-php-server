<?php

/**
 * File containing the ObjectStateUpdateStruct ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;

use App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;
use App\eZ\Platform\Core\Repository\Output\Generator;
use App\eZ\Platform\Core\Repository\Output\Visitor;

/**
 * ObjectStateUpdateStruct value object visitor.
 */
class ObjectStateUpdateStruct extends ValueObjectVisitor
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
        $generator->startObjectElement('ObjectStateUpdate');
        $visitor->setHeader('Content-Type', $generator->getMediaType('ObjectStateUpdate'));

        $generator->startValueElement('identifier', $data->identifier);
        $generator->endValueElement('identifier');

        $generator->startValueElement('defaultLanguageCode', $data->defaultLanguageCode);
        $generator->endValueElement('defaultLanguageCode');

        $generator->startHashElement('names');

        $generator->startList('value');
        foreach ($data->names as $languageCode => $name) {
            $generator->startValueElement('value', $name, array('languageCode' => $languageCode));
            $generator->endValueElement('value');
        }
        $generator->endList('value');

        $generator->endHashElement('names');

        $generator->startHashElement('descriptions');

        foreach ($data->descriptions as $languageCode => $description) {
            $generator->startValueElement('value', $description, array('languageCode' => $languageCode));
            $generator->endValueElement('value');
        }

        $generator->endHashElement('descriptions');

        $generator->endObjectElement('ObjectStateUpdate');
    }
}
