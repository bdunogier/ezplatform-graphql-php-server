<?php

/**
 * File containing the InputParser CompilerPass class.
 *
 * @copyright Copyright (C) eZ Systems AS. All rights reserved.
 * @license For full copyright and license information view LICENSE file distributed with this source code.
 */
namespace App\eZ\Platform\DependencyInjection\Compiler;

use App\eZ\Platform\Core\Repository\Input\Dispatcher as InputDispatcher;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Container processor for the ezpublish_rest.input.handler service tag.
 * Maps input formats (json, xml) to handlers.
 *
 * Tag attributes: format. Ex: json
 */
class InputHandlerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition(InputDispatcher::class)) {
            return;
        }

        $definition = $container->getDefinition(InputDispatcher::class);

        // @todo rethink the relationships between registries. Rename if required.
        foreach ($container->findTaggedServiceIds('ezpublish_rest.input.handler') as $id => $attributes) {
            foreach ($attributes as $attribute) {
                if (!isset($attribute['format'])) {
                    throw new \LogicException('ezpublish_rest.input.handler service tag needs a "format" attribute to identify the input handler. None given.');
                }

                $definition->addMethodCall(
                    'addHandler',
                    [$attribute['format'], new Reference($id)]
                );
            }
        }
    }
}
