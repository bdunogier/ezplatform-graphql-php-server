<?php

/**
 * File containing the LocationUpdateStruct ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;

use App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;
use App\eZ\Platform\Core\Repository\Output\Generator;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use App\eZ\Platform\API\Repository\Values\Content\Location;

/**
 * LocationUpdateStruct value object visitor.
 */
class LocationUpdateStruct extends ValueObjectVisitor
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
        $generator->startObjectElement('LocationUpdate');
        $visitor->setHeader('Content-Type', $generator->getMediaType('LocationUpdate'));

        $generator->startValueElement('priority', $data->priority);
        $generator->endValueElement('priority');

        $generator->startValueElement('remoteId', $data->remoteId);
        $generator->endValueElement('remoteId');

        $generator->startValueElement('sortField', $this->getSortFieldName($data->sortField));
        $generator->endValueElement('sortField');

        $generator->startValueElement('sortOrder', $data->sortOrder == Location::SORT_ORDER_ASC ? 'ASC' : 'DESC');
        $generator->endValueElement('sortOrder');

        $generator->endObjectElement('LocationUpdate');
    }

    /**
     * Returns the '*' part of SORT_FIELD_* constant name.
     *
     * @param int $sortField
     *
     * @return string
     */
    protected function getSortFieldName($sortField)
    {
        $class = new \ReflectionClass('\\eZ\\Publish\\API\\Repository\\Values\\Content\\Location');
        foreach ($class->getConstants() as $constantName => $constantValue) {
            if ($constantValue == $sortField && strpos($constantName, 'SORT_FIELD_') >= 0) {
                return str_replace('SORT_FIELD_', '', $constantName);
            }
        }

        return '';
    }
}
