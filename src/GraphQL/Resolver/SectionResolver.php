<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\GraphQL\Resolver;

use App\eZ\Platform\API\Repository\SectionService;

/**
 * @internal
 */
class SectionResolver
{
    /**
     * @var \App\eZ\Platform\API\Repository\SectionService
     */
    private $sectionService;

    public function __construct(SectionService $sectionService)
    {
        $this->sectionService = $sectionService;
    }

    public function resolveSectionById($sectionId)
    {
        return $this->sectionService->loadSection($sectionId);
    }
}
