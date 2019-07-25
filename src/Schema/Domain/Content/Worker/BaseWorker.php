<?php

/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\Schema\Domain\Content\Worker;

use App\Schema\Domain\Content\NameHelper;

class BaseWorker
{
    /**
     * @var \App\Schema\Domain\Content\NameHelper
     */
    private $nameHelper;

    public function setNameHelper(NameHelper $nameHelper)
    {
        $this->nameHelper = $nameHelper;
    }

    protected function getNameHelper()
    {
        return $this->nameHelper;
    }
}
