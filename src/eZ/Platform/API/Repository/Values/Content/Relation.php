<?php

/**
 * File containing the eZ\Platform\API\Repository\Values\Content\Relation class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\API\Repository\Values\Content;

use App\eZ\Platform\API\Repository\Values\ValueObject;

/**
 * Class representing a relation between content.
 *
 * @property-read mixed $id the internal id of the relation
 * @property-read string $sourceFieldDefinitionIdentifier the field definition identifier of the field where this relation is anchored if the relation is of type EMBED, LINK, or ATTRIBUTE
 *
 * @property-read \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $sourceContentInfo - calls {@link getSourceContentInfo()}
 * @property-read \App\eZ\Platform\API\Repository\Values\Content\ContentInfo $destinationContentInfo - calls {@link getDestinationContentInfo()}
 * @property-read int $type The relation type bitmask containing one or more of Relation::COMMON, Relation::EMBED, Relation::LINK, Relation::FIELD
 */
abstract class Relation extends ValueObject
{
    /**
     * The relation type COMMON is a general relation between object set by a user.
     *
     * @var int
     */
    const COMMON = 1;

    /**
     * the relation type EMBED is set for a relation which is anchored as embedded link in an attribute value.
     *
     * @var int
     */
    const EMBED = 2;

    /**
     * the relation type LINK is set for a relation which is anchored as link in an attribute value.
     *
     * @var int
     */
    const LINK = 4;

    /**
     * the relation type FIELD is set for a relation which is part of an relation attribute value.
     *
     * @var int
     */
    const FIELD = 8;

    /**
     * the relation type ASSET is set for a relation to asset in an attribute value.
     *
     * @var int
     */
    const ASSET = 16;

    /**
     * Id of the relation.
     *
     * @var mixed
     */
    protected $id;

    /**
     * Source Content Type Field Definition Id.
     * For relation not of type RelationType::COMMON this field denotes the field definition id
     * of the attribute where the relation is anchored.
     *
     * @var string
     */
    protected $sourceFieldDefinitionIdentifier;

    /**
     * the content of the source content of the relation.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentInfo
     */
    abstract public function getSourceContentInfo();

    /**
     * the content of the destination content of the relation.
     *
     * @return \App\eZ\Platform\API\Repository\Values\Content\ContentInfo
     */
    abstract public function getDestinationContentInfo();

    /**
     * The relation type bitmask.
     *
     * @see Relation::COMMON, Relation::EMBED, Relation::LINK, Relation::FIELD, Relation::ASSET
     *
     * @var int
     */
    protected $type;
}
