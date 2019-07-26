<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor\Criterion;

use App\eZ\Platform\Core\Repository\Output\Generator;
use App\eZ\Platform\Core\Repository\Output\ValueObjectVisitor;
use App\eZ\Platform\Core\Repository\Output\Visitor;

class ContentTypeIdentifier extends ValueObjectVisitor
{
    /**
     * @param Visitor $visitor
     * @param Generator $generator
     * @param \App\eZ\Platform\API\Repository\Values\Content\Query\Criterion\ContentTypeIdentifier $data
     */
    public function visit(Visitor $visitor, Generator $generator, $data)
    {
        $contentTypeIdentifiers = is_array($data->value) ? $data->value : [$data->value];

        $generator->startList('ContentTypeIdentifierCriterion');
        foreach ($contentTypeIdentifiers as $contentTypeIdentifier) {
            $generator->startValueElement('ContentTypeIdentifierCriterion', $contentTypeIdentifier);
            $generator->endValueElement('ContentTypeIdentifierCriterion');
        }
        $generator->endList('ContentTypeIdentifierCriterion');
    }
}
