<?php

/**
 * File containing the SectionService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\API\Repository\SectionService as APISectionService;
use App\eZ\Platform\API\Repository\Values\Content\ContentInfo;
use App\eZ\Platform\API\Repository\Values\Content\Location;
use App\eZ\Platform\API\Repository\Values\Content\Section;
use App\eZ\Platform\API\Repository\Values\Content\SectionCreateStruct;
use App\eZ\Platform\API\Repository\Values\Content\SectionUpdateStruct;
use App\eZ\Platform\Core\Repository\Exceptions\InvalidArgumentException;
use App\eZ\Platform\Core\Repository\Exceptions\ForbiddenException;
use App\eZ\Platform\Core\Repository\RequestParser;
use App\eZ\Platform\Core\Repository\Input\Dispatcher;
use App\eZ\Platform\Core\Repository\Output\Visitor;
use App\eZ\Platform\Core\Repository\Message;
use App\eZ\Platform\Core\Repository\Values\RestContentMetadataUpdateStruct;

/**
 * Implementation of the {@link \App\eZ\Platform\API\Repository\SectionService}
 * interface.
 *
 * @see \App\eZ\Platform\API\Repository\SectionService
 */
class SectionService implements APISectionService, Sessionable
{
    /** @var \App\eZ\Platform\Core\Repository\HttpClient */
    private $client;

    /** @var \App\eZ\Platform\Core\Repository\Input\Dispatcher */
    private $inputDispatcher;

    /** @var \App\eZ\Platform\Core\Repository\Output\Visitor */
    private $outputVisitor;

    /** @var \App\eZ\Platform\Core\Repository\RequestParser */
    private $requestParser;

    /**
     * @param \App\eZ\Platform\Core\Repository\\Symfony\Contracts\HttpClient\HttpClientInterface $ezpRestClient
     * @param \App\eZ\Platform\Core\Repository\Input\Dispatcher $inputDispatcher
     * @param \App\eZ\Platform\Core\Repository\Output\Visitor $outputVisitor
     * @param \App\eZ\Platform\Core\Repository\RequestParser $requestParser
     */
    public function __construct(\Symfony\Contracts\HttpClient\HttpClientInterface $ezpRestClient, Dispatcher $inputDispatcher, Visitor $outputVisitor, RequestParser $requestParser)
    {
        $this->client = $ezpRestClient;
        $this->inputDispatcher = $inputDispatcher;
        $this->outputVisitor = $outputVisitor;
        $this->requestParser = $requestParser;
    }

    /**
     * Set session ID.
     *
     * Only for testing
     *
     * @param mixed $id
     *
     * @private
     */
    public function setSession($id)
    {
        if ($this->client instanceof Sessionable) {
            $this->client->setSession($id);
        }
    }

