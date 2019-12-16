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

namespace Drift\Preload\DependencyInjection\CompilerPass;

use Drift\HttpKernel\AsyncKernelEvents;
use Drift\Preload\Event\PreloadServicesCollector;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

/**
 * Class PreLoadCompilerPass
 */
class PreLoadCompilerPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->has(PreloadServicesCollector::class)) {
            $collector = new Definition(PreloadServicesCollector::class);

            $collector->setPublic($container->getParameter('kernel.environment') === 'test');
            $collector->addTag('kernel.event_listener', [
                'event' => AsyncKernelEvents::PRELOAD,
                'method' => 'preload'
            ]);

            $container->setDefinition(PreloadServicesCollector::class, $collector);

            $serviceIds = array_unique(
                array_merge(
                    array_keys(
                        $container->findTaggedServiceIds('preload')
                    ),
                    $container->getParameter('preload.services'),
                    $this->getAllPresetsServices($container)
                )
            );

            foreach ($serviceIds as $serviceId) {
                $this->makeServicePreloaded(
                    $container,
                    $collector,
                    $serviceId
                );
            }
        }
    }

    /**
     * Make a service a preload one
     *
     * @param ContainerBuilder $container
     * @param Definition $collector
     * @param string $serviceId
     */
    private function makeServicePreloaded(
        ContainerBuilder $container,
        Definition $collector,
        string $serviceId
    )
    {
        if (!$container->has($serviceId)) {
            return;
        }

        $collector->addMethodCall('inject', [
            $serviceId,
            new Reference($serviceId)
        ]);
    }

    /**
     * Return an array of all presets services
     *
     * @param ContainerBuilder $container
     *
     * @return array
     */
    private function getAllPresetsServices(ContainerBuilder $container) : array
    {
        $presets = $container->getParameter('preload.presets');
        $services = [];

        foreach ($presets as $preset) {
            $services = array_merge(
                $services,
                $preset::getServicesToPreload()
            );
        }

        return $services;
    }
}