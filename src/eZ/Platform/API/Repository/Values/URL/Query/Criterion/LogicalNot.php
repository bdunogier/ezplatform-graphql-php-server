<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\URL\Query\Criterion;

use App\eZ\Platform\API\Repository\Values\URL\Query\Criterion;

class LogicalNot extends LogicalOperator
{
    /**
     * Creates a new NOT logic criterion.
     *
     * Will match of the given criterion doesn't match
     *
     * @param Criterion $criterion criterion
     * @throws \InvalidArgumentException if more than one criterion is given in the array parameter
     */
    public function __construct(Criterion $criterion)
    {
        parent::__construct([$criterion]);
    }
}
