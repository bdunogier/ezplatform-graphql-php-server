<?php

/**
 * File containing the InputParser CompilerPass class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\DependencyInjection\Compiler;

use App\eZ\Platform\Core\Repository\Input\ParsingDispatcher as InputParsingDispatcher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Container compiler processor for the ezpublish_rest.input.parser service tag.
 * Maps input parsers to media types.
 *
 * Tag attributes: mediaType. Ex: application/vnd.ez.api.Content
 */
class InputParserPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(InputParsingDispatcher::class)) {
            return;
        }

        $definition = $container->getDefinition(InputParsingDispatcher::class);

        foreach ($container->findTaggedServiceIds('ezpublish_rest.input.parser') as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['mediaType'])) {
                    throw new \LogicException('ezpublish_rest.input.parser service tag needs a "mediaType" attribute to identify the input parser. None given.');
                }

                $definition->addMethodCall(
                    'addParser',
                    [$attribute['mediaType'], new Reference($id)]
                );
            }
        }
    }
}
