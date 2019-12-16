<?php

/*
 * This file is part of the Drift Http Kernel
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Drift\Preload\DependencyInjection;

use Drift\Preload\DependencyInjection\Preset\Preset;
use Drift\Preload\DependencyInjection\Preset\SymfonyPreset;
use Drift\Preload\Exception\InvalidPresetException;
use Mmoreram\BaseBundle\DependencyInjection\BaseConfiguration;
use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;

/**
 * Class PreloadConfiguration.
 */
class PreloadConfiguration extends BaseConfiguration
{
    /**
     * Configure the root node.
     *
     * @param ArrayNodeDefinition $rootNode Root node
     */
    protected function setupTree(ArrayNodeDefinition $rootNode)
    {
        $rootNode
            ->children()
                ->arrayNode('services')
                    ->scalarPrototype()
                    ->end()
                ->end()
                ->arrayNode('presets')
                    ->scalarPrototype()
                        ->beforeNormalization()
                            ->always(function(string $preset) {
                                $namespace = $preset;
                                switch ($preset) {
                                    case '@symfony':
                                        $namespace = SymfonyPreset::class;
                                }

                                if (
                                    !class_exists($namespace) ||
                                    !isset(class_implements($namespace)[Preset::class])
                                ) {
                                    throw new InvalidPresetException(sprintf(
                                        "Preset value should be a valid alias or the namespace of a class implementing `%s`. `%s` given.",
                                        Preset::class,
                                        $namespace
                                    ));
                                }

                                return $namespace;
                            })
                    ->end()
                ->end()
            ->end();
    }
}
