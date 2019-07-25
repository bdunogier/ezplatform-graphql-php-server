<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Schema\Domain\Content;

use eZ\Publish\API\Repository\LanguageService;
use App\Schema\Domain\Iterator;
use App\Schema\Builder;
use Generator;

class LanguagesIterator implements Iterator
{
    /**
     * @var \eZ\Publish\API\Repository\LanguageService
     */
    private $languageService;

    public function __construct(LanguageService $languageService)
    {
        $this->languageService = $languageService;
    }

    public function init(Builder $schema)
    {
    }

    public function iterate(): Generator
    {
        foreach ($this->languageService->loadLanguages() as $language) {
            yield ['Language' => $language];
        }
    }
}
