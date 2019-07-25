<?php

/**
 * File containing the eZ\Platform\API\Repository\SectionService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository;

use App\eZ\Platform\API\Repository\Values\Content\Location;
use App\eZ\Platform\API\Repository\Values\Content\SectionCreateStruct;
use App\eZ\Platform\API\Repository\Values\Content\ContentInfo;
use App\eZ\Platform\API\Repository\Values\Content\Section;
use App\eZ\Platform\API\Repository\Values\Content\SectionUpdateStruct;

/**
 * Section service, used for section operations.
 */
interface SectionService
{
    /**
     * Creates the a new Section in the content repository.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to create a section
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If the new identifier in $sectionCreateStruct already exists
     *
     * @param \eZ\Platform\API\Repository\Values\Content\SectionCreateStruct $sectionCreateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Section The newly create section
     */
    public function createSection(SectionCreateStruct $sectionCreateStruct);

    /**
     * Updates the given in the content repository.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to create a section
     * @throws \eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If the new identifier already exists (if set in the update struct)
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Section $section
     * @param \eZ\Platform\API\Repository\Values\Content\SectionUpdateStruct $sectionUpdateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Section
     */
    public function updateSection(Section $section, SectionUpdateStruct $sectionUpdateStruct);

    /**
     * Loads a Section from its id ($sectionId).
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if section could not be found
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to read a section
     *
     * @param mixed $sectionId
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Section
     */
    public function loadSection($sectionId);

    /**
     * Loads all sections.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to read a section
     *
     * @return array of {@link \eZ\Platform\API\Repository\Values\Content\Section}
     */
    public function loadSections();

    /**
     * Loads a Section from its identifier ($sectionIdentifier).
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException if section could not be found
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to read a section
     *
     * @param string $sectionIdentifier
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Section
     */
    public function loadSectionByIdentifier($sectionIdentifier);

    /**
     * Counts the contents which $section is assigned to.
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Section $section
     *
     * @return int
     *
     * @deprecated since 6.0
     */
    public function countAssignedContents(Section $section);

    /**
     * Returns true if the given section is assigned to contents, or used in role policies, or in role assignments.
     *
     * This does not check user permissions.
     *
     * @since 6.0
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Section $section
     *
     * @return bool
     */
    public function isSectionUsed(Section $section);

    /**
     * Assigns the content to the given section
     * this method overrides the current assigned section.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user does not have access to view provided object
     *
     * @param \eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \eZ\Platform\API\Repository\Values\Content\Section $section
     */
    public function assignSection(ContentInfo $contentInfo, Section $section);

    /**
     * Assigns the subtree to the given section
     * this method overrides the current assigned section.
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Location $location
     * @param \eZ\Platform\API\Repository\Values\Content\Section $section
     */
    public function assignSectionToSubtree(Location $location, Section $section): void;

    /**
     * Deletes $section from content repository.
     *
     * @throws \eZ\Platform\API\Repository\Exceptions\NotFoundException If the specified section is not found
     * @throws \eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to delete a section
     * @throws \eZ\Platform\API\Repository\Exceptions\BadStateException  if section can not be deleted
     *         because it is still assigned to some contents.
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Section $section
     */
    public function deleteSection(Section $section);

    /**
     * Instantiates a new SectionCreateStruct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\SectionCreateStruct
     */
    public function newSectionCreateStruct();

    /**
     * Instantiates a new SectionUpdateStruct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\SectionUpdateStruct
     */
    public function newSectionUpdateStruct();
}
