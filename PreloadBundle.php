<?php

namespace Drift\Preload;

use Drift\Preload\DependencyInjection\CompilerPass\PreloadNamespacesCompilerPass;
use Drift\Preload\DependencyInjection\CompilerPass\PreloadServicesCompilerPass;
use Drift\Preload\DependencyInjection\PreloadExtension;
use Mmoreram\BaseBundle\BaseBundle;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Class PreloadBundle.
 */
class PreloadBundle extends BaseBundle
{
    /**
     * Return a CompilerPass instance array.
     *
     * @return CompilerPassInterface[]
     */
    public function getCompilerPasses(): array
    {
        return [
            new PreloadServicesCompilerPass(),
            new PreloadNamespacesCompilerPass(),
        ];
    }

    /**
     * Returns the bundle's container extension.
     *
     * @return ExtensionInterface|null The container extension
     *
     * @throws \LogicException
     */
    public function getContainerExtension()
    {
        return new PreloadExtension();
    }
}
