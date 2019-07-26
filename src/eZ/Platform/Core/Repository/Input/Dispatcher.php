<?php

/**
 * File containing the Input Dispatcher class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\Core\Repository\Input;

use App\eZ\Platform\Core\Repository\Exceptions;
use Symfony\Contracts\HttpClient\ResponseInterface;

/**
 * Input dispatcher.
 */
class Dispatcher
{
    /**
     * Array of handlers.
     *
     * Structure:
     *
     * <code>
     *  array(
     *      <type> => <handler>,
     *      …
     *  )
     * </code>
     *
     * @var array
     */
    protected $handlers = [];

    /** @var \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher */
    protected $parsingDispatcher;

    /**
     * Construct from optional parsers array.
     *
     * @param \App\eZ\Platform\Core\Repository\Input\ParsingDispatcher $parsingDispatcher
     * @param array $handlers
     */
    public function __construct(ParsingDispatcher $parsingDispatcher, array $handlers = [])
    {
        $this->parsingDispatcher = $parsingDispatcher;
        foreach ($handlers as $type => $handler) {
            $this->addHandler($type, $handler);
        }
    }

    /**
     * Adds another handler for the given Content Type.
     *
     * @param string $type
     * @param \App\eZ\Platform\Core\Repository\Input\Handler $handler
     */
    public function addHandler($type, Handler $handler)
    {
        $this->handlers[$type] = $handler;
    }

    /**
     * Parse provided request.
     *
     * @param \App\eZ\Platform\Core\Repository\Message $response
     *
     * @return mixed
     */
    public function parse(ResponseInterface $response)
    {
        $headers = $response->getHeaders();
        if (!isset($headers['content-type'])) {
            throw new Exceptions\Parser('Missing Content-Type header in message.');
        }

        $mediaTypeParts = explode(';', $headers['content-type'][0]);
        $contentTypeParts = explode('+', $mediaTypeParts[0]);
        if (count($contentTypeParts) !== 2) {
            // TODO expose default format
            $contentTypeParts[1] = 'xml';
        }

        $media = $contentTypeParts[0];

        // add version if given
        if (count($mediaTypeParts) > 1) {
            $parameters = $this->parseParameters(implode(';', array_slice($mediaTypeParts, 1)));
            if (isset($parameters['version'])) {
                $media .= '; version=' . $parameters['version'];
            }
        }

        $format = $contentTypeParts[1];

        if (!isset($this->handlers[$format])) {
            throw new Exceptions\Parser("Unknown format specification: '{$format}'.");
        }

        $rawArray = $this->handlers[$format]->convert($response->getContent());

        // Only 1 XML root node
        $rootNodeArray = reset($rawArray);

        // @todo: This needs to be refactored in order to make the called URL
        // available to parsers in the server in a sane way
        if (isset($headers['Url'])) {
            $rootNodeArray['__url'] = $headers['Url'];
        }
        if (isset($headers['__publish'])) {
            $rootNodeArray['__publish'] = $headers['__publish'];
        }

        return $this->parsingDispatcher->parse($rootNodeArray);
    }

    private function parseParameters($mediaTypePart)
    {
        $parameters = [];
        foreach (explode(';', $mediaTypePart) as $parameterString) {
            list($parameterName, $parameterValue) = explode('=', $parameterString);
            $parameters[trim($parameterName)] = trim($parameterValue);
        }

        return $parameters;
    }
}
