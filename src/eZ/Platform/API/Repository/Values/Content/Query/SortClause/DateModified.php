<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\Query\SortClause\DateModified class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content\Query\SortClause;

use App\eZ\Platform\API\Repository\Values\Content\Query;
use App\eZ\Platform\API\Repository\Values\Content\Query\SortClause;

/**
 * Sets sort direction on the content modification date for a content query.
 */
class DateModified extends SortClause
{
    /**
     * Constructs a new DateModified SortClause.
     *
     * @param string $sortDirection
     */
    public function __construct($sortDirection = Query::SORT_ASC)
    {
        parent::__construct('date_modified', $sortDirection);
    }
}
