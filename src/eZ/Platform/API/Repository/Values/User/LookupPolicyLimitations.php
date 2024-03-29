<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
declare(strict_types=1);

namespace App\eZ\Platform\API\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * This class represents a result of lookup limitation for module and function in the context of current User.
 */
final class LookupPolicyLimitations extends ValueObject
{
    /** @var \eZ\Platform\API\Repository\Values\User\Policy */
    protected $policy;

    /** @var \eZ\Platform\API\Repository\Values\User\Limitation[] */
    protected $limitations;

    /**
     * @param \eZ\Platform\API\Repository\Values\User\Policy $policy
     * @param \eZ\Platform\API\Repository\Values\User\Limitation[] $limitations
     */
    public function __construct(Policy $policy, array $limitations = [])
    {
        parent::__construct();

        $this->policy = $policy;
        $this->limitations = $limitations;
    }
}
