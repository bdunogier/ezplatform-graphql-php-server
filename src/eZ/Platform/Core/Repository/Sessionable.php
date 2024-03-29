<?php

/**
 * File containing the Sessionable interface.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\SectionService}
 * interface.
 *
 * @see \App\eZ\Platform\API\Repository\SectionService
 */
interface Sessionable
{
    /**
     * Set session ID.
     *
     * Only for testing
     *
     * @param mixed $id
     *
     * @private
     */
    public function setSession($id);
}
