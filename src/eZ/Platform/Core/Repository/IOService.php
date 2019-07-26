<?php

/**
 * File containing the IOService class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\Repository;

use App\eZ\Platform\API\Repository\IOService as APIIOService;
use eZ\Publish\Core\IO\Values\BinaryFile;
use eZ\Publish\Core\IO\Values\BinaryFileCreateStruct;
use App\eZ\Platform\Core\Repository\RequestParser;
use App\eZ\Platform\Core\Repository\Input\Dispatcher;
use App\eZ\Platform\Core\Repository\Output\Visitor;

/**
 * Service used to handle io operations.
 */
class IOService implements APIIOService, Sessionable
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
     * @param mixed tringid
     *
     * @private
     */
    public function setSession($id)
    {
        if ($this->outputVisitor instanceof Sessionable) {
            $this->outputVisitor->setSession($id);
        }
    }

    /**
     * Creates a BinaryFileCreateStruct object from the uploaded file $uploadedFile.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException When given an invalid uploaded file
     *
     * @param array $uploadedFile The $_POST hash of an uploaded file
     *
     * @return \eZ\Publish\Core\IO\Values\BinaryFileCreateStruct
     */
    public function newBinaryCreateStructFromUploadedFile(array $uploadedFile)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Creates a BinaryFileCreateStruct object from $localFile.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\InvalidArgumentException When given a non existing / unreadable file
     *
     * @param string $localFile Path to local file
     *
     * @return \eZ\Publish\Core\IO\Values\BinaryFileCreateStruct
     */
    public function newBinaryCreateStructFromLocalFile($localFile)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Creates a  binary file in the the repository.
     *
     * @param \eZ\Publish\Core\IO\Values\BinaryFileCreateStruct $binaryFileCreateStruct
     *
     * @return \eZ\Publish\Core\IO\Values\BinaryFile The created BinaryFile object
     */
    public function createBinaryFile(BinaryFileCreateStruct $binaryFileCreateStruct)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Deletes the BinaryFile with $path.
     *
     * @param \eZ\Publish\Core\IO\Values\BinaryFile $binaryFile
     */
    public function deleteBinaryFile(BinaryFile $binaryFile)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Loads the binary file with $id.
     *
     * @throws \App\eZ\Platform\API\Repository\Exceptions\NotFoundException
     *
     * @param string $binaryFileid
     *
     * @return \eZ\Publish\Core\IO\Values\BinaryFile
     */
    public function loadBinaryFile($binaryFileId)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Returns a read (mode: rb) file resource to the binary file identified by $path.
     *
     * @param \eZ\Publish\Core\IO\Values\BinaryFile $binaryFile
     *
     * @return resource
     */
    public function getFileInputStream(BinaryFile $binaryFile)
    {
        throw new \Exception('@todo: Implement.');
    }

    /**
     * Returns the content of the binary file.
     *
     * @param \eZ\Publish\Core\IO\Values\BinaryFile $binaryFile
     *
     * @return string
     */
    public function getFileContents(BinaryFile $binaryFile)
    {
        throw new \Exception('@todo: Implement.');
    }
}
