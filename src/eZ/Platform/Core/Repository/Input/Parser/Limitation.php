<?php

/**
 * File containing the Limitation parser class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository\Input\Parser;

use App\eZ\Platform\Core\Repository\Input\BaseParser;
use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher;
use App\eZ\Platform\API\Repository\Values\User\Limitation as APILimitation;

/**
 * Parser for Limitation.
 */
class Limitation extends BaseParser
{
    /**
     * Parse input structure.
     *
     * @param array $data
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     *
     * @todo Error handling
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\Limitation
     */
    public function parse(array $data, ParsingDispatcher $parsingDispatcher)
    {
        $limitation = $this->getLimitationByIdentifier($data['_identifier']);

        $limitationValues = array();
        foreach ($data['values']['ref'] as $limitationValue) {
            $limitationValues[] = $limitationValue['_href'];
        }

        $limitation->limitationValues = $limitationValues;

        return $limitation;
    }

    /**
     * Instantiates Limitation object based on identifier.
     *
     * @param string $identifier
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException
     *
     * @return \App\eZ\Platform\API\Repository\Values\User\Limitation
     *
     * @todo Use dependency injection system
     */
    protected function getLimitationByIdentifier($identifier)
    {
        switch ($identifier) {
            case APILimitation::CONTENTTYPE:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\ContentTypeLimitation();

            case APILimitation::LANGUAGE:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\LanguageLimitation();

            case APILimitation::LOCATION:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\LocationLimitation();

            case APILimitation::OWNER:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\OwnerLimitation();

            case APILimitation::PARENTOWNER:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\ParentOwnerLimitation();

            case APILimitation::PARENTCONTENTTYPE:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\ParentContentTypeLimitation();

            case APILimitation::PARENTDEPTH:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\ParentDepthLimitation();

            case APILimitation::SECTION:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\SectionLimitation();

            case APILimitation::SITEACCESS:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\SiteaccessLimitation();

            case APILimitation::STATE:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\ObjectStateLimitation();

            case APILimitation::SUBTREE:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\SubtreeLimitation();

            case APILimitation::USERGROUP:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\UserGroupLimitation();

            case APILimitation::PARENTUSERGROUP:
                return new \App\eZ\Platform\API\Repository\Values\User\Limitation\ParentUserGroupLimitation();

            default:
                throw new \eZ\Publish\Core\Base\Exceptions\NotFoundException('Limitation', $identifier);
        }
    }
}
