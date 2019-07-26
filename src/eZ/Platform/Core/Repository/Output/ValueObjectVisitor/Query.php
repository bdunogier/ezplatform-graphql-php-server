<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;

use App\eZ\Platform\API\Repository\Values\Content\LocationQuery;
use App\eZ\Platform\Core\Repository\Output\Generator;
use App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use eZ\Publish\Core\Base\Exceptions;
use App\eZ\Platform\API\Repository\Values\Content\LocationQuery as LocationQueryValue;
use App\eZ\Platform\API\Repository\Values\Content\Query as ContentQueryValue;

class Query extends ValueObjectVisitor
{
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        if ($data instanceof LocationQueryValue) {
            $rootObjectElement = 'LocationQuery';
        } else if ($data instanceof ContentQueryValue) {
            $rootObjectElement = 'ContentQuery';
        } else {
            throw new Exceptions\InvalidArgumentException("ViewInput Query", "No content nor location query found in ViewInput");
        }
        $generator->startObjectElement($rootObjectElement, 'Query');

        if (isset($data->filter)) {
            $generator->startHashElement('Filter');
            $visitor->visitValueObject($data->filter);
            $generator->endHashElement('Filter');
        }

        if (isset($data->query)) {
            $generator->startHashElement('Query');
            $visitor->visitValueObject($data->query);
            $generator->endhashElement('Query');
        }

        // $generator->startObjectElement('SortClauses');
        // foreach ($data->sortClauses as $sortClause) {
        //     $visitor->visitValueObject($sortClause);
        // }
        // $generator->endObjectElement('SortClauses');

        $generator->endObjectElement($rootObjectElement);
    }
}
