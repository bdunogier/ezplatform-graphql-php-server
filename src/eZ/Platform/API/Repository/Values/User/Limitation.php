<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\User\Limitation class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\User;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * This class represents a Limitation applied to a policy.
 */
abstract class Limitation extends ValueObject
{
    // consts for BC
    const CONTENTTYPE = 'Class';
    const LANGUAGE = 'Language';
    const LOCATION = 'Node';
    const OWNER = 'Owner';
    const PARENTOWNER = 'ParentOwner';
    const PARENTCONTENTTYPE = 'ParentClass';
    const PARENTDEPTH = 'ParentDepth';
    const SECTION = 'Section';
    const NEWSECTION = 'NewSection';
    const SITEACCESS = 'SiteAccess';
    const STATE = 'State';
    const NEWSTATE = 'NewState';
    const SUBTREE = 'Subtree';
    const USERGROUP = 'Group';
    const PARENTUSERGROUP = 'ParentGroup';
    const STATUS = 'Status';

    /**
     * Returns the limitation identifier (one of the defined constants) or a custom limitation.
     *
     * @return string
     */
    abstract public function getIdentifier();

    /**
     * A read-only list of IDs or identifiers for which the limitation should be applied.
     *
     * The value of this property must conform to a hash, which means that it
     * may only consist of array and scalar values, but must not contain objects
     * or resources.
     *
     * @var mixed[]
     */
    public $limitationValues = [];
}
