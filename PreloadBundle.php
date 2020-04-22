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

namespace Drift\Preload;

use Drift\Preload\DependencyInjection\CompilerPass\PreloadNamespacesCompilerPass;
use Drift\Preload\DependencyInjection\CompilerPass\PreloadServicesCompilerPass;
use Drift\Preload\DependencyInjection\PreloadExtension;
use Mmoreram\BaseBundle\BaseBundle;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;

/**
 * Class PreloadBundle.
 */
class PreloadBundle extends BaseBundle
{
    /**
     * Builds bundle.
     *
     * @param ContainerBuilder $container Container
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new PreloadServicesCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -100);
        $container->addCompilerPass(new PreloadNamespacesCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, -100);
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
