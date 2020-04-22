<?php

/*
 * This file is part of the Drift Project
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * Feel free to edit as you please, and have fun.
 *
 * @author Marc Morera <yuhu@mmoreram.com>
 */

declare(strict_types=1);

namespace Drift\Preload\DependencyInjection\CompilerPass;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class PreloadNamespacesCompilerPass.
 */
class PreloadNamespacesCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        $namespaces = array_merge(
            $container->getParameter('preload.namespaces'),
            $this->getAllPresetsNamespaces($container)
        );

        foreach ($namespaces as $namespace) {
            class_exists($namespace);
        }
    }

    /**
     * Return an array of all presets namespaces.
     *
     * @param ContainerBuilder $container
     *
     * @return array
     */
    private function getAllPresetsNamespaces(ContainerBuilder $container): array
    {
        $presets = $container->getParameter('preload.presets');
        $namespaces = [];
        $rootPath = $container->getParameter('kernel.project_dir');

        foreach ($presets as $preset) {
            $namespaces = array_merge(
                $namespaces,
                $preset::getNamespacesToPreload($rootPath)
            );
        }

        return $namespaces;
    }
}