    /**
     * Creates the a new Section in the content repository.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to create a section
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If the new identifier in $sectionCreateStruct already exists
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\SectionCreateStruct $sectionCreateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Section The newly create section
     */
    public function createSection(SectionCreateStruct $sectionCreateStruct)
    {
        $inputMessage = $this->outputVisitor->visit($sectionCreateStruct);
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('Section');

        $result = $this->client->request(
            'POST',
            $this->requestParser->generate('sections'),
            $inputMessage
        );

        try {
            return $this->inputDispatcher->parse($result);
        } catch (ForbiddenException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Updates the given in the content repository.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to create a section
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException If the new identifier already exists (if set in the update struct)
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Section $section
     * @param \App\eZ\Platform\API\Repository\Values\Content\SectionUpdateStruct $sectionUpdateStruct
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Section
     */
    public function updateSection(Section $section, SectionUpdateStruct $sectionUpdateStruct)
    {
        $inputMessage = $this->outputVisitor->visit($sectionUpdateStruct);
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('Section');
        $inputMessage->headers['X-HTTP-Method-Override'] = 'PATCH';

        // Should originally be PATCH, but PHP's shiny new internal web server
        // dies with it.
        $result = $this->client->request(
            'POST',
            $section->id,
            $inputMessage
        );

        try {
            return $this->inputDispatcher->parse($result);
        } catch (ForbiddenException $e) {
            throw new InvalidArgumentException($e->getMessage(), $e->getCode());
        }
    }

    /**
     * Loads a Section from its id ($sectionId).
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException if section could not be found
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to read a section
     *
     * @param mixed $sectionId
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Section
     */
    public function loadSection($sectionId)
    {
        $response = $this->client->request(
            'GET',
            $sectionId,
            new Message(
                array('Accept' => $this->outputVisitor->getMediaType('Section'))
            )
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * Loads all sections.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to read a section
     *
     * @return array of {@link \App\eZ\Platform\API\Repository\Values\Content\Section}
     */
    public function loadSections()
    {
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('sections'),
            new Message(
                array('Accept' => $this->outputVisitor->getMediaType('SectionList'))
            )
        );

        return $this->inputDispatcher->parse($response);
    }

    /**
     * Loads a Section from its identifier ($sectionIdentifier).
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException if section could not be found
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to read a section
     *
     * @param string $sectionIdentifier
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\Section
     */
    public function loadSectionByIdentifier($sectionIdentifier)
    {
        $response = $this->client->request(
            'GET',
            $this->requestParser->generate('sectionByIdentifier', array('section' => $sectionIdentifier)),
            new Message(
                array('Accept' => $this->outputVisitor->getMediaType('SectionList'))
            )
        );
        $result = $this->inputDispatcher->parse($response);

        return reset($result);
    }

    /**
     * Counts the contents which $section is assigned to.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Section $section
     *
     * @return int
     *
     * @deprecated since 6.0
     */
    public function countAssignedContents(Section $section)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Returns true if the given section is assigned to contents, or used in role policies, or in role assignments.
     *
     * This does not check user permissions.
     *
     * @since 6.0
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Section $section
     *
     * @return bool
     */
    public function isSectionUsed(Section $section)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Assigns the content to the given section
     * this method overrides the current assigned section.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If user does not have access to view provided object
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $contentInfo
     * @param \App\eZ\Platform\API\Repository\Values\Content\Section $section
     *
     * @todo In order to make the integration test for this method running, the
     *       countAssignedContents() method must be implemented. Otherwise this
     *       should work fine.
     */
    public function assignSection(ContentInfo $contentInfo, Section $section)
    {
        $inputMessage = $this->outputVisitor->visit(
            new RestContentMetadataUpdateStruct(
                array('sectionId' => $section->id)
            )
        );
        $inputMessage->headers['Accept'] = $this->outputVisitor->getMediaType('Content');
        $inputMessage->headers['X-HTTP-Method-Override'] = 'PATCH';

        $this->client->request(
            'POST',
            $contentInfo->id,
            $inputMessage
        );

        // Will throw exception on error, no return value for method
        // @todo: Deactivated due to missing implementation of visitor for
        // content on the server side.
        // Should be: $result = $this->inputDispatcher->parse( $response );
    }

    /**
     * Deletes $section from content repository.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException If the specified section is not found
     * @throws \App\eZ\Platform\API\Repository\Exceptions\UnauthorizedException If the current user user is not allowed to delete a section
     * @throws \App\eZ\Platform\API\Repository\Exceptions\BadStateException  if section can not be deleted
     *         because it is still assigned to some contents.
     *
     * @param \App\eZ\Platform\API\Repository\Values\Content\Section $section
     */
    public function deleteSection(Section $section)
    {
        $response = $this->client->request(
            'DELETE',
            $section->id,
            new Message(
                // @todo: What media-type should we set here? Actually, it should be
                // all expected exceptions + none? Or is "Section" correct,
                // since this is what is to be expected by the resource
                // identified by the URL?
                array('Accept' => $this->outputVisitor->getMediaType('Section'))
            )
        );

        if (!empty($response->body)) {
            $this->inputDispatcher->parse($response);
        }
    }

    /**
     * Instantiates a new SectionCreateStruct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\SectionCreateStruct
     */
    public function newSectionCreateStruct()
    {
        return new SectionCreateStruct();
    }

    /**
     * Instantiates a new SectionUpdateStruct.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\SectionUpdateStruct
     */
    public function newSectionUpdateStruct()
    {
        return new SectionUpdateStruct();
    }

    /**
     * Assigns the subtree to the given section
     * this method overrides the current assigned section.
     *
     * @param \eZ\Platform\API\Repository\Values\Content\Location $location
     * @param \eZ\Platform\API\Repository\Values\Content\Section $section
     */
    public function assignSectionToSubtree(Location $location, Section $section): void
    {
        // TODO: Implement assignSectionToSubtree() method.
    }
}
