<?php
/**
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */

namespace App\eZ\Platform\Core\FieldType\Author;

use App\eZ\Platform\API\Repository\Values\ValueObject;

class Value extends ValueObject implements \App\eZ\Platform\Core\FieldType\Value
{
    /**
     * List of authors.
     *
     * @var \eZ\Publish\Core\FieldType\Author\AuthorCollection
     */
    public $authors;

    /**
     * Construct a new Value object and initialize with $authors.
     *
     * @param \eZ\Publish\Core\FieldType\Author\Author[] $authors
     */
    public function __construct(array $authors = [])
    {
        $this->authors = new AuthorCollection($authors);
    }

    /**
     * @see \eZ\Publish\Core\FieldType\Value
     */
    public function __toString()
    {
        if (empty($this->authors)) {
            return '';
        }

        $authorNames = [];

        if ($this->authors instanceof AuthorCollection) {
            foreach ($this->authors as $author) {
                $authorNames[] = $author->name;
            }
        }

        return implode(', ', $authorNames);
    }
}