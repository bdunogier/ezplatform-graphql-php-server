<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\Query\SortClause\DatePublished class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content\Query\SortClause;

use App\eZ\Platform\API\Repository\Values\Content\Query;
use App\eZ\Platform\API\Repository\Values\Content\Query\SortClause;

/**
 * Sets sort direction on the content creation date for a content query.
 */
class DatePublished extends SortClause
{
    /**
     * Constructs a new DatePublished SortClause.
     *
     * @param string $sortDirection
     */
    public function __construct($sortDirection = Query::SORT_ASC)
    {
        parent::__construct('date_published', $sortDirection);
    }
}
