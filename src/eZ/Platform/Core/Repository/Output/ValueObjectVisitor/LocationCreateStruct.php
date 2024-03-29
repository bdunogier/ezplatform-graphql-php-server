<?php

/**
 * File containing the LocationCreateStruct ValueObjectVisitor class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;

use App\eZ\Platform\API\Repository\LocationService;
use App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;
use App\eZ\Platform\Core\Repository\Output\Generator;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use App\eZ\Platform\API\Repository\Values\Content\Location;

/**
 * LocationCreateStruct value object visitor.
 */
class LocationCreateStruct extends ValueObjectVisitor
{
    /** @var \App\eZ\Platform\API\Repository\LocationService */
    private $locationService;

    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Visit struct returned by controllers.
     *
     * @param \App\eZ\Platform\Core\Repository\Output\Visitor $visitor
     * @param \App\eZ\Platform\Core\Repository\Output\Generator $generator
     * @param \App\eZ\Platform\API\Repository\Values\Content\LocationCreateStruct $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $generator->startObjectElement('LocationCreate');
        $visitor->setHeader('Content-Type', $generator->getMediaType('LocationCreate'));

        $generator->startObjectElement('ParentLocation', 'Location');
        $this->addParentLocationHref($generator, $data->parentLocationId);
        $generator->endObjectElement('ParentLocation');

        $generator->startValueElement('priority', $data->priority);
        $generator->endValueElement('priority');

        $generator->startValueElement('hidden', $data->hidden ? 'true' : 'false');
        $generator->endValueElement('hidden');

        $generator->startValueElement('sortField', $this->getSortFieldName($data->sortField));
        $generator->endValueElement('sortField');

        $generator->startValueElement('sortOrder', $data->sortOrder == Location::SORT_ORDER_ASC ? 'ASC' : 'DESC');
        $generator->endValueElement('sortOrder');

        $generator->endObjectElement('LocationCreate');
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

    private function addParentLocationHref(Generator $generator, $parentLocationId)
    {
        $parentLocation = $this->locationService->loadLocation($parentLocationId);

        $generator->startAttribute(
            'href',
            $this->router->generate(
                'ezpublish_rest_loadLocation',
                ['locationPath' => trim($parentLocation->pathString, '/')]
            )
        );
        $generator->endAttribute('href');
    }
}
